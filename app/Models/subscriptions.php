<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriptions extends Model
{
    use HasFactory;


    /**
     * Get the plan that owns the subscriptions
     *  Aboneliklerin sahibi olan planı edinin
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }




    /**
     * Get all of the payments for the subscriptions
     *  Aboneliklere ilişkin tüm ödemeleri alın
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }
}
