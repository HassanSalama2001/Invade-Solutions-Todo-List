<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'time',
        'description',
        'category_id',
        'deleted',
        'due_date',  // Add due_date to fillable
        'status',  // Add status to fillable
    ];

    public function sub_tasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
