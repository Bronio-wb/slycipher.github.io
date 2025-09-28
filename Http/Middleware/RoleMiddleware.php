<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Maneja la verificación de roles.
     * Mejor detección de roles en distintos campos/relaciones y soporte para paquetes comunes.
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        // Si no hay usuario autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si no se especifican roles en la definición del middleware, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Normalizar roles requeridos (acepta "admin,estudiante" o "admin|estudiante")
        $required = preg_split('/[\|,]+/', $roles);
        $required = array_map(fn($r) => strtolower(trim($r)), $required);

        // Si el usuario tiene método hasRole (spatie u otros), usarlo directamente
        if (is_object($user) && method_exists($user, 'hasRole')) {
            foreach ($required as $req) {
                // hasRole suele aceptar nombre, array o colección
                try {
                    if ($user->hasRole($req)) {
                        return $next($request);
                    }
                } catch (\Throwable $e) {
                    // ignorar y continuar con otras comprobaciones
                }
            }
        }

        // Recolectar roles del usuario en formato array (case-insensitive)
        $userRoles = [];

        // Campos habituales string: role, rol, tipo, role_name, roleName, nombre_rol
        $possibleFields = ['role','rol','tipo','role_name','roleName','nombre_rol','rol_name'];
        foreach ($possibleFields as $field) {
            if (isset($user->{$field})) {
                $value = $user->{$field};
                if (is_string($value)) {
                    $userRoles[] = strtolower($value);
                } elseif (is_array($value)) {
                    $userRoles = array_merge($userRoles, array_map('strtolower', $value));
                }
            }
        }

        // Relación roles (collection) -> intentar pluck por 'name' o 'rol'
        if (isset($user->roles)) {
            try {
                if (method_exists($user->roles, 'pluck')) {
                    // intenta varios campos comunes
                    $names = $user->roles->pluck('name')->toArray();
                    if (empty($names)) {
                        $names = $user->roles->pluck('rol')->toArray();
                    }
                    $userRoles = array_merge($userRoles, array_map('strtolower', $names));
                }
            } catch (\Throwable $e) {
                // ignorar
            }
        }

        // Si el modelo trae getRoleNames (spatie)
        if (method_exists($user, 'getRoleNames')) {
            try {
                $names = $user->getRoleNames()->map(fn($n) => strtolower($n))->toArray();
                $userRoles = array_merge($userRoles, $names);
            } catch (\Throwable $e) {}
        }

        // Flag booleano is_admin / isAdmin
        if ((isset($user->is_admin) && ($user->is_admin == 1 || $user->is_admin === true))
            || (isset($user->isAdmin) && ($user->isAdmin == 1 || $user->isAdmin === true))
        ) {
            $userRoles[] = 'admin';
        }

        // Si existe un campo role_id y no hay mapping, opcionalmente podríamos mapearlo aquí (si se indica).
        if (isset($user->role_id) && empty($userRoles)) {
            // fallback: incluir role_id como string para posibles comparaciones
            $userRoles[] = (string) $user->role_id;
        }

        // Map de aliases para cubrir variaciones comunes
        $roleAliases = [
            'admin' => ['admin', 'administrador', 'superadmin', 'root', 'owner'],
            'estudiante' => ['estudiante', 'student', 'usuario', 'user'],
            // agregar más si es necesario
        ];

        // Expandir roles del usuario con aliases conocidos
        $expandedUserRoles = array_unique(array_map('strtolower', $userRoles));
        foreach ($expandedUserRoles as $ur) {
            foreach ($roleAliases as $key => $aliases) {
                if (in_array($ur, $aliases)) {
                    $expandedUserRoles = array_merge($expandedUserRoles, $aliases);
                }
            }
        }
        $expandedUserRoles = array_unique(array_map('strtolower', $expandedUserRoles));

        // Verificar intersección entre requeridos y roles del usuario
        $ok = count(array_intersect($expandedUserRoles, $required)) > 0;

        if (!$ok) {
            // Loguear para diagnóstico: qué roles detectó y cuáles se requerían
            try {
                Log::warning('RoleMiddleware: acceso denegado', [
                    'user_id' => $user->id ?? null,
                    'user_roles_detected' => $expandedUserRoles,
                    'roles_required' => $required,
                    'route' => $request->route()?->getName()
                ]);
            } catch (\Throwable $e) {
                // ignorar fallos de logging
            }

            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
