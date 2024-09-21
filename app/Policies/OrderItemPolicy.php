<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OrderItem;
use App\Models\User;

final class OrderItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderItem $orderItem): bool
    {
        return $user->isAdmin();
    }
}
