<?php

namespace App\Http\Controllers\Company\Lists;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
    public function index()
    {
        $companyId = Auth::id();
        $tasks = Task::whereHas('project', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->with('project', 'users',)
            ->get();

        return view('dashboard.tasks.list', compact('tasks'));
    }

}
