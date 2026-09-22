<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
            'equestrian_center_id' => [
                'required',
                Rule::exists('equestrian_centers', 'id')->where('affiliation_status', 'active'),
            ],
            'grade_id' => ['nullable', 'exists:grades,id'],
            'data_processing_consent' => ['accepted'],
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['first_name'].' '.$input['last_name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            $riderRole = Role::query()->firstOrCreate(
                ['code' => 'rider'],
                ['name' => 'Călăreț'],
            );

            $user->roles()->attach($riderRole);

            $riderProfile = $user->riderProfile()->create([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'birth_date' => $input['birth_date'],
                'phone' => $input['phone'],
                'self_reported_grade_id' => $input['grade_id'] ?? null,
                'data_processing_consent_at' => now(),
            ]);

            $riderProfile->centerMemberships()->create([
                'equestrian_center_id' => $input['equestrian_center_id'],
                'status' => 'pending',
                'is_initial_validation' => true,
                'requested_by' => $user->id,
            ]);

            return $user;
        });
    }
}
