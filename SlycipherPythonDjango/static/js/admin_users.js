function getCookie(name) {
  let cookieValue = null;
  if (document.cookie && document.cookie !== '') {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      if (cookie.substring(0, name.length + 1) === (name + '=')) {
        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
        break;
      }
    }
  }
  return cookieValue;
}

function updateQuery(params) {
  const url = new URL(window.location.href);
  Object.keys(params).forEach(k => {
    const v = params[k];
    if (v === null || v === undefined || v === '') url.searchParams.delete(k);
    else url.searchParams.set(k, v);
  });
  window.location = url.toString();
}

function debounce(fn, wait) {
  let t;
  return function(...args){
    clearTimeout(t);
    t = setTimeout(()=>fn.apply(this,args), wait);
  }
}

document.addEventListener('DOMContentLoaded', function(){
  // soporta tanto los ids nuevos como los ids legacy presentes en las plantillas
  const searchEl = document.getElementById('user-search') || document.getElementById('search');
  if (searchEl) {
    searchEl.addEventListener('input', debounce(function(e){
      const q = e.target.value || '';
      // recopilar todos los selects de filtro con name y clase select-filter
      const selects = document.querySelectorAll('.select-filter');
      const params = { q: q };
      selects.forEach(s => {
        if (s.name) params[s.name] = s.value || '';
      });
      updateQuery(params);
    }, 350));
  }

  // escuchar cambios en cualquier select-filter para actualizar la consulta
  document.querySelectorAll('.select-filter').forEach(sel => {
    sel.addEventListener('change', function(e){
      const selects = document.querySelectorAll('.select-filter');
      const params = {};
      const qv = (document.getElementById('user-search') || document.getElementById('search'))?.value || '';
      if (qv) params.q = qv;
      selects.forEach(s => { if (s.name) params[s.name] = s.value || ''; });
      updateQuery(params);
    });
  });

  document.querySelectorAll('.status-btn').forEach(btn => {
    btn.addEventListener('click', function(e){
      if (btn.disabled) return;
      const url = btn.getAttribute('data-url');
      if (!url) return;
      const csrftoken = getCookie('csrftoken');
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRFToken': csrftoken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      }).then(async r => {
        let data = {};
        try { data = await r.json(); } catch (_) {}
        if (!r.ok || (data && data.ok === false)) {
          const message = (data && data.message) ? data.message : 'No se pudo cambiar el estado del usuario.';
          window.alert(message);
          return;
        }
        if (data) {
          if (data.is_active) {
            btn.classList.remove('status-inactive');
            btn.classList.add('status-active');
            btn.textContent = 'Activo';
          } else {
            btn.classList.remove('status-active');
            btn.classList.add('status-inactive');
            btn.textContent = 'Inactivo';
          }
        }
      }).catch(() => { window.location.reload(); });
    });
  });

  // confirmación para formularios de eliminación en la tabla de usuarios
  document.querySelectorAll('.actions form').forEach(form => {
    form.addEventListener('submit', function(e){
      const name = form.getAttribute('data-username') || 'el usuario';
      const ok = window.confirm(`¿Estás seguro de eliminar este usuario: ${name}?`);
      if (!ok) {
        e.preventDefault();
      }
    });
  });
});
