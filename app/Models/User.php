<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ownedHinarios()
    {
        if ($this->hasRole('superadmin')) {
            return Hinario::orderByRaw("JSON_EXTRACT(preloaded_json, '$.name')");
        } else {
            return $this->owned_hinarios();
        }
    }

    public function owned_hinarios()
    {
        return $this->belongsToMany(Hinario::class, 'hinario_owners')
            ->orderByRaw("JSON_EXTRACT(preloaded_json, '$.name')");
    }



}
