<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\CautionController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\FraisController;
use App\Http\Controllers\LoyerController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\TypeAppartementController;
use App\Http\Controllers\TypeClientController;
use App\Http\Controllers\TypeDepenseController;
use App\Http\Controllers\TypeTerrainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::get('/media', FileDownloadController::class)->middleware('auth:sanctum');

Route::apiResource('appartements-types', TypeAppartementController::class)->middleware('auth:sanctum');
Route::apiResource('appartements', AppartementController::class)->middleware('auth:sanctum');
Route::apiResource('typeTerrain', TypeTerrainController::class)->middleware('auth:sanctum');
Route::apiResource('typeClient', TypeClientController::class)->middleware('auth:sanctum');
Route::apiResource('typeDepense', TypeDepenseController::class)->middleware('auth:sanctum');
Route::apiResource('proprietaires', ProprietaireController::class)->middleware('auth:sanctum');
Route::apiResource('personnes', PersonneController::class)->middleware('auth:sanctum');
Route::apiResource('terrains', TerrainController::class)->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::get('visites/pending', [VisiteController::class, 'getPending'])->middleware('auth:sanctum');
Route::apiResource('visites', VisiteController::class)->middleware('auth:sanctum');
Route::apiResource('frais', FraisController::class)->middleware('auth:sanctum');
Route::apiResource('avances', AvanceController::class)->middleware('auth:sanctum');
Route::apiResource('cautions', CautionController::class)->middleware('auth:sanctum');
Route::get('contrats/active-bail', [ContratController::class, 'getRentProcessing'])->middleware('auth:sanctum');
Route::apiResource('contrats', ContratController::class)->middleware('auth:sanctum');
Route::get('dettes/pending', [DetteController::class, 'getPending'])->middleware('auth:sanctum');
Route::apiResource('dettes', DetteController::class)->except(['update', 'destroy'])->middleware('auth:sanctum');
Route::apiResource('paiements', PaiementController::class)->middleware('auth:sanctum');
Route::get('depenses/pending', [DepenseController::class, 'getPending'])->middleware('auth:sanctum');
Route::apiResource('depenses', DepenseController::class)->middleware('auth:sanctum');
Route::resource('societes', SocieteController::class)->except(['create', 'edit', 'destroy', 'show'])->middleware('auth:sanctum');
Route::get('loyers/pending', [LoyerController::class, 'getPending'])->middleware('auth:sanctum');
Route::get('loyers/last-paid', [LoyerController::class, 'getLastPaid'])->middleware('auth:sanctum');
Route::get('loyers/pending', [LoyerController::class, 'getPending'])->middleware('auth:sanctum');
Route::resource('loyers', LoyerController::class)->except(['create', 'edit', 'update', 'destroy'])->middleware('auth:sanctum');
Route::get('achats/pending', [AchatController::class, 'getPending'])->middleware('auth:sanctum');
Route::resource('achats', AchatController::class)->except(['update', 'edit', 'create'])->middleware('auth:sanctum');

Route::get('societe-count', [CountController::class, 'societe'])->middleware('auth:sanctum');
Route::get('dashboard-count', [CountController::class, 'dashboard'])->middleware('auth:sanctum');
Route::get('dashboard-pendings', [CountController::class, 'pendings'])->middleware('auth:sanctum');
Route::get('paiements/payable/{id}', [PaiementController::class, 'getByPayable'])->middleware('auth:sanctum');
Route::post('paiements/direct', [PaiementController::class, 'createDirect'])->middleware('auth:sanctum');
Route::post('contrats/validate', [ContratController::class, 'contratValidate'])->middleware('auth:sanctum');
Route::post('loyers/avance', [LoyerController::class, 'avancer'])->middleware('auth:sanctum');
Route::patch('achats/validate/{achat}', [AchatController::class, 'valider'])->middleware('auth:sanctum');
Route::patch('paiements/validate/{paiement}', [PaiementController::class, 'valider'])->middleware('auth:sanctum');
Route::patch('loyers/cashed', [LoyerController::class, 'encaisser'])->middleware('auth:sanctum');
Route::patch('loyers/validate/{loyer}', [LoyerController::class, 'valider'])->middleware('auth:sanctum');
Route::patch('dettes/repay/{dette}', [DetteController::class, 'rembourser'])->middleware('auth:sanctum');
Route::patch('dettes/validate/{dette}', [DetteController::class, 'valider'])->middleware('auth:sanctum');
Route::patch('visites/direct-validate/{id}', [VisiteController::class, 'directValidate'])->middleware('auth:sanctum');
Route::patch('visites/frais-dossier/{visite}', [VisiteController::class, 'patchFraisDossier'])->middleware('auth:sanctum');
Route::patch('depenses/validate/{depense}', [DepenseController::class, 'valider'])->middleware('auth:sanctum');
