<?php

namespace App\Http\Controllers\Company\Lists;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectListController extends Controller
{
    public function index()
    {
        $projects = Project::where('company_id', auth()->id())->get();

        return view('dashboard.projects.list', compact('projects'));
    }

    public function list()
    {

        $projects = Project::where('company_id', auth()->id())
            ->with(['users', 'tasks.users',])
            ->get();

        return view('dashboard.projects.list', compact('projects'));
    }

}

