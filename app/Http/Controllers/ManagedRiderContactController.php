<?php

namespace App\Http\Controllers;

use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManagedRiderContactController extends Controller
{
    public function edit(Request $request, RiderProfile $riderProfile): View
    {
        $this->ensureGuardianCanManage($request, $riderProfile);

        return view('pages.guardians.contact', compact('riderProfile'));
    }

    public function update(Request $request, RiderProfile $riderProfile): RedirectResponse
    {
        $this->ensureGuardianCanManage($request, $riderProfile);

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'contact_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
                Rule::unique(RiderProfile::class, 'contact_email')->ignore($riderProfile->id),
            ],
        ]);

        $riderProfile->update($validated);

        return to_route('guardian.dashboard')->with('status', 'Datele de contact au fost actualizate.');
    }

    private function ensureGuardianCanManage(Request $request, RiderProfile $riderProfile): void
    {
        abort_unless(
            $request->user()?->guardianRelationships()
                ->where('rider_profile_id', $riderProfile->id)
                ->exists(),
            403,
        );
    }
}
