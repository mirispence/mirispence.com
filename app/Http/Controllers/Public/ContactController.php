<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function create()
    {
        Inertia::share('seo', SeoBuilder::forContact());
        
        return Inertia::render('Public/Contact');
    }

    public function store(ContactRequest $request)
    {
        $key = 'contact:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 3600);

        $data = $request->validated();
        $data['metadata'] = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        // Ensure type is set if not present (though validation handles it, good to be explicit)
        $data['type'] = $data['type'] ?? 'general';

        $message = ContactMessage::create($data);

        if ($message->type === 'commission') {
            $message->commissionRequest()->create([
                'project_description' => $request->input('message'),
                'budget_range' => $request->input('budget_range'),
                'desired_due_date' => $request->input('desired_due_date'),
                'status' => 'new',
            ]);
        }

        // TODO: Send email notification (Phase 5)

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
