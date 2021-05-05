<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Food
 * @package App\Models
 * @method static Builder approved(bool $approved = true)
 */
class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'approved',
        'approved_by',
        'requested_by',
    ];

    final public function units(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Unit::class, 'units_foods', 'food_id', 'unit_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    final public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    final public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    final public function scopeApproved(Builder $builder, bool $approved = true): Builder
    {
        return $builder->where('approved', '=', $approved ? 1 : 0);
    }
}
