<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'completed',
        'due_date',
        'user_id',
        'task_list_id',
    ];

    // relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // relationship with the TaskList model
    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }

}
