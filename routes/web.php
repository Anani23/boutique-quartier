<?php

use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Appel serveur-à-serveur de CinetPay : pas de session, pas de CSRF.
Route::post('/abonnement/webhook', [AbonnementController::class, 'webhook'])->name('abonnement.webhook');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Toujours accessible, même abonnement expiré, sinon impossible de payer pour se réactiver.
    Route::middleware('role:gerant')->group(function () {
        Route::get('/abonnement', [AbonnementController::class, 'index'])->name('abonnement.index');
        Route::post('/abonnement/payer', [AbonnementController::class, 'payer'])->name('abonnement.payer');
    });
    Route::get('/abonnement/retour', [AbonnementController::class, 'retour'])->name('abonnement.retour');

    Route::middleware('abonnement.actif')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
        Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
        Route::get('/ventes/creer', [VenteController::class, 'create'])->name('ventes.create');
        Route::post('/ventes', [VenteController::class, 'store'])->name('ventes.store');
        Route::get('/ventes/{vente}', [VenteController::class, 'show'])->name('ventes.show');
        Route::get('/ventes/{vente}/pdf', [VenteController::class, 'pdf'])->name('ventes.pdf');

        Route::middleware('role:gerant')->group(function () {
            Route::get('/produits/creer', [ProduitController::class, 'create'])->name('produits.create');
            Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
            Route::get('/produits/{produit}/modifier', [ProduitController::class, 'edit'])->name('produits.edit');
            Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
            Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

            Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
            Route::post('/categories', [CategorieController::class, 'store'])->name('categories.store');
            Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');

            Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');

            Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
            Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
            Route::delete('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
        });
    });
});
