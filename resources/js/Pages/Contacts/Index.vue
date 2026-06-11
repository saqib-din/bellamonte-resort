<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><a href="#">Contacts</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Contacts List</h2></div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <AppModal :show="!!viewTarget" title="Contact Details" :max-width="640" @close="viewTarget = null">
        <dl class="row mb-0" v-if="viewTarget">
            <dt class="col-sm-4 text-muted">ID</dt><dd class="col-sm-8">{{ viewTarget.id }}</dd>
            <dt class="col-sm-4 text-muted">Name</dt><dd class="col-sm-8">{{ viewTarget.name }}</dd>
            <dt class="col-sm-4 text-muted">Email</dt><dd class="col-sm-8"><a :href="`mailto:${viewTarget.email}`">{{ viewTarget.email }}</a></dd>
            <dt class="col-sm-4 text-muted">Phone</dt><dd class="col-sm-8">{{ viewTarget.phone || '—' }}</dd>
            <dt class="col-sm-4 text-muted">Subject</dt><dd class="col-sm-8">{{ viewTarget.subject || '—' }}</dd>
            <dt class="col-sm-4 text-muted">Message</dt><dd class="col-sm-8">{{ viewTarget.message }}</dd>
            <dt class="col-sm-4 text-muted">Terms Accepted At</dt><dd class="col-sm-8">{{ viewTarget.terms_accepted_time || '—' }}</dd>
            <dt class="col-sm-4 text-muted">IP Address</dt><dd class="col-sm-8">{{ viewTarget.ip_address || '—' }}</dd>
            <dt class="col-sm-4 text-muted">User Agent</dt><dd class="col-sm-8" style="word-break:break-word; font-size:12px;">{{ viewTarget.user_agent || '—' }}</dd>
            <dt class="col-sm-4 text-muted">Admin Reply</dt>
            <dd class="col-sm-8">
                <span v-if="viewTarget.reply_message" class="text-success">{{ viewTarget.reply_message }}</span>
                <span v-else class="text-muted fst-italic">No reply yet</span>
            </dd>
            <dt class="col-sm-4 text-muted">Replied At</dt><dd class="col-sm-8">{{ viewTarget.replied_at || 'No reply yet' }}</dd>
        </dl>
        <div class="text-end mt-3">
            <button v-if="viewTarget && !viewTarget.is_replied" class="btn btn-light-info me-2" @click="openReply(viewTarget)"><i class="ti ti-arrow-back-up me-1"></i> Reply Now</button>
            <button class="btn btn-outline-secondary" @click="viewTarget = null">Close</button>
        </div>
    </AppModal>

    <!-- Reply Modal -->
    <AppModal :show="!!replyTarget" :title="replyTarget ? `Reply to ${replyTarget.name}` : ''" :max-width="520" @close="replyTarget = null">
        <template v-if="replyTarget">
            <div class="alert alert-light-secondary mb-3 py-2">
                <small><strong>To:</strong> {{ replyTarget.email }}<br><strong>Subject:</strong> {{ replyTarget.subject || 'Contact Inquiry' }}</small>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted" style="font-size:12px;">ORIGINAL MESSAGE</label>
                <div class="p-3 bg-light rounded" style="font-size:13px; color:#666; border-left:3px solid #6c757d;">{{ replyTarget.message }}</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Reply <span class="text-danger">*</span></label>
                <textarea v-model="replyForm.reply_message" class="form-control" :class="{ 'is-invalid': replyForm.errors.reply_message }" rows="5" placeholder="Type your reply here..."></textarea>
                <div v-if="replyForm.errors.reply_message" class="invalid-feedback">{{ replyForm.errors.reply_message }}</div>
            </div>
            <div class="text-end">
                <button class="btn btn-light-secondary me-2" @click="replyTarget = null">Cancel</button>
                <button class="btn btn-light-info" :disabled="replyForm.processing" @click="sendReply"><i class="ti ti-send me-1"></i> Send Reply</button>
            </div>
        </template>
    </AppModal>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Contacts List</h5>
                    <input v-if="contacts.length" type="text" v-model="search" class="form-control form-control-sm" style="width:220px;" placeholder="Search...">
                </div>
                <div class="card-body table-card">
                    <div v-if="!contacts.length" class="text-center" style="min-height:300px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                        <img src="/admin/assets/images/application/img-empty-mail.png" alt="No mail" class="img-fluid mb-4" style="max-width:200px;">
                        <h2><b>There is No Mail</b></h2>
                    </div>
                    <div v-else class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Phone</th><th>Replied</th><th class="text-end">Action</th></tr>
                            </thead>
                            <tbody>
                                <tr v-for="c in filtered" :key="c.id">
                                    <td>{{ c.id }}</td>
                                    <td>{{ c.name }}</td>
                                    <td>{{ c.email }}</td>
                                    <td>{{ c.subject || '—' }}</td>
                                    <td>{{ c.phone || '—' }}</td>
                                    <td><span class="badge" :class="c.is_replied ? 'bg-light-success' : 'bg-light-danger'">{{ c.is_replied ? 'Yes' : 'No' }}</span></td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="View" @click="viewTarget = c"><i class="ti ti-eye f-20"></i></a>
                                        <a v-if="!c.is_replied" href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Reply" @click="openReply(c)"><i class="ti ti-arrow-back-up f-20"></i></a>
                                        <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(c)"><i class="ti ti-trash f-20"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="!filtered.length">
                                    <td colspan="7" class="text-center py-4 text-muted">No contacts match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AppModal from '@/Components/AppModal.vue';
import { swalDelete } from '@/lib/swalDelete';

defineOptions({ layout: AppLayout });

const props = defineProps({
    contacts: { type: Array, default: () => [] },
});

const search = ref('');
const filtered = computed(() => {
    const s = search.value.trim().toLowerCase();
    if (!s) return props.contacts;
    return props.contacts.filter((c) =>
        (c.name || '').toLowerCase().includes(s) ||
        (c.email || '').toLowerCase().includes(s) ||
        (c.subject || '').toLowerCase().includes(s) ||
        (c.phone || '').toLowerCase().includes(s),
    );
});

const viewTarget   = ref(null);
const replyTarget  = ref(null);
const replyForm    = useForm({ reply_message: '' });

function openReply(c) {
    viewTarget.value = null;
    replyTarget.value = c;
    replyForm.reset();
    replyForm.clearErrors();
}
function sendReply() {
    replyForm.post(`/contacts/${replyTarget.value.id}/reply`, {
        preserveScroll: true,
        onSuccess: () => { replyTarget.value = null; },
    });
}
function askDelete(c) {
    swalDelete(() => router.delete(`/contacts/${c.id}`, { preserveScroll: true }));
}
</script>
