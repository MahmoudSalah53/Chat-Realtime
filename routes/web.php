<?php

use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/chat');

Route::middleware('auth')->group(function() {
    Route::get('/chat', Index::class)->name('chat.index');
    Route::get('/chat/{query}', Chat::class)->name('chat');
    Route::get('/users', Users::class)->name('users');
});

Route::post('/set-timezone', function (Request $request) {
    Session::put('user-timezone', $request->timezone);
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
