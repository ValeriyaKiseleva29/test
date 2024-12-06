<?php

namespace App\Http\Controllers\Company\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Services\FileService;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{

    public function list()
    {

        $projects = Project::where('company_id', auth()->id())
            ->with(['users', 'tasks.users'])
            ->get();

        return view('dashboard.projects.list', compact('projects'));
    }

    public function create()
    {

        $users = User::where('company_id', auth()->id())->get();

        return view('dashboard.projects.project-create', compact('users'));

    }

    public function store(StoreProjectRequest $request, FileService $fileService)
    {

        $data = $request->except('_token');

        $companyId = auth()->id();
        $data['company_id'] = $companyId;


        if (!$companyId) {
            return redirect()->back()->with('error', 'Ошибка: невозможно определить текущую компанию.');
        }

        $project = Project::create($data);

        if ($request->has('participants')) {
            $project->users()->attach($request->participants);

        }

        if ($request->hasFile('file')) {
           $fileService->storeFile($request, $project);
        }

        return redirect()->route('dashboard.projects.create')->with('success', 'Проект успешно добавлен!');
    }


    public function edit($id)
    {
        $project = Project::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$project) {
            return redirect()->route('dashboard.projects.list')->with('error', 'Проект не найден.');
        }

        $users = User::where('company_id', auth()->id())->get();
        return view('dashboard.projects.project-edit', compact('project', 'users'));
    }


    public function update(StoreProjectRequest $request, $id, FileService $fileService)
    {
        $project = Project::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$project) {
            return redirect()->route('dashboard.projects.list')->with('error', 'Проект не найден.');
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'leader_id' => $request->leader,
        ]);

        if ($request->has('participants')) {
            $project->users()->sync($request->participants);
        }

        if ($request->hasFile('file')) {

            if ($project->files()->exists()) {
                $existingFile = $project->files()->first();
                Storage::disk('public')->delete($existingFile->path);
                $existingFile->delete();
            }

            $fileService->storeFile($request, $project);
        }

        return redirect()->route('dashboard.projects.list')->with('success', 'Проект успешно обновлен!');
    }


    public function destroy($id)
    {
        $project = Project::where('id', $id)->where('company_id', auth()->id())->first();

        if (!$project) {
            return redirect()->route('dashboard.projects.list')->with('error', 'Проект не найден.');
        }

        $project->delete();

        return redirect()->route('dashboard.projects.list')->with('success', 'Проект успешно удален.');
    }

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return Project::with(['files', 'participants', 'leader'])->get()->map(function ($project) {
            // Добавляем URL для скачивания файлов
            $project->files->map(function ($file) {
                $file->url = route('projects.files.download', $file->id);
                return $file;
            });
            return $project;
        });
    }


    public function myProjects()
    {
        return Project::with(['files', 'participants', 'leader'])
            ->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get()
            ->map(function ($project) {
                $project->files->map(function ($file) {
                    $file->url = route('projects.files.download', $file->id);
                    return $file;
                });
                return $project;
            });
    }
//    public function show($id)
//    {
//        $project = Project::with(['users', 'tasks', 'files', 'leader'])
//            ->where('company_id', auth()->id())
//            ->find($id);
//
//        if (!$project) {
//            if (request()->wantsJson()) {
//                return response()->json(['message' => 'Проект не найден'], 404);
//            }
//            abort(404, 'Проект не найден.');
//        }
//
//        if (request()->wantsJson()) {
//            return response()->json($project);
//        }
//
//        // Если запрос HTML (Blade)
//        return view('dashboard.projects.show', compact('project'));
//    }
//
    public function apiShow($id)
    {
        $project = Project::with(['users', 'tasks', 'files', 'leader', 'participants'])
            ->where('company_id', auth()->user()->company_id)
            ->find($id);
        if (!$project) {
            return response()->json(['message' => 'Проект не найден'], 404);
        }

        $project->files->map(function ($file) {
            $file->url = route('projects.files.download', $file->id);
            return $file;
        });

        return response()->json($project);
    }

    public function bladeShow($id)
    {
        $project = Project::with(['users', 'tasks', 'files', 'leader'])
            ->where('company_id', auth()->id())
            ->find($id);

        if (!$project) {
            abort(404, 'Проект не найден.');
        }

        return view('dashboard.projects.show', compact('project'));
    }

    public function destroyFile($fileId)
    {
        $file = File::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'Файл успешно удален!');
    }

}
