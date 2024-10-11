<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class CardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAhmed();
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
