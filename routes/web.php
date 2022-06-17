<?php

use App\Http\Controllers\BurialTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TpuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Route::get('/user', function() {
    $pageTitle = "Template User";
    return view('user', compact('pageTitle'));
})->name('template.user');
Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/dashboard', function() {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::get('/password-reset', function() {
    return 'password reset';
})->name('password.reset');
Route::get('/password-email', function() {
    return 'password email';
})->name('password.email');
Route::get('/password-request', function() {
    return 'password request';
})->name('password.request');

Route::middleware(['auth'])->group(function(){
    Route::middleware(['role:admin:superadmin'])->group(function() {
        // begin::role
        Route::get('/role/json', [RoleController::class, 'json'])->name('role.json');
        Route::get('/role/get-all', [RoleController::class, 'getAll'])->name('role.getAll');
        Route::resource('role', RoleController::class);
        Route::post('/role/{id}', [RoleController::class, 'update'])->name('role.update');
        // end::role
        
        // begin::user
        Route::get('/user/json', [UserController::class, 'json'])->name('user.json');
        Route::get('/user/get-data-form', [UserController::class, 'getDataForm'])->name('user.getDataForm');
        Route::resource('user', UserController::class);
        Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
        // end::user

        // begin::burial-type
        Route::get('/burial-type/json', [BurialTypeController::class, 'json'])->name('burial-type.json');
        Route::resource('burial-type', BurialTypeController::class);
        // end::burial-type
    });
    // begin::tpu
    Route::get('/tpu/json', [TpuController::class, 'json'])->name('tpu.json');
    Route::get('/tpu/detail-grave/{id}', [TpuController::class, 'detailGrave'])->name('tpu.detailGrave');
    Route::get('/tpu/grave/{id}', [TpuController::class, 'detailTpuGrave'])->name("tpu.detailTpuGrave");
    Route::get('/tpu/detail/{id}', [TpuController::class, 'show'])->name("tpu.show");
    Route::post('/tpu/grave', [TpuController::class, 'storeGrave'])->name('tpu.grave.store');
    Route::put('/tpu/grave/{id}', [TpuController::class, 'editGrave'])->name('tpu.grave.edit');
    Route::delete('/tpu/grave/{id}', [TpuController::class, 'deleteGrave'])->name('tpu.grave.delete');
    Route::get('/tpu/show/{id}', [TpuController::class, 'showTpu'])->name('tpu.indentity.show');
    Route::post('/tpu/identity/{id}', [TpuController::class, 'storeTpu'])->name('tpu.identity.store');
    Route::resource('tpu', TpuController::class);
    // end::tpu
});