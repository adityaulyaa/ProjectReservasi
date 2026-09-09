<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'list_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'due_date',
        'is_completed',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isOverdue()
    {
        return !$this->is_completed && $this->due_date && $this->due_date->isPast();
    }
}
