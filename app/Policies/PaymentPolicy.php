<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

final class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->isAhmed()
            || $payment->recipient_id === $user->id
            || $payment->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAhmed() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        if (
            $payment->recipient_id !== $user->id
        ) {
            return false;
        }

        return $payment->created_at->isToday();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->isAhmed();
    }
}
