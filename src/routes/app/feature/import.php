<?php


use App\Http\Controllers\App\Import\CandidateImportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

//Route::get('import/candidates', [CandidateImportController::class, 'importView']);

Route::group(['prefix' => 'import'],function (Router $router){
    $router->get('candidates',[CandidateImportController::class,'importViewCandidate'])
        ->name('view.existing.candidate');

    $router->post('candidates',[CandidateImportController::class,'importCandidate'])
        ->name('import.existing.candidate');

});
