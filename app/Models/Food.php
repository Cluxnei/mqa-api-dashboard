<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
