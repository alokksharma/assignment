<?php

use App\Http\Controllers\InviteAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\TemporaryStorageController;

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

Route::get('/redis-test', function () {
    Redis::set('framework', 'Laravel with Predis');
    return Redis::get('framework');
});
Route::get('settings', [SettingsController::class, 'index'])->name('settings');
Route::get('url/{code}', [ShortUrlController::class, 'redirectToOriginalUrl'])->name('short_urls.redirect');
Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/exports', [ExportController::class, 'create' ])->name('exports.create');
    Route::get('/exports/{export}/status', [ExportController::class, 'status']);
    Route::get('/exports/{export}/download', [ExportController::class, 'download'])->name('exports.download');
    Route::get('/fake-users', [InviteAdminController::class, 'fakeUser'])->name('fake.users');
    Route::get('temporary-storage/{temporary_storage}', [TemporaryStorageController::class, 'show']);
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
});

require __DIR__.'/auth.php';
