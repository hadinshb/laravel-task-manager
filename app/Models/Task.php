<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;

class Task extends Model
{
     protected $casts = [
        'status' => TaskStatus::class,
    ];
}
