<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $table = 'lists';

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'list_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id');
    }

    public function getProgressPercentageAttribute()
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        
        $completed = $this->tasks()->where('is_completed', true)->count();
        return round(($completed / $total) * 100, 2);
    }
}
