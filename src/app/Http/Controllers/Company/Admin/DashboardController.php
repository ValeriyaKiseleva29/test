<?php

namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = session('company_id');
        $company = Company::find($companyId);

        if (!$company) {
            return redirect()->route('company.index')->with('error', 'Компания не найдена.');
        }

        $companyName = $company->name;

        $projectCount = $company->projects()->count();
        $userCount = $company->users()->count();
        $taskCount = $company->projects()->withCount('tasks')->get()->sum('tasks_count');

        return view('dashboard.index', compact('companyName', 'projectCount', 'userCount', 'taskCount'));
    }

}
