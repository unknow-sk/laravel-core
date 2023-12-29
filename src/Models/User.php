<?php

namespace UnknowSk\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function __call($method, $arguments)
    {
        if (str_starts_with($method, 'wants')) {
            // @todo
            if (str_ends_with($method, 'Breadcrumbs')) {
                return true;
            } elseif (str_ends_with($method, 'RTL')) {
                return false;
            }
        }

        return parent::__call($method, $arguments);
    }

    /**
     * Determines if the User is a Super admin
     *
     * @return null
     */
    public function isSuperAdmin()
    {
        return true; // @todo $this->hasRole('super-admin');
    }
}
