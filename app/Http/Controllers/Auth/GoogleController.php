<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Enviar al usuario a Google
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Buscar usuario por google_id o por email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {
                DB::beginTransaction();

                // Separar nombre y apellido
                $fullName = $googleUser->getName() ?: $googleUser->user['given_name'] ?? '';
                $givenName = $googleUser->user['given_name'] ?? null;
                $familyName = $googleUser->user['family_name'] ?? null;

                if (!$givenName || !$familyName) {
                    // fallback: separar el nombre en primera palabra y resto
                    $parts = explode(' ', trim($fullName));
                    $givenName = $parts[0] ?? '';
                    $familyName = implode(' ', array_slice($parts, 1)) ?: '';
                }

                // Generar username a partir del email
                $baseUsername = strstr($googleUser->getEmail(), '@', true);
                $username = $this->generateUniqueUsername($baseUsername);

                $user = new User();
                $user->google_id    = $googleUser->getId();
                $user->name         = mb_strtoupper($givenName);
                $user->last_name    = mb_strtoupper($familyName);
                $user->email        = $googleUser->getEmail();
                $user->username     = $username;
                $user->users_genero = null; // lo puede completar luego
                $user->users_status = 1;

                // password random (no la usará, pero el campo no queda vacío)
                $user->password = bcrypt(Str::random(32));

                // Rol = 3 (como dijiste)
                $role = Role::find(3);
                if ($role) {
                    // mismo estilo que ya usas
                    $user->syncRoles($role->name);
                }

                if ($user->save()) {
                    DB::commit();
                } else {
                    DB::rollBack();
                    return redirect()
                        ->route('login')
                        ->with('error', 'No se pudo crear la cuenta con Google. Intente nuevamente.');
                }
            }

            // Loguear al usuario
            Auth::login($user, true);

            // Redirigir a donde quieras
            return redirect()->route('dashboard'); // ajusta tu ruta

        } catch (\Throwable $e) {
            // Puedes loguear el error
            // logger()->error($e->getMessage());
            return redirect()
                ->route('login')
                ->with('error', 'Error al iniciar sesión con Google.');
        }
    }

    /**
     * Genera un username único basado en el email.
     */
    protected function generateUniqueUsername(string $base): string
    {
        $username = Str::slug($base, '.'); // ej: juan.perez
        $original = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $original . $counter; // juan.perez1, juan.perez2...
            $counter++;
        }

        return $username;
    }
}
