<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Network;
use App\Models\User;

final class NetworkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isPartner();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Network $network): bool
    {
        if ($user->isAhmed()) {
            return true;
        }

        return $network->partners()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function createPartner(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function assignManager(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function deletePartner(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function deactivate(User $user): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function activate(User $user): bool
    {
        return $user->isAhmed();
    }
}
