<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $allowedEmails = config('admin.allowed_emails', []);
            $emailRecibido = strtolower($user->email);

            if (! in_array($emailRecibido, $allowedEmails, true)) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Acceso denegado: Tu cuenta no tiene permisos de administrador.');
            }

            $finduser = User::where('google_id', $user->id)
                ->orWhere('email', $emailRecibido)
                ->first();

            if ($finduser) {
                $finduser->update([
                    'google_id' => $user->id,
                    'is_admin' => true,
                ]);

                Auth::login($finduser, true);

                return redirect()->route('admin.dishes.index');
            }

            $newUser = User::create([
                'name' => $user->name,
                'email' => $emailRecibido,
                'google_id' => $user->id,
                'is_admin' => true,
                'password' => encrypt('123456dummy'),
                'email_verified_at' => now(),
            ]);

            Auth::login($newUser, true);

            return redirect()->route('admin.dishes.index');
        } catch (Exception $e) {
            return redirect()
                ->route('login')
                ->with('error', 'Error de autenticacion con Google.');
        }
    }
}
