<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMessageRequest;
use App\Models\ContactMessage;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('can see form answers');

        return Inertia::render('Admin/Messages/Index', [
            'messages' => ContactMessage::with('commissionRequest')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function show(ContactMessage $message): \Inertia\Response
    {
        $this->authorize('admin');

        $message->load('commissionRequest');

        return Inertia::render('Admin/Messages/Show', [
            'message' => $message,
        ]);
    }

    public function update(UpdateMessageRequest $request, ContactMessage $message): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $message->update($validated);

        return to_route('admin.messages.index')->with('success', 'Message status updated successfully.');
    }

    public function destroy(ContactMessage $message): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('admin');

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
