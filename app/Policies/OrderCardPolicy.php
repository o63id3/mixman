<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OrderCard;
use App\Models\User;

final class OrderCardPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderCard $orderCard): bool
    {
        return $user->isAhmed();
    }
}
