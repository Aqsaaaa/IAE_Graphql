<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/vendor-requests', [DashboardController::class, 'getVendorRequests']);
Route::get('/dashboard/deliveries', [DashboardController::class, 'getDeliveries']);
Route::get('/dashboard/memberships', [DashboardController::class, 'getMemberships']);
Route::post('/dashboard/vendor-requests/create', [DashboardController::class, 'createVendorRequest']);
Route::post('/dashboard/create-delivery', [DashboardController::class, 'createDelivery']);
Route::post('/dashboard/create-memberships', [DashboardController::class, 'createMembership']);

