<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatureConsentRequest;
use App\Support\MatureConsent;
use Illuminate\Http\RedirectResponse;

/**
 * Records the visitor's consent to see mature artwork in the session, then
 * sends them back so the page reloads the now-revealed props (ADR-0004).
 */
class MatureConsentController extends Controller
{
    public function store(MatureConsentRequest $request, MatureConsent $consent): RedirectResponse
    {
        if ($request->boolean('all')) {
            $consent->revealAll();
        } elseif ($request->filled('artwork_id')) {
            $consent->reveal($request->integer('artwork_id'));
        }

        return back(303);
    }
}
