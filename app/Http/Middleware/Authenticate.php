<?php

namespace App\Http\Middleware;

use App\Services\ReferooService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash;


class Authenticate extends Middleware
{
    
    // Get the path the user should be redirected to when they are not authenticated.
    protected function redirectTo(Request $request): ?string
    {
        // Inject ReferooService
        $referooService = app(ReferooService::class);

        // Check if a Referoo access token exists and is valid
        if ($referooService->hasAccessToken()) {
            $loggedInUser = $referooService->getLoggedInUser();
            $userEmail = $loggedInUser['email'];
            $userName = $loggedInUser['first_name'].$loggedInUser['last_name'];
            $user = User::where('email', $userEmail)->first();

            if (!$user) {
                // If the user doesn't exist, create one, as our app's users are dictated by Referoo
                $rawUser = [
                    'name' => $userName,
                    'email' => $userEmail,
                    'password' => 'ThisisaRandomDefaultpassword212135!@#$',
                ];
            
                // Validate the user data obtained from the API response
                $validator = Validator::make($rawUser, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                    'password' => ['required'],
                ]);
            
                if ($validator->fails()) {
                    //todo
                } else {
                    // Validation succeeded, create the user
                    $user = User::create([
                        'name' => $rawUser['name'],
                        'email' => $rawUser['email'],
                        'password' => Hash::make($rawUser['password']),
                    ]);
            
                    // Trigger the Registered event and log in the user
                    Auth::login($user);
                    // Proceed with the request
                    return null;
                }
            }else{
                Auth::login($user);
                // Proceed with the request
                return null;
            }
            
        }

        // If the access token is not valid or does not exist
        return $request->expectsJson() ? null : route('login');
    }
}