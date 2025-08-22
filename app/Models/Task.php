<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'status', 'due_date'];

    protected $casts = [
        'status' => TaskStatus::class,
        'due_date' => 'datetime',
    ];

    protected $attributes = [
        'status' => TaskStatus::Pending,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
