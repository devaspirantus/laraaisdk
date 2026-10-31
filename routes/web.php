<?php

use App\Ai\Agents\ChatAgent;
use Illuminate\Support\Facades\Route;
use App\Livewire\Chat;

Route::get('/chat',function(){
    dd('test');
});

Route::get('/ai-test', function () {
    $response = (new ChatAgent)->prompt('ايش تقدر تطلع لي  المنتجات المخفضة من امازون السعودية');

    return response()->json([
        'response'=> (string) $response,
    ]);
});

Route::get('/ai-chat',function(){
    return (new ChatAgent)->stream("what is the laravel");
});