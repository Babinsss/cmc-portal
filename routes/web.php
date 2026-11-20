<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\PricelistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'home'])->name('portal.home');
Route::get('/services', [PortalController::class, 'services'])->name('portal.services');
Route::get('/pricelist', [PricelistController::class, 'index'])->name('portal.pricelist');
Route::get('/pricelist/pdf', [PricelistController::class, 'downloadPdf'])->name('portal.pricelist.pdf.all');
Route::get('/pricelist/{slug}/pdf', [PricelistController::class, 'downloadPdf'])->name('portal.pricelist.pdf');
Route::get('/about', [PortalController::class, 'about'])->name('portal.about');
Route::get('/advisories', [PortalController::class, 'advisories'])->name('portal.advisories');
Route::get('/advisories/{slug}', [PortalController::class, 'advisoryShow'])->name('portal.advisory.show');
Route::get('/careers', [PortalController::class, 'careers'])->name('portal.careers');
Route::get('/contact', [PortalController::class, 'contact'])->name('portal.contact');
Route::post('/contact', [PortalController::class, 'submitInquiry'])->name('portal.contact.submit');
Route::get('/privacy-notice', [PortalController::class, 'privacyNotice'])->name('portal.privacy');
