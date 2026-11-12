<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PartnerApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('parners', [PartnerApiController::class, 'parners'])->name('parners');
Route::middleware('supplier.auth')->group(function () {

    Route::get('languages', [PartnerApiController::class, 'languages'])->name('languages');
    Route::get('countries', [PartnerApiController::class, 'countries'])->name('countries');
    Route::get('qualifications', [PartnerApiController::class, 'qualifications'])->name('qualifications');
    Route::get('survey', [PartnerApiController::class, 'survey'])->name('survey');
   // return $request->user();
});
