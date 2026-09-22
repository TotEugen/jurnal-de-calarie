<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\RiderProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $isMinor = isset($input['birth_date'])
            && strtotime($input['birth_date']) !== false
            && Carbon::parse($input['birth_date'])->age < 18;

        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => [Rule::requiredIf(! $isMinor), 'nullable', 'string', 'max:30'],
            'email' => [
                Rule::requiredIf(! $isMinor),
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
                Rule::unique('rider_profiles', 'contact_email'),
            ],
            'password' => $this->passwordRules(),
            'had_physical_journal' => ['nullable', 'boolean'],
            'guardian_first_name' => [Rule::requiredIf($isMinor), 'nullable', 'string', 'max:255'],
            'guardian_last_name' => [Rule::requiredIf($isMinor), 'nullable', 'string', 'max:255'],
            'guardian_age' => [Rule::requiredIf($isMinor), 'nullable', 'integer', 'between:18,120'],
            'guardian_phone' => [Rule::requiredIf($isMinor), 'nullable', 'string', 'max:30'],
            'guardian_email' => [Rule::requiredIf($isMinor), 'nullable', 'email', 'max:255', 'different:email'],
            'guardian_relationship' => [Rule::requiredIf($isMinor), 'nullable', Rule::in(['parent', 'guardian'])],
            'data_processing_consent' => ['accepted'],
        ])->validate();

        $existingGuardian = $isMinor
            ? User::query()->where('email', $input['guardian_email'])->first()
            : null;

        if ($existingGuardian && ! Hash::check($input['password'], $existingGuardian->password)) {
            throw ValidationException::withMessages([
                'password' => 'Emailul părintelui este deja înregistrat. Introdu parola contului existent.',
            ]);
        }

        return DB::transaction(function () use ($existingGuardian, $input, $isMinor): User {
            $riderRole = Role::query()->firstOrCreate(
                ['code' => 'rider'],
                ['name' => 'Călăreț'],
            );

            if (! $isMinor) {
                $accountUser = User::create([
                    'name' => $input['first_name'].' '.$input['last_name'],
                    'email' => $input['email'],
                    'password' => $input['password'],
                ]);

                $accountUser->roles()->attach($riderRole);
            } else {
                $accountUser = $existingGuardian ?? User::create([
                    'name' => $input['guardian_first_name'].' '.$input['guardian_last_name'],
                    'email' => $input['guardian_email'],
                    'password' => $input['password'],
                ]);

                $guardianRole = Role::query()->firstOrCreate(
                    ['code' => 'guardian'],
                    ['name' => 'Părinte / Tutore'],
                );

                $accountUser->roles()->syncWithoutDetaching([$guardianRole->id]);
            }

            $riderProfile = RiderProfile::query()->create([
                'user_id' => $isMinor ? null : $accountUser->id,
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'birth_date' => $input['birth_date'],
                'phone' => $input['phone'] ?? null,
                'contact_email' => $input['email'] ?? null,
                'data_processing_consent_at' => now(),
                'had_physical_journal' => (bool) ($input['had_physical_journal'] ?? false),
            ]);

            if ($isMinor) {
                $riderProfile->guardians()->create([
                    'guardian_user_id' => $accountUser->id,
                    'first_name' => $input['guardian_first_name'],
                    'last_name' => $input['guardian_last_name'],
                    'age' => $input['guardian_age'],
                    'phone' => $input['guardian_phone'],
                    'email' => $input['guardian_email'],
                    'relationship' => $input['guardian_relationship'],
                    'is_primary' => true,
                ]);
            }

            return $accountUser;
        });
    }
}
