<?php

use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Engineer\DashboardController as EngineerDashboardController;
use App\Http\Controllers\Engineer\ProfileController as EngineerProfileController;
use App\Http\Controllers\Leader\DashboardController as LeaderDashboardController;
use App\Http\Controllers\Leader\EngineerController;
use App\Http\Controllers\Leader\ProjectController as LeaderProjectController;
use App\Http\Controllers\Leader\TaskBoardController;
use App\Http\Controllers\Leader\TaskController as LeaderTaskController;
use App\Http\Controllers\NotificationController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
    });
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('teams', TeamController::class);
        Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);
        Route::resource('systems', SystemController::class)->except(['show', 'edit', 'create']);
        Route::resource('projects', ProjectController::class);
        Route::resource('tasks', TaskController::class);
        Route::get('/projects/{project}/members', function (Project $project) {
            return User::where('team_id', $project->team_id)
                ->select('id', 'name')
                ->get();
        });
        
        Route::get('/users/roles', [UserRoleController::class, 'index'])->name('roles.index');
        Route::get('/users/{user}/roles/edit', [UserRoleController::class, 'edit'])->name('roles.edit');
        Route::put('/users/{user}/roles', [UserRoleController::class, 'update'])->name('roles.update');
    });

Route::prefix('engineer')
    ->name('engineer.')
    ->middleware(['auth', 'role:engineer'])
    ->group(function () {
        Route::get('/', [EngineerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/my-tasks', [EngineerDashboardController::class, 'myTasks'])->name('tasks');
        Route::post('/tasks/update-status', [EngineerDashboardController::class, 'updateTaskStatus'])
            ->name('tasks.updateStatus');
        Route::get('/tasks/{task}', [EngineerDashboardController::class, 'show'])
            ->name('tasks.show');

        Route::get('/tasks/{task}/comments', [EngineerDashboardController::class, 'comments'])
            ->name('tasks.comments');
        Route::post('/tasks/{task}/comment', [EngineerDashboardController::class, 'storeComment'])
            ->name('tasks.comment');
            

        Route::get('/my-projects', [EngineerDashboardController::class, 'myProjects'])
            ->name('projects');
        Route::get('/my-projects/{project}', [EngineerDashboardController::class, 'projectDetails'])
            ->name('projects.show');
            
        Route::get('/profile', [EngineerProfileController::class, 'index'])
            ->name('profile');
        Route::post('/profile/update', [EngineerProfileController::class, 'update'])
            ->name('profile.update');
        Route::post('/profile/password', [EngineerProfileController::class, 'updatePassword'])
            ->name('profile.password');
    });


Route::prefix('leader')
    ->name('leader.')
    ->middleware(['auth', 'role:leader'])
    ->group(function () {
        Route::get('/dashboard', [LeaderDashboardController::class, 'index'])->name('dashboard');
        Route::get('/task-board', [TaskBoardController::class, 'index'])->name('taskboard');
        Route::post('/tasks/update-status', [TaskBoardController::class, 'updateStatus'])
            ->name('tasks.updateStatus');
        Route::post('/leader/tasks/update', [TaskBoardController::class, 'updateTask'])
            ->name('tasks.update');
        Route::get('/leader/projects', [LeaderProjectController::class, 'index'])
            ->name('projects.index');
        Route::get('/leader/projects/{project}', [LeaderProjectController::class, 'show'])
            ->name('projects.show');
        Route::post('/leader/projects/{project}/tasks', [LeaderProjectController::class, 'storeTask'])
            ->name('projects.tasks.store');
        Route::get('/engineers/performance', [EngineerController::class, 'performance'])
            ->name('engineers.performance');
        Route::get('/leader/engineers/{engineer}', [EngineerController::class, 'show'])
            ->name('engineers.show');
    });











// Route::prefix('test/leader')->middleware(['auth'])->group(function () {
//     Route::get('/projects', [LeaderProjectController::class, 'index'])
//         ->name('leader.projects');

//     // Route::get('/projects/{project}', [LeaderProjectController::class, 'show'])
//     //     ->name('leader.projects.show');
//     Route::get('/tasks', [LeaderTaskController::class, 'index'])
//         ->name('leader.tasks');

//     // Route::post('/tasks/update-status', [LeaderTaskController::class, 'updateStatus'])
//     //     ->name('leader.tasks.updateStatus');

//     //     Route::post('/tasks/reassign', [LeaderTaskController::class, 'reassign'])
//     //         ->name('leader.tasks.reassign');
// });


// Route::prefix('leader')->middleware(['auth'])->group(function () {

//     // Route::get('/tasks', [LeaderDashboardController::class, 'tasks'])->name('leader.tasks');
//     // // Route::post('/tasks/update-status', [LeaderDashboardController::class, 'updateTaskStatus'])->name('leader.tasks.updateStatus');
//     // Route::post('/tasks/reassign', [LeaderDashboardController::class, 'reassignTask'])->name('leader.tasks.reassign');

//     // Route::post('/tasks/store', [LeaderDashboardController::class, 'storeTask'])->name('leader.tasks.store');

//     // // Optional dynamic engineer dropdown
//     // Route::get('/projects/{project}/engineers', function (Project $project) {
//     //     $engineers = $project->team->users()->where('role', 'engineer')->get(['id', 'name']);
//     //     return response()->json($engineers);
//     // });

//     // Route::get('/team-workload', [LeaderDashboardController::class, 'teamWorkload'])->name('leader.teamWorkload');
// });

require __DIR__ . '/auth.php';
