<?php

namespace App\Policies;

use App\Models\CenterRiderMembership;
use App\Models\GuardianRelationship;
use App\Models\ProfessionalProfile;
use App\Models\ProfessionalRiderAccess;
use App\Models\RiderProfile;
use App\Models\User;

class RiderProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('federation') || $user->hasRole('monitor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RiderProfile $riderProfile): bool
    {
        if ($user->hasRole('federation') || $riderProfile->user_id === $user->id) {
            return true;
        }

        if (GuardianRelationship::query()
            ->where('guardian_user_id', $user->id)
            ->where('rider_profile_id', $riderProfile->id)
            ->whereNotNull('verified_at')
            ->exists()) {
            return true;
        }

        $professional = ProfessionalProfile::query()
            ->where('user_id', $user->id)
            ->where('federation_status', 'active')
            ->first();

        if (! $professional) {
            return false;
        }

        $centerIds = $professional->affiliations()
            ->where('status', 'active')
            ->pluck('equestrian_center_id');

        $sharedCenter = CenterRiderMembership::query()
            ->where('rider_profile_id', $riderProfile->id)
            ->where('status', 'active')
            ->whereIn('equestrian_center_id', $centerIds)
            ->exists();

        $independentAccess = ProfessionalRiderAccess::query()
            ->where('professional_profile_id', $professional->id)
            ->where('rider_profile_id', $riderProfile->id)
            ->whereNull('revoked_at')
            ->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();

        return $sharedCenter || $independentAccess;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RiderProfile $riderProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RiderProfile $riderProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RiderProfile $riderProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RiderProfile $riderProfile): bool
    {
        return false;
    }
}
