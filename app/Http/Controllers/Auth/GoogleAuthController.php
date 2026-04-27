<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar o crear usuario
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(24)), // Password aleatorio
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Actualizar google_id si no existe
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user, true);

            // Redirigir a la encuesta si venía de ahí
            if (session()->has('intended_survey')) {
                $url = session()->pull('intended_survey');
                return redirect($url);
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Error al autenticar con Google']);
        }
    }
}
