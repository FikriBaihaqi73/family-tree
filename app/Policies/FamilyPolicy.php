<?php

namespace App\Policies;

use App\Models\Family;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the family.
     */
    public function view(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }

    /**
     * Determine whether the user can update the family.
     */
    public function update(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }

    /**
     * Determine whether the user can delete the family.
     */
    public function delete(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }
}
