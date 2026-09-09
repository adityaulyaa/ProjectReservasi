<?php

namespace App\Models;

use App\Models\ProjectList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Lists created and owned by this user.
     */
    public function ownedLists(): HasMany
    {
        return $this->hasMany(ProjectList::class, 'owner_id');
    }

    /**
     * Lists where this user is a member/collaborator.
     */
    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(ProjectList::class, 'list_user', 'user_id', 'list_id')->withTimestamps();
    }
}

