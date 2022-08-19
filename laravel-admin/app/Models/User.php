<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
    protected $hidden = ['password'];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasAccess($access)
    {
        // pluck - The pluck method retrieves all of the values for a given key:
        // contains - The contains method determines whether the collection contains a given item.
        return $this->role->permissions->pluck('name')->contains($access); // Gets all by the name and checks the access.
    }
}
