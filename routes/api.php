<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanRepaymentController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/loan', [LoanController::class, 'create']);
    Route::get('/loan/{id}', [LoanController::class, 'getById']);
    Route::get('/loans', [LoanController::class, 'getAllForUser']);

    Route::middleware('admin')->group(function () {
        Route::post('/loan/approve/{loanId}', [LoanController::class, 'approve']);
        Route::get('/loans/all', [LoanController::class, 'getAll']);
    });

    Route::post('/loan/{loanId}/repayment', [LoanRepaymentController::class, 'create']);
    Route::get('/loan/{loanId}/repayments', [LoanRepaymentController::class, 'getAll']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);