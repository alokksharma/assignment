<?php

use App\Http\Controllers\InviteAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:SuperAdmin|Admin'])->group(function () {
    Route::get('companies/invite-admin', [InviteAdminController::class, 'create'])->name('companies.invite-admin');
    Route::post('companies/invite-admin', [InviteAdminController::class, 'store'])->name('companies.invite-admin.store');
    Route::get('invite-list', [InviteAdminController::class, 'index'])->name('companies.invite-list');
});

Route::middleware(['auth', 'role:SuperAdmin|Admin|Member'])->group(function () {
    Route::get('short-urls', [ShortUrlController::class, 'index'])->name('short_urls.index');
    Route::get('short-urls/create', [ShortUrlController::class, 'create'])->name('short_urls.create')->middleware('can:create short url,App\Models\ShortUrl');
    Route::post('short-urls/store', [ShortUrlController::class, 'store'])->name('short_urls.store')->middleware('can:create short url,App\Models\ShortUrl');
  //  Route::get('short-urls/{shortUrl}', [ShortUrlController::class, 'show'])->name('short_urls.show');
});

require __DIR__.'/auth.php';
