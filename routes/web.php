<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-redis', function () {
    try {
        // Set a test key in Redis
        Redis::set('test-key', 'Redis connection successful!');

        // Retrieve the test key from Redis
        $value = Redis::get('test-key');

        // Return the value as a response
        return response()->json([
            'message' => $value,
        ]);
    } catch (\Exception $e) {
        // Return the error if the connection fails
        return response()->json([
            'error' => 'Could not connect to Redis',
            'message' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/test-liara', function () {
    $fileContent = 'Hello, Liara!';
    $filePath = 'test-folder/hello.txt';

    // Upload the file to Liara storage
    Storage::disk('liara')->put($filePath, $fileContent);

    return 'File uploaded successfully to Liara!';
});
