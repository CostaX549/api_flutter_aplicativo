<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\DocsController;
use App\Livewire\Agendamento;
use App\Livewire\Horario;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/agendamentos', Agendamento::class)->name('agendamentos');
Route::get('/horarios', Horario::class)->name('horarios');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DocsController::class, 'index'])->name('dashboard');
});
