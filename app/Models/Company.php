<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'active',
        'name',
        'cnpj',
        'phone',
        'email',
        'zipcode',
        'street',
        'neighborhood',
        'address_number',
        'city',
        'state',
        'country',
        'latitude',
        'longitude'
    ];

    /**
     * @param bool $withTrashed
     * @return BelongsToMany
     */
    final public function users(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(User::class, 'users_companies', 'company_id', 'user_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    /**
     * @param bool $withTrashed
     * @return BelongsToMany
     */
    final public function foods(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Food::class, 'companies_foods', 'company_id', 'food_id')
            ->withPivot('unit_id', 'requested_by', 'type', 'amount', 'deleted_at')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    /**
     * @param bool $withTrashed
     * @return BelongsToMany
     */
    final public function interestFoods(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Food::class, 'companies_foods', 'company_id', 'food_id')
            ->withPivot('unit_id', 'requested_by', 'type', 'amount', 'deleted_at')
            ->wherePivot('type', '=', 'interest')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    /**
     * @param bool $withTrashed
     * @return BelongsToMany
     */
    final public function availableFoods(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Food::class, 'companies_foods', 'company_id', 'food_id')
            ->withPivot('unit_id', 'requested_by', 'type', 'amount', 'deleted_at')
            ->wherePivot('type', '=', 'available')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    /**
     * @return bool
     */
    final public function isActive(): bool
    {
        return (int)$this->active === 1;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|null $availableFoods
     * @return Collection
     */
    final public function compatibleDonations(?\Illuminate\Database\Eloquent\Collection $availableFoods = null): Collection
    {
        if (null === $availableFoods) {
            $availableFoods = $this->availableFoods()->get();
        }
        return DB::table('companies_foods')->where('type', '=', 'interest')
            ->whereIn('food_id', $availableFoods->pluck('id'))
            ->get();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|null $interestFoods
     * @return Collection
     */
    final public function compatibleReceptions(?\Illuminate\Database\Eloquent\Collection $interestFoods = null): Collection
    {
        if (null === $interestFoods) {
            $interestFoods = $this->interestFoods()->get();
        }
        return DB::table('companies_foods')->where('type', '=', 'available')
            ->whereIn('food_id', $interestFoods->pluck('id'))
            ->get();
    }

}
