<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Order;
use App\Models\User;

final class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager() || $user->role !== RoleEnum::Partner->value;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->isAhmed()
            || $order->orderer_id === $user->id
            || $order->manager_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        if (
            $order->manager_id !== $user->id
        ) {
            return false;
        }

        return $order->pending() || $order->completed_at->isToday();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can create items for the model.
     */
    public function createItems(User $user, Order $order): bool
    {
        return $this->update($user, $order);
    }
}
