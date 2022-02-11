<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use App\Models\OauthAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'cpf', 'mobile', 'terms', 'optin', 'birth_date', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'is_super_admin', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];

    
    public function accessTokens()
    {
        return $this->hasMany('App\Models\OauthAccessToken')->latest('created_at')->first();
    }

    public function profiles()
    {
        return $this->belongsToMany(\App\Models\UserProfile::Class);
    }

    public function project()
    {
        return $this->belongsToMany(\App\Models\Project::Class);
    }

    public function getProfiles()
    {
        $profiles = [];
        if($this->isAdmin()) array_push($profiles, 'SYSTEM_ADMIN');
        foreach ($this->profiles as $profile)  array_push($profiles, $profile->code);
        return implode(', ', $profiles);
    }

    public function setProfiles($profiles)
    {
        $this->profiles()->sync($profiles);
    }

    public function fullname()
    {
        return $this->name. ' '.$this->lastname;
    }

    public function isAdmin()
    {
        return $this->is_super_admin;
    }

    /**
     * Get the cpf formated.
     *
     * @return string
     */
    public function cpfFormated()
    {
        $cnpj_cpf = preg_replace("/\D/", '', $this->cnpjcpf);
  
        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        } 
        
        return $cnpj_cpf;
    }

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::Class);
    }

    /**
     * Get the all company data.
     */
    public function hasProfile(string $userProfile)
    {
        return $this->isAdmin() || $this->in_array_field('ADMIN', 'code', $this->profiles) || $this->in_array_field($userProfile, 'code', $this->profiles);
    }

    /**
     * Get the all company data.
     */
    public function hasAccess(String $url)
    {

        return true;
    }

    private function in_array_field($needle, $needle_field, $haystack, $strict = false) {
        if ($strict) {
            foreach ($haystack as $item)
                if (isset($item->$needle_field) && $item->$needle_field === $needle)
                    return true;
        }
        else {
            foreach ($haystack as $item)
                if (isset($item->$needle_field) && $item->$needle_field == $needle)
                    return true;
        }
        return false;
    }
}
