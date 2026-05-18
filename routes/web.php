<?php

use App\Http\Controllers\HomeController;
use App\Livewire\People\CreatePerson;
use App\Livewire\People\EditPerson;
use App\Livewire\People\ListPeople;
use Illuminate\Support\Facades\Auth;
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
    return Auth::check()
        ? redirect()->route('people.index')
        : redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/people', ListPeople::class)->name('people.index');
    Route::get('/people/create', CreatePerson::class)->name('people.create');
    Route::get('/people/{person}/edit', EditPerson::class)->name('people.edit');
});
