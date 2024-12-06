<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'leader_id',
        'company_id',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');

    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
