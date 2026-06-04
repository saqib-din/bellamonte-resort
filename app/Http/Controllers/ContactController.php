<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // ─── Public: Contact Page Show 
    public function show()
    {
        return view('pages.contact-us.contact');
    }

    // ─── Public: Store new contact submission 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        Contact::create([
            ...$validated,
            'terms_accepted'      => true,
            'terms_accepted_time' => now(),
            'ip_address'          => $request->ip(),
            'user_agent'          => $request->userAgent(),
        ]);

        return back()->with('success', 'Your message has been sent! We will get back to you soon.');
    }

    // ─── Admin: List all contacts 
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('pages.admin-side.contacts.index', compact('contacts'));
    }

    // ─── Admin: Reply to a contact via email 
    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'reply_message' => 'required|string|max:5000',
        ]);

        try {
            $contact->reply_message = $request->reply_message;
            Mail::to($contact->email)->send(new ContactReplyMail($contact));

            $contact->update([
                'reply_message' => $request->reply_message,
                'is_replied'    => true,
                'replied_at'    => now(),
            ]);

            return redirect()->route('contacts.index')
                ->with('success', "Reply sent to {$contact->name} successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send email. Please check mail settings.');
        }
    }

    // ─── Admin: Delete a contact 
    public function delete(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Contact deleted successfully.');
    }
}
