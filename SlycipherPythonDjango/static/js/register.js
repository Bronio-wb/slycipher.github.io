document.addEventListener('DOMContentLoaded', function(){
  const pwd = document.getElementById('password');
  const email = document.getElementById('email');
  const submit = document.querySelector('.btn-submit');
  const rules = {
    length: v => v.length >= 8 && v.length <= 20,
    upper: v => /[A-Z]/.test(v),
    lower: v => /[a-z]/.test(v),
    digit: v => /\d/.test(v),
    symbol: v => /[@$!%*?&]/.test(v)
  };

  function updateRules(){
    const v = pwd ? pwd.value : '';
    const items = document.querySelectorAll('.password-hints li[data-rule]');
    let allOk = true;
    items.forEach(li => {
      const name = li.getAttribute('data-rule');
      const ok = !!(rules[name] && rules[name](v));
      li.classList.toggle('ok', ok);
      li.classList.toggle('bad', !ok);
      if(!ok) allOk = false;
    });

    // el correo debe contener @
    const emailOk = email ? /@/.test(email.value) : false;
    const emailInline = document.getElementById('email-inline-error');
    if(emailInline){
      if(email && email.value && !emailOk){
        emailInline.style.display = 'block';
        emailInline.textContent = 'El correo debe contener @';
      } else {
        emailInline.style.display = 'none';
        emailInline.textContent = '';
      }
    }

    if(submit){
      submit.disabled = !(allOk && emailOk);
    }
  }

  if(pwd){
    pwd.addEventListener('input', updateRules);
  }
  if(email){
    email.addEventListener('input', updateRules);
  }
  // ejecutar una vez al cargar
  updateRules();

  // alternadores de visibilidad de contraseña (ícono SVG sutil)
  document.querySelectorAll('.password-toggle').forEach(btn => {
    btn.addEventListener('click', function(){
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if(!input) return;
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.classList.toggle('visible', isHidden);
    });
  });
});
