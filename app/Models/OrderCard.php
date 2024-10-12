<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class OrderCard extends Pivot
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_id',
        'number_of_packages',
        'number_of_cards_per_package',
    ];

    /**
     * Get the order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the card.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
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

        self::saving(function ($model) {
            $card = Card::find($model->card_id, ['price_for_consumer']);

            $model->quantity = $model->number_of_packages * $model->number_of_cards_per_package;
            $model->total_price_for_consumer = $card->price_for_consumer * $model->quantity;
            $model->total_price_for_seller = ($card->price_for_consumer * (1 - $model->order->user->percentage)) * $model->quantity;
        });
    }
}
