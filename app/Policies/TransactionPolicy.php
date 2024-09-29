<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }
}
