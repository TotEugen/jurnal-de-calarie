<?php

namespace App\Http\Controllers;

use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManagedRiderContactController extends Controller
{
    public function edit(Request $request, RiderProfile $riderProfile): View
    {
        $this->ensureUserCanManage($request, $riderProfile);

        return view('pages.riders.profile', compact('riderProfile'));
    }

    public function update(Request $request, RiderProfile $riderProfile): RedirectResponse
    {
        $this->ensureUserCanManage($request, $riderProfile);

        $isMinor = Carbon::parse($request->input('birth_date'))->age < 18;

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => [Rule::requiredIf(! $isMinor), 'nullable', 'string', 'max:30'],
            'contact_email' => [
                Rule::requiredIf(! $isMinor),
                'nullable',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
                Rule::unique(RiderProfile::class, 'contact_email')->ignore($riderProfile->id),
            ],
            'had_physical_journal' => ['nullable', 'boolean'],
        ]);

        $validated['had_physical_journal'] = $request->boolean('had_physical_journal');

        $riderProfile->update($validated);

        $destination = $riderProfile->user_id === $request->user()->id
            ? 'profile.edit'
            : 'guardian.dashboard';

        return to_route($destination)->with('status', 'Profilul călărețului a fost actualizat.');
    }

    private function ensureUserCanManage(Request $request, RiderProfile $riderProfile): void
    {
        abort_unless(
            $riderProfile->user_id === $request->user()?->id
                || $request->user()?->guardianRelationships()
                    ->where('rider_profile_id', $riderProfile->id)
                    ->exists(),
            403,
        );
    }
}
