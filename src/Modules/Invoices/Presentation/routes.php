<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoices\Presentation\Http\InvoiceController;

Route::get('/invoices/{id}', [InvoiceController::class, 'view']);
Route::post('/invoices', [InvoiceController::class, 'create']);
Route::post('/invoices/send/{id}', [InvoiceController::class, 'send']);
