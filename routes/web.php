<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/kendaraan');
});

Route::get('/kendaraan', function () {
    return view('kendaraan');
});

Route::get('/supir', function () {
    return view('supir');
});

Route::get('/rute', function () {
    return view('rute');
});

Route::get('/perawatan', function () {
    return view('perawatan');
});

Route::get('/bahan-bakar', function () {
    return view('bahan-bakar');
});
