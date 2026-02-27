<?php

use Illuminate\Support\Facades\Route;
use Src\Auth\Infrastructure\Http\Controllers\LoginController;
use Src\Auth\Infrastructure\Http\Controllers\LogoutController;
use Src\Auth\Infrastructure\Http\Controllers\RefreshTokenController;
use Src\Companies\Infrastructure\Http\Controllers\CreateCompanyController;
use Src\Companies\Infrastructure\Http\Controllers\GetCompaniesSelectorController;
use Src\Companies\Infrastructure\Http\Controllers\PaginateCompaniesController;
use Src\Companies\Infrastructure\Http\Controllers\UpdateCompanyController;

Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/refresh', [RefreshTokenController::class, 'refresh']);
Route::post('/auth/logout', [LogoutController::class, 'logout']);

Route::middleware('auth.token')->group(function () {
    Route::post('/companies', [CreateCompanyController::class, 'createCompany']);
    Route::get('/companies', [PaginateCompaniesController::class, 'paginateCompanies']);
    Route::get('/companies/selector', [GetCompaniesSelectorController::class, 'getCompaniesSelector']);
    Route::middleware('auth.user_context')->group(function () {
        Route::patch('/companies/{company_id}', [UpdateCompanyController::class, 'updateCompany']);
    });
});
