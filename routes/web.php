<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\DashboardController;
//use App\Http\Controllers\Admin\UsersController;
//use App\Http\Controllers\Admin\CreateKeyController;

use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\ContractsController;
use App\Http\Controllers\Admin\ContractItemsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('email', $googleUser->getEmail())->first();

    if (!$user) {
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(str()->random(24)),
            'level' => User::LEVEL_PARTNER,
        ]);
    } else {
        // Só atualiza o google_id, NÃO mexe no level
        $user->update([
            'google_id' => $googleUser->getId(),
        ]);
    }

    Auth::login($user);

    // Redireciona baseado no nível
    if ($user->level == User::LEVEL_ADMIN) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('partner.dashboard');

});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/users', [UsersController::class, 'index'])->name('users');
    //Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
    //Route::get('/createkey', function () {
    //    return Inertia::render('Admin/CreateKey/Index');
    //})->name('createkey');
    
    //Route::post('/createkey', [CreateKeyController::class, 'store']) ->name('createkey.generate');


    //CLIENTS
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients');
    Route::post('/clients', [ClientsController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientsController::class, 'show'])->name('clients.show');
    Route::put('/clients/{client}', [ClientsController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientsController::class, 'destroy'])->name('clients.destroy');

    //SERVICES
    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::post('/services', [ServicesController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [ServicesController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServicesController::class, 'destroy'])->name('services.destroy');

    //CONTRACTS
    Route::get('/contracts', [ContractsController::class, 'index'])->name('contracts');
    Route::post('/contracts', [ContractsController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractsController::class, 'show'])->name('contracts.show');
    Route::put('/contracts/{contract}', [ContractsController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractsController::class, 'destroy'])->name('contracts.destroy');

    //CONTRACTS ITEMS
    Route::post('/contract-items', [ContractItemsController::class, 'store'])->name('contract-items.store');
    Route::put('/contract-items/{contractItem}', [ContractItemsController::class, 'update'])->name('contract-items.update');
    Route::delete('/contract-items/{contractItem}', [ContractItemsController::class, 'destroy'])->name('contract-items.destroy');

});

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
