@extends('layouts.app')

@section('title', 'Perfil')
@section('content')
<div class="profile-panel container">

    <div class="profile-card">
        <img id="avatarPreview" src="{{ auth()->user()->avatar_url ?? asset('img/Logo.jpeg') }}" alt="Avatar" class="profile-avatar">
        <div class="small-note">Haz click en "Seleccionar avatar" para cambiar tu foto.</div>

        <div class="profile-actions mt-3">
            <label class="btn btn-outline-secondary" style="cursor:pointer;">
                Seleccionar avatar
                <input id="avatarInput" type="file" accept="image/*" style="display:none;">
            </label>
            <button id="removeAvatarBtn" class="btn btn-outline-danger">Eliminar</button>
        </div>

        <div class="small-note mt-3">Vista previa y preferencias rápidas.</div>
    </div>

    <div class="profile-form">
        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-field">
                <label for="name">Nombre visible</label>
                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}">
            </div>

            <div class="profile-field">
                <label for="email">Correo (no editable)</label>
                <input id="email" name="email" type="email" value="{{ auth()->user()->email }}" readonly>
            </div>

            <div class="profile-field">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
            </div>

            <div class="profile-field toggle-row">
                <div>
                    <strong>Tema</strong>
                    <div class="small-note">Elige claro u oscuro</div>
                </div>
                <div>
                    <select id="themeSelect" name="theme" style="padding:8px 10px;border-radius:8px;">
                        <option value="light" {{ (auth()->user()->theme ?? 'light') === 'light' ? 'selected' : '' }}>Claro</option>
                        <option value="dark" {{ (auth()->user()->theme ?? '') === 'dark' ? 'selected' : '' }}>Oscuro</option>
                    </select>
                </div>
            </div>

            <div class="profile-field toggle-row" style="margin-top:12px;">
                <div>
                    <strong>Tamaño editor</strong>
                    <div class="small-note">Ajusta tus preferencias de editor</div>
                </div>
                <div>
                    <select id="editorSize" name="editor_size" style="padding:8px 10px;border-radius:8px;">
                        <option value="12">12px</option>
                        <option value="14" selected>14px</option>
                        <option value="16">16px</option>
                        <option value="18">18px</option>
                    </select>
                </div>
            </div>

            <div class="profile-field toggle-row" style="margin-top:12px;">
                <div>
                    <strong>Idioma</strong>
                    <div class="small-note">Preferencia de idioma UI</div>
                </div>
                <div>
                    <select id="langSelect" name="language" style="padding:8px 10px;border-radius:8px;">
                        <option value="es" {{ (auth()->user()->language ?? 'es') === 'es' ? 'selected' : '' }}>Español</option>
                        <option value="en" {{ (auth()->user()->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
            </div>

            <div class="profile-field" style="margin-top:14px;">
                <label class="small-note">Notificaciones</label>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <label><input type="checkbox" id="notifEmail" name="notif_email" {{ (auth()->user()->notif_email ?? true) ? 'checked' : '' }}> Correo</label>
                    <label><input type="checkbox" id="notifInApp" name="notif_inapp" {{ (auth()->user()->notif_inapp ?? true) ? 'checked' : '' }}> In-app</label>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:18px;align-items:center;">
                <button type="submit" class="save-profile-btn">Guardar cambios</button>
                <button type="button" id="saveLocalBtn" class="save-local-btn">Guardar local</button>
                <button type="button" id="deleteAccountBtn" class="danger-btn ms-auto">Eliminar cuenta</button>
            </div>

            <!-- input oculto para avatar -->
            <input type="hidden" name="avatar_data" id="avatarData">

        </form>

        <p class="small-note mt-3">Tus preferencias se aplicarán inmediatamente en la interfaz cuando uses "Guardar local".</p>
    </div>
</div>

<!-- Modal confirmación eliminar cuenta -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <p class="fw-bold">¿Confirmas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
            <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarData = document.getElementById('avatarData');
    const saveLocalBtn = document.getElementById('saveLocalBtn');
    const themeSelect = document.getElementById('themeSelect');
    const editorSize = document.getElementById('editorSize');
    const langSelect = document.getElementById('langSelect');

    const currentId = '{{ auth()->user()->id }}';
    const storageKey = 'slycipher_profile_' + currentId;

    // Previsualizar avatar
    avatarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            avatarPreview.src = e.target.result;
            avatarData.value = e.target.result; // base64 for backend if needed
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('removeAvatarBtn').addEventListener('click', function () {
        avatarPreview.src = "{{ asset('img/Logo.jpeg') }}";
        avatarData.value = '';
        // optional: mark for backend removal
    });

    // Guardar preferencias en localStorage
    saveLocalBtn.addEventListener('click', function () {
        const payload = {
            name: document.getElementById('name').value,
            bio: document.getElementById('bio').value,
            theme: themeSelect.value,
            editor_size: editorSize.value,
            language: langSelect.value,
            notif_email: document.getElementById('notifEmail').checked,
            notif_inapp: document.getElementById('notifInApp').checked,
            avatar_preview: avatarPreview.src
        };
        localStorage.setItem(storageKey, JSON.stringify(payload));
        applyPreferences(payload);
        alert('Preferencias guardadas localmente.');
    });

    // Aplicar preferencias visualmente
    function applyPreferences(p) {
        if (!p) return;
        // tema
        if (p.theme === 'dark') document.body.classList.add('dark-theme');
        else document.body.classList.remove('dark-theme');

        // editor size: guardar en localStorage y emitir evento (si editor presente)
        if (p.editor_size) localStorage.setItem('slycipher_editor_fontsize', p.editor_size);

        // idioma - aplicar solo local UI hint (no cambio global de backend)
        // avatar already applied via preview
    }

    // Cargar preferencias guardadas localmente
    (function loadLocal() {
        const raw = localStorage.getItem(storageKey);
        if (!raw) return;
        try {
            const p = JSON.parse(raw);
            document.getElementById('name').value = p.name ?? document.getElementById('name').value;
            document.getElementById('bio').value = p.bio ?? document.getElementById('bio').value;
            themeSelect.value = p.theme ?? themeSelect.value;
            editorSize.value = p.editor_size ?? editorSize.value;
            langSelect.value = p.language ?? langSelect.value;
            document.getElementById('notifEmail').checked = p.notif_email ?? document.getElementById('notifEmail').checked;
            document.getElementById('notifInApp').checked = p.notif_inapp ?? document.getElementById('notifInApp').checked;
            if (p.avatar_preview) avatarPreview.src = p.avatar_preview;
            applyPreferences(p);
        } catch(e){}
    })();

    // eliminar cuenta modal trigger
    document.getElementById('deleteAccountBtn').addEventListener('click', function () {
        const bsModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        bsModal.show();
    });
});
</script>
@endpush
