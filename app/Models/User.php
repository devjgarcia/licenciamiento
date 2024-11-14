<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Role;
use App\Enums\EstadoUsuarios;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class User extends Authenticatable implements Auditable
{
    use HasFactory, Notifiable, SoftDeletes, HasAudit;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_lice';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rif',
        'direction',
        'phone',
        'frkcanal',
        'email',
        'password',
        'codigo',
        'role_id',
        'status',
        'photo'
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
        'updated_at' => 'datetime:d-m-Y h:ia',
    ];

    protected $appends = [
        'class_estado'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSistemas()
    {
        if( isset($this->role->name_role) && 
            ($this->role->name_role==='Sistemas') 
        ){
            return true;
        }

        return false;
    }

    public function isAdmin()
    {
        if( isset($this->role->name_role) && 
            ($this->role->name_role==='Administrador' || $this->role->name_role==='Sistemas') 
        ){
            return true;
        }

        return false;
    }

    public function isSoporte()
    {
        if( isset($this->role->name_role) && ($this->role->name_role=='Soporte' || $this->role->name_role==='Administrador' || $this->role->name_role==='Sistemas') ){
            return true;
        }

        return false;
    }

    public function isCobranza()
    {
        if( isset($this->role->name_role) && ($this->role->name_role=='Cobranza' || $this->role->name_role==='Administrador' || $this->role->name_role==='Sistemas') ){
            return true;
        }

        return false;
    }

    public function getClassEstadoAttribute()
    {
        return EstadoUsuarios::getClassEstado( $this->status );
    }
}
