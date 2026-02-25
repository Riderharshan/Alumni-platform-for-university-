<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\FirebaseController;
use App\Livewire\Alumni\Feed;
use App\Livewire\Alumni\ProfileView;
use App\Livewire\activity;
use App\Livewire\AlumniProfileForm;
use App\Livewire\Directory;





Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Route::get('/alumni/profile', AlumniProfileForm::class)->name('alumni.profile');
    Route::get('/alumni/directory', Directory::class)->name('alumni.Directory');
    Route::get('/alumni/{userId}',  ProfileView::class)->name('alumni.view');
});

Route::get('/feed', Feed::class)->middleware(['auth'])->name('alumini.Feed'); 

Route::get('/feeds', activity::class)->middleware('auth')->name('feed');

Route::post('/firebase/verify-phone', [FirebaseController::class, 'verifyIdToken'])->name('firebase.verify');

require __DIR__.'/auth.php';
