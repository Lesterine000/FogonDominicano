<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Muestra la vista para solicitar el enlace de restablecimiento de contraseña.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Gestiona una solicitud entrante de enlace de restablecimiento de contraseña.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Enviaremos a este usuario el enlace de restablecimiento de contraseña. Una vez
        // intentado enviarlo, examinaremos la respuesta para obtener el mensaje que
        // debemos mostrar al usuario. Por último, devolveremos una respuesta adecuada.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
