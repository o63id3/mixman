<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class OrderItem extends Model
{
    use HasFactory;

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

        $setTotalPrices = function ($model) {
            $model->quantity = $model->number_of_packages * $model->number_of_cards_per_package;

            $card = Card::find($model->card_id);

            $model->total_price_for_consumer = $card->price_for_consumer * $model->quantity;
            $model->total_price_for_seller = ($card->price_for_consumer * (1 - $model->order->orderer->percentage)) * $model->quantity;
        };

        self::creating($setTotalPrices);
        self::updating($setTotalPrices);
    }
}
