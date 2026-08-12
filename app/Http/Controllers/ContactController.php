<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $message = ContactMessage::create($data);

        $adminEmail = env('ADMIN_NOTIFY_EMAIL', User::where('role', 'admin')->value('email'));

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewContactMessage($data));
        }

        return redirect()->back()->with('status', 'Your message has been sent. We will reply within one business day.');
    }

    public function toggleRead(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'is_read' => ! $contactMessage->is_read,
        ]);

        return redirect()->back();
    }
}
