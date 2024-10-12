<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

final class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->isAhmed()
            || $expense->user_id === $user->id
            || $expense->network_id === $user->network_id;
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
    public function update(User $user, Expense $expense): bool
    {
        if (
            $expense->user_id !== $user->id
        ) {
            return false;
        }

        return $expense->created_at->isToday();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->isAhmed();
    }
}
