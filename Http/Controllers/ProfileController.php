<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // Validaciones básicas
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'bio'  => 'nullable|string|max:2000',
            'theme' => 'nullable|string|in:light,dark',
            'editor_size' => 'nullable|numeric',
            'language' => 'nullable|string|max:8',
            'notif_email' => 'nullable|boolean',
            'notif_inapp' => 'nullable|boolean',
            'avatar_data' => 'nullable|string', // base64
        ]);

        // Guardar avatar si viene base64
        if (!empty($data['avatar_data'])) {
            // extraer base64 (data:image/...)
            if (preg_match('/^data:image\/(\w+);base64,/', $data['avatar_data'], $type)) {
                $imageData = substr($data['avatar_data'], strpos($data['avatar_data'], ',') + 1);
                $imageData = base64_decode($imageData);
                $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
                $path = 'public/avatars/user_' . ($user->id ?? time()) . '.' . $ext;
                Storage::put($path, $imageData);
                $publicPath = Storage::url(str_replace('public/', '', $path));
                // si existe columna avatar_url o avatar, actualizarla
                if (Schema::hasColumn('users', 'avatar_url')) {
                    $user->avatar_url = $publicPath;
                } elseif (Schema::hasColumn('users', 'avatar')) {
                    $user->avatar = $publicPath;
                }
            }
        }

        // Guardar campos sólo si existen en la tabla
        if (!empty($data['name'])) {
            if (Schema::hasColumn('users', 'name')) $user->name = $data['name'];
        }
        if (array_key_exists('bio', $data) && Schema::hasColumn('users', 'bio')) {
            $user->bio = $data['bio'];
        }
        if (array_key_exists('theme', $data) && Schema::hasColumn('users', 'theme')) {
            $user->theme = $data['theme'];
        }
        if (array_key_exists('editor_size', $data) && Schema::hasColumn('users', 'editor_size')) {
            $user->editor_size = $data['editor_size'];
        }
        if (array_key_exists('language', $data) && Schema::hasColumn('users', 'language')) {
            $user->language = $data['language'];
        }
        if (array_key_exists('notif_email', $data) && Schema::hasColumn('users', 'notif_email')) {
            $user->notif_email = (bool)$data['notif_email'];
        }
        if (array_key_exists('notif_inapp', $data) && Schema::hasColumn('users', 'notif_inapp')) {
            $user->notif_inapp = (bool)$data['notif_inapp'];
        }

        $user->save();

        return redirect()->back()->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        // opcional: eliminar avatar público
        if (isset($user->avatar_url)) {
            try {
                $file = str_replace('/storage/', 'public/', $user->avatar_url);
                Storage::delete($file);
            } catch (\Throwable $e) {}
        }
        Auth::logout();
        // eliminar registro (si soporta)
        try { $user->delete(); } catch (\Throwable $e) {}
        return redirect('/')->with('status', 'Cuenta eliminada.');
    }
}
