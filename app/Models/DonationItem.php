<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'donations_items';

    final public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'id');
    }

    final public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id', 'id');
    }

    final public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
