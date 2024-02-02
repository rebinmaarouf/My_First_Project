<?php

namespace App\Http\Controllers\Auth;
Use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\getFactorial;


class ProviderController extends Controller
{
    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
        
    }

    public function callback($provider){

        try {

            $SocialUser = Socialite::driver($provider)->user();

            if(User::where('email', $SocialUser->getEmail())->exists()){
                return redirect( '/login')->withErrors(['email' => 'this email uses diffrent method to login']);
            }

            $user = User::where([
                'provider' => $provider,
                'provider_id' => $SocialUser ->id

            ])->first();

            if(!$user){

                $user = User::create([
                    'name' => $SocialUser ->getName(),
                    'email' => $SocialUser ->getEmail(),
                    'username' =>User::generateUserName($SocialUser ->getNickname()),
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now()


                ]);

            }

                 
            Auth::login($user);
     
            return redirect('/dashboard');
            // dd($user);


        } catch (\Exception $e) {
            return redirect('/login');
            
        }
        
    }
}