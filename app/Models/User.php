<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models
 * @method static Builder admin()
 * @property int id
 * @property int is_admin
 * @property int active
 * @property string name
 * @property string cpf
 * @property string phone
 * @property string email
 * @property string password
 * @property string remember_token
 * @property string|Carbon email_verified_at
 * @property string|Carbon phone_verified_at
 * @property string|Carbon created_at
 * @property string|Carbon updated_at
 * @property string|Carbon deleted_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_admin',
        'active',
        'name',
        'cpf',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    final public function companies(bool $withTrashed = false): BelongsToMany
    {
        $builder = $this->belongsToMany(Company::class, 'users_companies', 'user_id', 'company_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
        return !$withTrashed ? $builder->wherePivotNull('deleted_at') : $builder;
    }

    final public function isAdmin(): bool
    {
        return (int)$this->is_admin === 1;
    }

    final public function isActive(): bool
    {
        return (int)$this->active === 1;
    }

    final public function scopeAdmin(Builder $builder): Builder
    {
        return $builder->where('is_admin', '=', 1);
    }

    final public function scopeActive(Builder $builder, bool $active = true): Builder
    {
        return $builder->where('active', '=', $active ? 1 : 0);
    }


}
