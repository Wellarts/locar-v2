<?php

namespace App\Policies;

//use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Activitylog\Models\Activity;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Activity');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        return $user->hasPermissionTo('View Activity');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Activity');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity)
    {
        return $user->hasPermissionTo('Update Activity');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity)
    {
        return $user->hasPermissionTo('Delete Activity');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $activity)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $activity)
    {
        //
    }
}
