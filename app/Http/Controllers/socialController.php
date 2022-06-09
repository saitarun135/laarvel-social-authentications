<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class socialController extends Controller
{
    //https://www.itsolutionstuff.com/post/laravel-6-socialite-login-with-google-gmail-accountexample.html

    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()

    {

        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('google_id', $user->id)->first();
            if($finduser){
                FacadesAuth::login($finduser);
                return redirect('/home');
            }else{
                $newUser = User::insert([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                return response()->json(['saved']);
            }
        } catch (Exception $e) {

            dd($e->getMessage());

        }

    }
}
