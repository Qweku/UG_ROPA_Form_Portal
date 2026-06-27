<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'surname',
        'email',
        'password',
        'personnel_id',
        'is_verified',
        'role',
    ];

    protected $attributes = [
        'role' => 'user', // Default role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return trim($this->firstname.' '.$this->surname);
    }

    // Mutator for backwards compatibility if needed
    public function setNameAttribute($value)
    {
        $parts = explode(' ', $value, 2);
        $this->attributes['firstname'] = $parts[0];
        $this->attributes['surname'] = $parts[1] ?? '';
    }

    public function ropaForms()
    {
        return $this->hasMany(RopaForm::class);
    }
}
