<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class DeactivateUserAction
{
    public function handle(User $user)
    {
        $user->active = false;
        $user->save();

        $this->logoutAllSessions($user);
    }

    private function logoutAllSessions(User $user)
    {
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();
    }
}
