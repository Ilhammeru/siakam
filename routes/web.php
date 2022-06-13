<?php

use App\Http\Controllers\RoleController;
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

Route::middleware(['auth', 'role:admin'])->group(function(){
    // begin::role
    Route::get('/role/json', [RoleController::class, 'json'])->name('role.json');
    Route::get('/role/get-all', [RoleController::class, 'getAll'])->name('role.getAll');
    Route::resource('role', RoleController::class);
    Route::post('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    // end::role
    
    // begin::user
    Route::get('/user/json', [UserController::class, 'json'])->name('user.json');
    Route::resource('user', UserController::class);
    Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
    // end::user
});