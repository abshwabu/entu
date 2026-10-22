<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Jobs\SendTelegramNotification;
use App\Mail\NewContactMessageMail;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contact');
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        // Honeypot check: If the hidden honeypot field is filled, silently discard spam
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Thank you! Your message has been sent successfully.');
        }

        $validated = $request->validated();
        $body = $validated['body'] ?? $validated['message'];

        $message = Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'body' => $body,
        ]);

        // Send queued email to the designer
        $recipient = setting('email', config('mail.from.address'));
        Mail::to($recipient)->queue(new NewContactMessageMail($message));

        // Optional Telegram notification hook (conditional on config flag)
        SendTelegramNotification::dispatch($message);

        return redirect()->route('contact')->with('success', 'Thank you! Your message has been received. I will review your inquiry and get back to you shortly.');
    }
}
