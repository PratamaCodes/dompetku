<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::livewire('/kategori', 'pages::kategori');
Route::livewire('/transaksi', 'pages::transaksi');