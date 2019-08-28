<?php

use Botble\Blog\Http\Controllers\ApiController;

Route::group(['middleware' => 'api', 'prefix' => 'api/v1'], function () {
    Route::get('search', [ApiController::class, 'getSearch'])->name('public.api.search');
});
