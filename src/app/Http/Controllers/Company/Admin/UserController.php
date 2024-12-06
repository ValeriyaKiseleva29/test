<?php

namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('dashboard.users.create');
    }

    public function list()
    {
        $users = User::where('company_id', auth()->id())
            ->with(['tasks', 'projects'])
            ->get();

        return view('dashboard.users.list', compact('users'));
    }


    public function store(StoreUserRequest $request)
    {

        $companyId = Auth::id();

        if (!$companyId) {
            return redirect()->back()->with('error', 'Ошибка: невозможно определить текущую компанию.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'company_id' => $companyId,
        ]);

        return redirect()->route('dashboard.users.create')->with('success', 'Пользователь успешно добавлен!');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$user) {
            return redirect()->route('dashboard.users.list')->with('error', 'Пользователь не найден.');
        }

        return view('dashboard.users.edit', compact('user'));
    }

    public function update(StoreUserRequest $request, $id)
    {
        $user = User::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$user) {
            return redirect()->route('dashboard.users.list')->with('error', 'Пользователь не найден.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('dashboard.users.list')->with('success', 'Пользователь успешно обновлен.');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$user) {
            return redirect()->route('dashboard.users.list')->with('error', 'Пользователь не найден.');
        }

        $user->delete();

        return redirect()->route('dashboard.users.list')->with('success', 'Пользователь успешно удален.');
    }

    public function getAllWorkers()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $workers = User::where('company_id', auth()->user()->company_id)
            ->select('id', 'name', 'email', 'role')
            ->get();

        return response()->json($workers);
    }
    public function show($id)
    {
        $user = User::with(['projects', 'tasks'])->findOrFail($id);

        return view('dashboard.users.show', compact('user'));
    }

}
