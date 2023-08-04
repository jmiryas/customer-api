<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::get("/setup", function () {
    $credentials = [
        "email" => "admin@gmail.com",
        "password" => "user123"
    ];

    if (!Auth::attempt($credentials)) {
        $user = User::create([
            "name" => "Administrator",
            "email" => "admin@gmail.com",
            "password" => Hash::make("user123")
        ]);

        if (Auth::attempt($credentials)) {
            $adminToken = $user->createToken("admin-token", ["create", "update", "delete"]);
            $updateToken = $user->createToken("update-token", ["create", "update", "delete"]);
            $basicToken = $user->createToken("basic-token", ["none"]);

            return [
                "admin" => $adminToken->plainTextToken,
                "update" => $updateToken->plainTextToken,
                "basic" => $basicToken->plainTextToken,
            ];
        }
    }

    if (Auth::attempt($credentials)) {
        $user = User::where("email", $credentials["email"])->first();

        $adminToken = $user->createToken("admin-token", ["create", "update", "delete"]);
        $updateToken = $user->createToken("update-token", ["create", "update", "delete"]);
        $basicToken = $user->createToken("basic-token", ["none"]);

        return [
            "admin" => $adminToken->plainTextToken,
            "update" => $updateToken->plainTextToken,
            "basic" => $basicToken->plainTextToken,
        ];
    }
});
