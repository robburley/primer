<?php

namespace App\Models\Users;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\AccessLog;
use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, EntrustUserTrait, CommonScopes, ActiveGetAndSet;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'active',
        'deactivated_at',
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

    public function setPasswordAttribute($value)
    {
        if ($value) {
            return $this->attributes['password'] = bcrypt($value);
        }
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class)
                    ->whereNull('deactivated_at')
                    ->withPivot([
                        'supervisor',
                        'create_new_lead',
                        'update_lead',
                        'confirm_lead',
                    ]);
    }

    public function campaignsSupervisor()
    {
        return $this->belongsToMany(Campaign::class)
                    ->whereNull('deactivated_at')
                    ->wherePivot('supervisor', 1)
                    ->withPivot([
                        'supervisor',
                        'create_new_lead',
                        'update_lead',
                        'confirm_lead',
                    ]);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    public function getFullNameAttribute()
    {
        return collect([$this->first_name, $this->last_name])->implode(' ');
    }

    public function getRoleNamesAttribute()
    {
        return $this->roles
            ? collect($this->roles->pluck('name'))->implode(', ')
            : null;
    }

    public function isAdministrator()
    {
        $administrator = Role::where('name', 'Administrator')->first();

        return $this->roles->contains($administrator);
    }

    public function leadAccessCount($lead)
    {
        return $this->accessLogs()
                    ->where('lead_id', $lead->id)
                    ->count();
    }

    public function hasCampaigns()
    {
        return $this->campaigns->count() > 0;
    }

    public function isCampaignSupervisor()
    {
        return $this->campaignsSupervisor->count() > 0;
    }

    public function ScopeFindTenantUser($query, $user)
    {
        $query->where([
            'id'        => $user,
            'tenant_id' => auth()->user()->tenant_id,
        ]);
    }
}
