<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Route;

Route::get('/start-queue', function () {
    \Illuminate\Support\Facades\Artisan::call('queue:work',['--queue'=>'stock.q']);
});
$router->get('/', function () {
    return 'Hello World from stock service';
});
$router->get('/stocks', ["as" => "stocks", "uses" => "StockController@getStocks"]);
$router->post('/store', ["as" => "stocks.store", "uses" => "StockController@store"]);

$router->delete('/delete/{id}', ["as" => "stocks.delete", "uses" => "StockController@delete"]);
