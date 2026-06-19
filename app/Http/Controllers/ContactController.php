<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

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
            'email'   => ['required', 'email', 'max:150', 'regex:/^.+@.+\..+$/'],
            'phone'   => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{7,20}$/'],
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ], [
            'phone.regex' => 'Please enter a valid phone number — digits and + - ( ) only.',
            'email.regex' => 'Please enter a valid email address, e.g. name@example.com.',
            'email.email' => 'Please enter a valid email address, e.g. name@example.com.',
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

    // ─── Admin: List all contacts (Inertia)
    public function index()
    {
        $contacts = Contact::latest()->get()->map(fn ($c) => [
            'id'                  => $c->id,
            'name'                => $c->name,
            'email'               => $c->email,
            'phone'               => $c->phone,
            'subject'             => $c->subject,
            'message'             => $c->message,
            'is_replied'          => (bool) $c->is_replied,
            'reply_message'       => $c->reply_message,
            'replied_at'          => optional($c->replied_at)->format('d M Y, h:i A'),
            'terms_accepted_time' => optional($c->terms_accepted_time)->format('d M Y, h:i A'),
            'ip_address'          => $c->ip_address,
            'user_agent'          => $c->user_agent,
            'created_at'          => optional($c->created_at)->format('d M Y, h:i A'),
        ]);

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
        ]);
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
