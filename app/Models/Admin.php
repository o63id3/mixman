<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Admin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
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
     * Seller is not an admin.
     */
    public function isAdmin(): bool
    {
        return true;
    }

    /**
     * The "booting" method of the model.
     *
     * This method is used to register any event listeners for the model,
     * such as handling actions before a model is created or updated.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $setTotalPrices = function ($model) {
            $model->admin = true;
        };

        self::creating($setTotalPrices);
        self::updating($setTotalPrices);
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
            'active' => 'boolean',
        ];
    }
}
