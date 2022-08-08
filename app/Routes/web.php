<?php
use Nopadi\Http\Route;

/******************************************************
 ******** Nopake - Desenvolvimento web progressivo*****
 ******** Arquivo +de rotas principal (web)*************
*******************************************************/

Route::get('*',function(){ return array('code'=>404, 'msg'=>'Page not found!'); });	

Route::get('/',function(){ return view('welcome'); });
Route::get('/about',function(){ return view('about'); });















