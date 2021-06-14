<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Donation
 * @package App\Models
 * @method static Builder donation()
 * @method static Builder reception()
 * @method static self|null create(array $data)
 */
class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    final public function items(): HasMany
    {
        return $this->hasMany(DonationItem::class, 'donation_id', 'id');
    }

    final public function scopeDonation(Builder $builder): Builder
    {
        return $builder->where('type', '=', 'donation');
    }

    final public function scopeReception(Builder $builder): Builder
    {
        return $builder->where('type', '=', 'reception');
    }

    final public function fromCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'from_company_id', 'id');
    }

    final public function toCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'to_company_id', 'id');
    }
}
