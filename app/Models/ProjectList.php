<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectList extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'name',
        'description',
    ];

    /**
     * Get the user who owns the list.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members belonging to this list.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user', 'list_id', 'user_id')->withTimestamps();
    }

    /**
     * Get tasks for this list (for Programmer 3's Task Management module).
     */
    public function tasks(): HasMany
    {
        // Safe relation hook for Task model when created by Programmer 3
        return $this->hasMany(Task::class, 'list_id');
    }
}
