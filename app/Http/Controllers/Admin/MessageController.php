<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('can see form answers');

        return Inertia::render('Admin/Messages/Index', [
            'messages' => ContactMessage::with('commissionRequest')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function show(ContactMessage $message)
    {
        $this->authorize('admin');

        $message->load('commissionRequest');

        return Inertia::render('Admin/Messages/Show', [
            'message' => $message,
        ]);
    }

    public function update(Request $request, ContactMessage $message)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'status' => 'required|string|in:new,read,replied,archived',
        ]);

        $message->update($validated);

        return to_route('admin.messages.index')->with('success', 'Message status updated successfully.');
    }

    public function destroy(ContactMessage $message)
    {
        $this->authorize('admin');

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
