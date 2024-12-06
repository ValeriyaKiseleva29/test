<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
     public function index()
     {

         if (auth()->check()) {
             return redirect()->route('dashboard.index');
         }
         return view('company.index');
     }

    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        auth()->login($company);

        session(['company_id' => $company->id]);

        return redirect()->route('dashboard.index');

    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        $company->projects()->each(function ($project) {
            $project->tasks()->delete();
            $project->delete();
        });

        $company->users()->delete();
        $company->delete();

        auth()->logout();
        session()->flush();

        return redirect('/');
    }



}
