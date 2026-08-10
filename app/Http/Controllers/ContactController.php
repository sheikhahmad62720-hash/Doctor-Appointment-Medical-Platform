<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

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

        ContactMessage::create($data);

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
