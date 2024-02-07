<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//bárki, aki bejelentkezik, ezeket éri el:
Route::middleware('auth.basic')->group(function () {

    //csak az admin éri el:
    Route::middleware('admin')->group(function (){
        Route::apiResource('/users', UserController::class);
    } );

    //lekérdezések:

    Route::get('lending_by_user', [UserController::class, 'lendingByUser']);
    Route::get('all_lending_user_copy', [LendingController::class, 'allLendingUserCopy']);
    Route::get('lendings_count_user', [LendingController::class, 'lendingsCountByUser']);

    Route::get('title_count/{title}',[BookController::class, 'titleCount']);
    Route::get('hard_auth_title/{hardcov}', [BookController::class, 'hardAuthorTitle']);
    Route::get('copies_in_year/{year}', [BookController::class, 'copiesInYear']);

});

//bejelentkezés nélkül is elérhető route-ok:
Route::apiResource('/copies', CopyController::class);
Route::apiResource('/books', BookController::class);

Route::get('/lendings', [LendingController::class, 'index']);
Route::get('/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']);
//Route::put('/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
Route::post('/lendings', [LendingController::class, 'store']);
Route::delete('/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']); 

Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/{book_id}/{user_id}/{start}', [ReservationController::class, 'show']);
//Route::put('/reservations/{user_id}/{start}', [ReservationControllerController::class, 'update']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::delete('/reservations/{book_id}/{user_id}/{start}', [ReservationController::class, 'destroy']);
Route::get('/authwithmore', [ReservationController::class,'authorsWithMoreBooks']);
Route::get('/backtoday', [LendingController::class,'broughtBackToday']);

//egyéb végpontok
Route::patch('/user_update_password/{id}', [UserController::class, 'updatePassword']);
