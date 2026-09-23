<?php

use App\Http\Controllers\InviteAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SurveController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MailTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

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


Route::get('/mail-test/config', [MailTestController::class, 'config']);
Route::get('/mail-test', [MailTestController::class, 'raw']);
Route::get('/mail-test/forgot-password', [MailTestController::class, 'forgotPasswordTemplate']);

Route::get('/setup', function () {
 Artisan::call('migrate', [
        '--force' => true,
    ]);

    Artisan::call('db:seed', [
        '--class' => Database\Seeders\RolePermissionSeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Migration and RolePermissionSeeder completed successfully.',
    ]);
});

Route::get('/project-seed', function () {
     Artisan::call('db:seed', [
        '--class' => Database\Seeders\SurveyApiProjectIdSeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'SurveyApiProjectIdSeeder completed successfully.',
    ]);
});

Route::get('/clear-cache', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return "All Laravel caches cleared";
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

Route::get('s2c', [ShortUrlController::class, 's2c'])->name('api.s2c');
Route::get('c2s', [ShortUrlController::class, 'c2s'])->name('api.c2s');
Route::get('iframe', [ShortUrlController::class, 'iframe'])->name('api.iframe');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/mail-test/resetmail', [MailTestController::class, 'resetmail']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('roles', RoleController::class);
    Route::resource('suppliers', ProjectController::class);
    Route::get('/invoices/{id}/export', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::get('/invoices/{id}/show', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'downloadInvoice'])
        ->name('invoice.pdf');
});


Route::middleware(['auth', 'role:SuperAdmin|Admin|Vendor|Member'])->group(function () {

    Route::get('/survey-projects', [SurveController::class, 'projects'])->middleware('can:view-surveys-projects')->name('survey.project');
    Route::get('/survey-projects-assign/list', [SurveController::class, 'vendorProjectAssignList'])->middleware('can:view-vendor-assign-projects')->name('vendor.project.assign.list');
    Route::get('/vendors/search', [SurveController::class, 'searchVendors'])->name('vendors.search');

    Route::get('/vendor-project-report', [ReportController::class, 'index'])->middleware('can:report-view')->name('vendor.project.report');
    Route::get('vendor-project-report/export', [ReportController::class, 'export'])->middleware('can:report-export')->name('proof.export');

    Route::get('/survey-projects-assign/{id}/create', [SurveController::class, 'vendorProjectAssign'])->middleware('can:create-vendor-assign-projects')->name('vendor.project.assign');
    Route::post('/survey-projects-assign/store/{id}', [SurveController::class, 'vendorProjectAssignStore'])->middleware('can:create-vendor-assign-projects')->name('vendors-project-assign.store');
    Route::delete('/survey-projects-assign/{vendorProjectAssign}', [SurveController::class, 'destroy'])->middleware('can:delete-vendor-assign-projects')->name('vendor.project.assign.destroy');

    Route::get('user/create', [InviteAdminController::class, 'create'])->middleware('can:create-user')->name('companies.invite-admin');
    Route::post('user/store', [InviteAdminController::class, 'store'])->middleware('can:create-user')->name('companies.invite-admin.store');
    Route::get('/user/{id}/edit', [InviteAdminController::class, 'edit'])->middleware('can:edit-user')->name('companies.invite-admin.edit');
    Route::put('/user/{id}', [InviteAdminController::class, 'update'])->middleware('can:edit-user')->name('companies.invite-admin.update');
    Route::get('users', [InviteAdminController::class, 'index'])->middleware('can:view-user')->name('companies.invite-list');
    Route::delete('/users/{user}', [InviteAdminController::class, 'destroy'])->middleware('can:destroy-user')->name('users.destroy');
    Route::resource('vendors', VendorController::class);
    Route::resource('partners', PartnerController::class);

    Route::get('/languages', [LanguageController::class, 'index'])->middleware('can:view-languages')->name('languages');
    Route::get('/questions', [QuestionsController::class, 'index'])->middleware('can:view-qualififcations')->name('questions');
    Route::get('/countries', [CountriesController::class, 'index'])->middleware('can:view-countries')->name('countries');
    Route::get('/surveys', [SurveController::class, 'index'])->middleware('can:view-surveys')->name('surveys');

    Route::middleware('can:generate-api')->group(function () {
        Route::get('/sync-languages', [LanguageController::class, 'syncLanguages'])->name('languages.sync');
        Route::get('/sync-countries', [CountriesController::class, 'syncCountries'])->name('countries.sync');
        Route::get('/sync-questions', [QuestionsController::class, 'syncQuestions'])->name('questions.sync');
        Route::get('/sync-surveys', [SurveController::class, 'syncSurve'])->name('surveys.sync');
    });


   Route::middleware('can:data-reconcile-create')->group(function () {
        Route::get('/data-reconcile', [ShortUrlController::class, 'dataReconcile'])->name('data.reconcile');
        Route::post('data-reconcile', [ShortUrlController::class, 'dataReconcileStore'])->name('data.store');
        Route::post('data-reconcile/import', [ReportController::class, 'import'])->name('data.reconcile.import');
    });

});


require __DIR__.'/auth.php';
