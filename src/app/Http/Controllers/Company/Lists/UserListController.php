<?php

namespace App\Http\Controllers\Company\Lists;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserListController extends Controller
{
    public function index()
    {

        $users = User::where('company_id', Auth::id())->get();

        return view('dashboard.users.list', compact('users'));
    }

    public function list()
    {
        $companyId = auth()->id();
        $users = User::where('company_id', $companyId)
            ->with(['projects', 'tasks'])
            ->get();

        return view('dashboard.users.list', compact('users'));
    }

}
