<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

final class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $order->seller_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        if ($order->completed()) {
            return false;
        }

        return $user->isAdmin() || $user->id === $order->seller_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        if ($order->completed()) {
            return false;
        }

        return $user->isAdmin() || $user->id === $order->seller_id;
    }
}
