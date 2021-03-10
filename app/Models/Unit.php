<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    final public function foods(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Food::class, 'units_foods', 'unit_id', 'food_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }
}
