<?php

use App\Models\Comment;
use App\Models\Publisher;
use App\sured;
use Illuminate\Support\Facades\Route;
use App\url;
use Illuminate\Support\Facades\DB;
// use App\Models\Open2che;


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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', 'GetArticle@index')->name('index');
Route::get('/save_article', 'GetArticle@saveArticle');
Route::get('/sign_up', 'GetArticle@sign_up')->name('sign_up');
Route::get('/sign_in', 'GetArticle@sign_in')->name('sign_in');

Route::get('/matome/index', 'Matome@index');
// Route::get('/store', 'GetArticle@store')->name('store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/save', 'PublisherComment@saveBoth');




Route::get('/hasmany', function () {
    $tests = url::with('sured')->get();
    url::with('sured')->get()->get('id');

    // foreach($tests as $test){
    //     echo $test->title;
    // }
});

Route::get('/test', function () {
    
 
    $practices = Publisher::with('Comment')->get();
    // dd($prasctices);
  foreach($practices as $practice){
      foreach($practice->comment as $comment){
          echo $comment->id;
      }
  }
  
    foreach ($practices as $practice) {
        foreach ($practice->comment as $comment) {
            echo $comment->title;
        }
    }
});
