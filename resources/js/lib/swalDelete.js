export function swalDelete(onConfirm, opts = {}) {
    const Swal = window.Swal;

    // Fallback agar SweetAlert load na ho
    if (!Swal) {
        if (window.confirm(opts.text || "You won't be able to revert this!")) onConfirm();
        return;
    }

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons.fire({
        title: opts.title || 'Are you sure?',
        text: opts.text || "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) onConfirm();
    });
}
