<?php

use Illuminate\Support\Facades\Route;
use Src\Auth\Infrastructure\Http\Controllers\LoginController;
use Src\Auth\Infrastructure\Http\Controllers\LogoutController;
use Src\Auth\Infrastructure\Http\Controllers\RefreshTokenController;
use Src\Companies\Infrastructure\Http\Controllers\CreateCompanyController;
use Src\Companies\Infrastructure\Http\Controllers\GetCompaniesSelectorController;
use Src\Companies\Infrastructure\Http\Controllers\PaginateCompaniesController;
use Src\Companies\Infrastructure\Http\Controllers\UpdateCompanyController;
use Src\Roles\Infrastructure\Http\Controllers\CreateRoleController;
use Src\UserTypes\Infrastructure\Http\Controllers\GetAssignableUserTypesController;

Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/refresh', [RefreshTokenController::class, 'refresh']);
Route::post('/auth/logout', [LogoutController::class, 'logout']);

Route::middleware('auth.token')->group(function () {
    Route::get('/assistant-types', [GetAssignableUserTypesController::class, 'getAssignableUserTypes']);
    Route::post('/companies', [CreateCompanyController::class, 'createCompany']);
    Route::get('/companies', [PaginateCompaniesController::class, 'paginateCompanies']);
    Route::get('/companies/selector', [GetCompaniesSelectorController::class, 'getCompaniesSelector']);
    Route::middleware('auth.root_company_id')->group(function () {
        Route::post('/roles', [CreateRoleController::class, 'createRole']);
        Route::patch('/companies/{company_id}', [UpdateCompanyController::class, 'updateCompany']);
    });
});
