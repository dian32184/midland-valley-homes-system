<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerNotificationController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'notified_at' => ['required', 'date'],
            'message' => ['required', 'string'],
            'notification_type' => ['required', Rule::in(['sms', 'email', 'phone_call', 'other'])],
        ]);

        $customer->notifications()->create([
            'notified_at' => $validated['notified_at'],
            'message' => $validated['message'],
            'notification_type' => $validated['notification_type'],
            'logged_by' => auth()->id(),
        ]);

        return back()->with('success', 'Notification log added successfully.');
    }
}
