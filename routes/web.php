<?php

use App\Http\Controllers\InviteAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SurveController;
use App\Http\Controllers\VendorController;
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
Route::get('/setup', function () {
 Artisan::call('migrate', [
        '--force' => true,
    ]);

    Artisan::call('db:seed', [
        '--class' => Database\Seeders\RolePermissionSeeder::class,
        '--force' => true,
    ]);
    Artisan::call('db:seed', [
        '--class' => Database\Seeders\ProjectSeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Migration and RolePermissionSeeder completed successfully.',
    ]);
});

Route::get('/migrate-rollback', function () {
    Artisan::call('migrate:rollback');

    return response()->json([
        'status' => true,
        'message' => 'Migration completed successfully.',
    ]);
});

Route::get('/refresh-migrate', function (){
    Artisan::call('migrate:refresh', [
        '--seed' => true,
        '--force' => true,
    ]);
});

Route::get('/', function () {
    return view('auth.login');
});
Route::get('url/{code}', [ShortUrlController::class, 'redirectToOriginalUrl'])->name('short_urls.redirect');
Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:SuperAdmin|Admin|Vendor'])->group(function () {
    Route::get('/languages', [LanguageController::class, 'index'])->name('languages');
    Route::get('/questions', [QuestionsController::class, 'index'])->name('questions');
    Route::get('/countries', [CountriesController::class, 'index'])->name('countries');
    Route::get('/surveys', [SurveController::class, 'index'])->name('surveys');
    Route::get('/survey-projects', [SurveController::class, 'projects'])->name('survey.project');
    Route::get('/survey-projects-assign/{id}/create', [SurveController::class, 'vendorProjectAssign'])->name('vendor.project.assign');
    Route::get('/survey-projects-assign/list', [SurveController::class, 'vendorProjectAssignList'])->name('vendor.project.assign.list');
    Route::get('/vendors/search', [SurveController::class, 'searchVendors'])->name('vendors.search');
    Route::post('/survey-projects-assign/store/{id}', [SurveController::class, 'vendorProjectAssignStore'])->name('vendors-project-assign.store');
    Route::get('/sync-languages', [LanguageController::class, 'syncLanguages'])->name('languages.sync');
    Route::get('/sync-countries', [CountriesController::class, 'syncCountries'])->name('countries.sync');
    Route::get('/sync-questions', [QuestionsController::class, 'syncQuestions'])->name('questions.sync');
    Route::get('/sync-surveys', [SurveController::class, 'syncSurve'])->name('surveys.sync');

    Route::get('/invite-admin/{id}/edit', [InviteAdminController::class, 'edit'])->name('companies.invite-admin.edit');
    Route::put('/invite-admin/{id}', [InviteAdminController::class, 'update'])->name('companies.invite-admin.update');
    Route::get('companies/invite-admin', [InviteAdminController::class, 'create'])->name('companies.invite-admin');
    Route::post('companies/invite-admin', [InviteAdminController::class, 'store'])->name('companies.invite-admin.store');
    Route::get('invite-list', [InviteAdminController::class, 'index'])->name('companies.invite-list');
    Route::resource('vendors', VendorController::class)->only(['index', 'create', 'store']);
});

Route::middleware(['auth', 'role:SuperAdmin|Admin|Member'])->group(function () {
    Route::get('short-urls', [ShortUrlController::class, 'index'])->name('short_urls.index');
    Route::get('short-urls/create', [ShortUrlController::class, 'create'])->name('short_urls.create')->middleware('can:create short url,App\Models\ShortUrl');
    Route::post('short-urls/store', [ShortUrlController::class, 'store'])->name('short_urls.store')->middleware('can:create short url,App\Models\ShortUrl');
  //  Route::get('short-urls/{shortUrl}', [ShortUrlController::class, 'show'])->name('short_urls.show');
});

require __DIR__.'/auth.php';
