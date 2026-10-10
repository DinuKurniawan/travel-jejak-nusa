<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'subject' => ['sometimes', 'string', Rule::in(['open-trip', 'private-tour', 'honeymoon', 'outing-kantor', 'lainnya'])],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        return response()->json(ContactMessage::create($data), 201);
    }

    public function index()
    {
        return ContactMessage::query()->orderByDesc('id')->paginate(20);
    }

    public function update(Request $request, ContactMessage $message)
    {
        $message->update($request->validate([
            'status' => ['required', 'string', Rule::in(['unread', 'read', 'replied', 'archived'])],
        ]));

        return $message;
    }
}
