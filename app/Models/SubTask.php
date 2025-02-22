<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    public function task()
    {
        return $this->hasOne(Task::class);
    }
}
