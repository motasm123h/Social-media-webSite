<?php

use Illuminate\Support\Facades\Route;
use App\Events\Hello;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     broadcast(new Hello());
//     return "Event has been sent!";
// });
Route::post('/broadcasting/auth', function () {
    return Auth::user();
 });

Route::get('/', function () {
    $user=User::find(1);
    // broadcast(new Hello());
    Hello::dispatch($user);
    return "Event has been sent!".$user->name;
});