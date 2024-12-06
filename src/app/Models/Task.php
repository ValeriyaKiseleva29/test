<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'priority',
        'attachment',
        'project_id',
        'leader_id',
        'company_id',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
