<?php

use Illuminate\Support\Facades\Route;
use Src\Companies\Infrastructure\Http\Controllers\CreateCompanyController;
use Src\Companies\Infrastructure\Http\Controllers\GetCompaniesSelectorController;
use Src\Companies\Infrastructure\Http\Controllers\PaginateCompaniesController;
use Src\Companies\Infrastructure\Http\Controllers\UpdateCompanyController;

Route::post('/companies', [CreateCompanyController::class, 'createCompany']);
Route::patch('/companies/{company_id}', [UpdateCompanyController::class, 'updateCompany']);
Route::get('/companies', [PaginateCompaniesController::class, 'paginateCompanies']);
Route::get('/companies/selector', [GetCompaniesSelectorController::class, 'getCompaniesSelector']);
