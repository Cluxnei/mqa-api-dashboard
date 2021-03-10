<?php

namespace App\Models;

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

    final public function isAdmin(): bool {
        return (int)$this->is_admin === 1;
    }

    final public function scopeAdmin(Builder $builder): Builder {
        return $builder->where('is_admin', '=', 1);
    }


}
