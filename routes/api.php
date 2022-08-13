<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use SaltLaravel\Controllers\ApiResourcesController;

// NOTE: make sure this route default order at last
app()->booted(function() {

  $version = config('app.API_VERSION', 'v1');

  Route::middleware(['api'])
      ->prefix("api/{$version}")
      ->group(function () {

    // DEFAULT: API RESOURCES
    Route::get("{collection}", [ApiResourcesController::class, 'index']); // get entire collection
    Route::post("{collection}", [ApiResourcesController::class, 'store']); // create new collection

    Route::get("{collection}/trash", [ApiResourcesController::class, 'trash']); // trash of collection

    Route::post("{collection}/import", [ApiResourcesController::class, 'import']); // import collection from external
    Route::post("{collection}/export", [ApiResourcesController::class, 'export']); // export entire collection
    Route::get("{collection}/report", [ApiResourcesController::class, 'report']); // report collection

    Route::get("{collection}/{id}/trashed", [ApiResourcesController::class, 'trashed'])->where('id', '[a-zA-Z0-9-]+'); // get collection by ID from trash

    // RESTORE data by ID (id), selected IDs (selected), and All data (all)
    Route::post("{collection}/{id}/restore", [ApiResourcesController::class, 'restore'])->where('id', '[a-zA-Z0-9-]+'); // restore collection by ID

    // DELETE data by ID (id), selected IDs (selected), and All data (all)
    Route::delete("{collection}/{id}/delete", [ApiResourcesController::class, 'delete'])->where('id', '[a-zA-Z0-9-]+'); // hard delete collection by ID

    Route::get("{collection}/{id}", [ApiResourcesController::class, 'show'])->where('id', '[a-zA-Z0-9-]+'); // get collection by ID
    Route::put("{collection}/{id}", [ApiResourcesController::class, 'update'])->where('id', '[a-zA-Z0-9-]+'); // update collection by ID
    Route::patch("{collection}/{id}", [ApiResourcesController::class, 'patch'])->where('id', '[a-zA-Z0-9-]+'); // patch collection by ID
    // DESTROY data by ID (id), selected IDs (selected), and All data (all)
    Route::delete("{collection}/{id}", [ApiResourcesController::class, 'destroy'])->where('id', '[a-zA-Z0-9-]+'); // soft delete a collection by ID

  });

});

