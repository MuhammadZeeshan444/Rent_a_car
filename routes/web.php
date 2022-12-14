<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {

    Auth::routes();
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // Route::resource('permissions', PermissionsController::class);
    // Route::resource('roles', RoleController::class);
    // Route::post('roles/assign-permission/{id}', [RoleController::class,'assignPermission'])->name('roles.assign.permissions');
    // Route::post('roles/revok', [PermissionController::class,'revokeRole'])->name('users.revoke.roles');
});


