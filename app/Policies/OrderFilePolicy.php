<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OrderFile;
use App\Models\User;

final class OrderFilePolicy
{
    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, OrderFile $file): bool
    {
        return $user->isAhmed();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderFile $file): bool
    {
        $file->load('order');

        return $user->can('update', $file->order);
    }
}
