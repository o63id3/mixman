<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

final class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'region_id',
        'contact_info',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if the user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * Get the seller region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the seller region.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'registered_by');
    }

    /**
     * Activate the user.
     */
    public function activate(): void
    {
        $this->active = true;
        $this->save();

    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): void
    {
        $this->active = false;
        $this->save();

        DB::table('sessions')
            ->where('user_id', $this->id)
            ->delete();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'admin' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
