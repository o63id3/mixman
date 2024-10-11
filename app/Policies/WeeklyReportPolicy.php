<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\WeeklyReport;

final class WeeklyReportPolicy
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
    public function view(User $user, WeeklyReport $weeklyReport): bool
    {
        return $user->isAhmed()
            || $user->networks()->where('networks.id', $weeklyReport->network_id)->exists();
    }
}
