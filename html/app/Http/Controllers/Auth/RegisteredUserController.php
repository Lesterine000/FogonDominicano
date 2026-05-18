<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Gestiona una solicitud entrante de registro.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $reservedEmails = array_values(array_unique(array_filter([
            ...config('admin.allowed_emails', []),
            strtolower((string) config('admin.local_email', 'admin@fogondominicano.local')),
        ])));
        $reservedUsername = strtolower((string) config('admin.local_username', 'admin'));

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($reservedUsername) {
                    if (Str::lower(trim((string) $value)) === $reservedUsername) {
                        $fail('Ese nombre de usuario esta reservado.');
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
                function (string $attribute, mixed $value, \Closure $fail) use ($reservedEmails) {
                    if (in_array(Str::lower(trim((string) $value)), $reservedEmails, true)) {
                        $fail('Ese correo esta reservado para acceso administrativo.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
