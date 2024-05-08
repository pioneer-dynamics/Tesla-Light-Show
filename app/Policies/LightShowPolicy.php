<?php

namespace App\Policies;

use App\Models\LightShow;
use App\Models\User;

class LightShowPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, LightShow $lightShow): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LightShow $lightShow): bool
    {
        if($lightShow->user->is($user)) 
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LightShow $lightShow): bool
    {
        if($user->id === 1)
            return true;

        if($lightShow->user->is($user))
            return true;
        
        return false;
    }
}
