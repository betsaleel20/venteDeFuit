<?php

use Illuminate\Support\Facades\Route;
use Shop\Reference\Infrastructure\Http\Controllers\GetAllReferenceAction;
use Shop\Reference\Infrastructure\Http\Controllers\SaveReferenceAction;

Route::post('reference/save', SaveReferenceAction::class);
Route::get('references/all', GetAllReferenceAction::class);
