<?php

namespace App\Http\Controllers;
use Exception;


use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\getFactorial;

class GithubController extends Controller
{
    public function redirect(){

        return Socialite::driver(driver:'github')->redirect();
     

    }

    public function callback(){
        try {
    
            $user = Socialite::driver(driver:'github')->user();

            $user = User::updateOrCreate([
                // ''github_id' => $user->id'
                'email' => $user ->email
            ],
            [
                'name' => $user->name,
                'password' => Hash::make(Str::random(length:10)),
                // 'nickname' => $user ->nickname,
                // 'email' => $user ->email,
                // 'github_token' => $user ->token,
                // 'auth_type' => 'github',
                

            ]);

            Auth::login($user);
            return redirect('/dashboard');


        } catch (\Exception $e) {
             ray($e->getMessage());
            
        }

    }
}
