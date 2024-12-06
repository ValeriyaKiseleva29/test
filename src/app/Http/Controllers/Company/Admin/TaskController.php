<?php

namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Services\FileService;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class TaskController extends Controller
{
    public function list()
    {
        $companyId = Auth::id();
        $tasks = Task::whereHas('project', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->with('project', 'users')
            ->get();

        return view('dashboard.tasks.list', compact('tasks'));
    }
    public function create()
    {
        $projects = Project::where('company_id', Auth::id())->get();
        $users = User::where('company_id', Auth::id())->get();

        return view('dashboard.tasks.task-create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $companyId = Auth::id();
        $project = Project::where('id', $request->project_id)
            ->where('company_id', $companyId)
            ->first();

        if (!$project) {
            return redirect()->back()->with('error', 'Проект не найден или не принадлежит вашей компании.');
        }

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'attachment' => $request->attachment,
            'project_id' => $request->project_id,
            'leader_id' => $request->leader_id,
            'company_id' => $companyId,
        ]);

        if ($request->has('participants')) {
            $task->users()->sync($request->participants);
        }

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('tasks', 'public');

            $task->files()->create([
                'name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'size' => $uploadedFile->getSize(),
                'type' => $uploadedFile->getClientMimeType(),
            ]);
        }

        return redirect()->route('dashboard.tasks.create')->with('success', 'Задача успешно добавлена!');
    }

    public function edit($id)
    {
        $task = Task::whereHas('project', function ($query) {
            $query->where('company_id', Auth::id());
        })->findOrFail($id);

        $projects = Project::where('company_id', Auth::id())->get();
        $users = User::where('company_id', Auth::id())->get();

        return view('dashboard.tasks.task-edit', compact('task', 'projects', 'users'));
    }

    public function update(StoreTaskRequest $request, $id, FileService $fileService)
    {
        $task = Task::whereHas('project', function ($query) {
            $query->where('company_id', Auth::id());
        })->findOrFail($id);

        $task->update($request->only(['name', 'description', 'start_date', 'end_date', 'priority', 'project_id', 'leader_id']));

        if ($request->has('participants')) {
            $task->users()->sync($request->participants);
        }

        if ($request->hasFile('file')) {
            if ($task->files()->exists()) {
                $existingFile = $task->files()->first();
                Storage::disk('public')->delete($existingFile->path);
                $existingFile->delete();
            }

            $fileService->storeFile($request, $task);
        }

        return redirect()->route('dashboard.tasks.list')->with('success', 'Задача успешно обновлена!');
    }

    public function destroy($id)
    {
        $task = Task::whereHas('project', function ($query) {
            $query->where('company_id', Auth::id());
        })->findOrFail($id);

        $task->delete();

        return redirect()->route('dashboard.tasks.list')->with('success', 'Задача успешно удалена!');
    }
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return Task::with(['users', 'files', 'project', 'leader'])->get()->map(function ($task) {
            $task->files->map(function ($file) {
                $file->url = route('tasks.files.download', $file->id);
                return $file;
            });
            return $task;
        });
    }

    public function myTasks()
    {
        return Task::with(['users', 'files', 'project', 'leader'])
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->get()
            ->map(function ($task) {
                $task->files->map(function ($file) {
                    $file->url = route('tasks.files.download', $file->id);
                    return $file;
                });
                return $task;
            });
    }
    public function show($id)
    {
        $task = Task::with(['project', 'users', 'files', 'leader'])
            ->whereHas('project', function ($query) {
                $query->where('company_id', Auth::id());
            })
            ->findOrFail($id);

        return view('dashboard.tasks.show', compact('task'));
    }
    public function apiShow($id)
    {
        $task = Task::with(['project', 'users', 'files', 'leader'])
            ->whereHas('project', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->findOrFail($id);

        if (!$task) {
            return response()->json(['message' => 'Задача не найдена'], 404);
        }

        $task->files->map(function ($file) {
            $file->url = route('projects.files.download', $file->id);
            return $file;
        });

        return response()->json($task);
    }

    public function destroyFile($fileId)
    {
        $file = File::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();
        return redirect()->back();
    }


}
