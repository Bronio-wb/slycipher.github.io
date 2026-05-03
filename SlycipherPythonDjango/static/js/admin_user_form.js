document.addEventListener('DOMContentLoaded', function(){
  try{
    const form = document.querySelector('form');
    if(!form) return;

    // enlazar toggles para cada .pwd-wrapper
    form.querySelectorAll('.pwd-wrapper').forEach(wrapper=>{
      // preferir data-target explícito si está presente (coincide con el id automático de Django), respaldo al primer campo de contraseña
      const toggle = wrapper.querySelector('.pwd-toggle');
      let pwd = null;
      if(toggle && toggle.dataset && toggle.dataset.target){
        pwd = document.getElementById(toggle.dataset.target) || wrapper.querySelector('input[type="password"]');
      } else {
        pwd = wrapper.querySelector('input[type="password"]');
      }
      if(!pwd || !toggle) return;
      toggle.addEventListener('click', function(){
        const show = pwd.type === 'password';
        pwd.type = show ? 'text' : 'password';
        toggle.classList.toggle('visible', show);
      });
    });

    // UI de reglas de contraseña -- apuntar específicamente al input "Nueva Contraseña"
    const rulesList = document.getElementById('pwd-rules');
    const ruleItems = {};
    if(rulesList){
      rulesList.querySelectorAll('li').forEach(li=>{ ruleItems[li.getAttribute('data-rule')] = li; });
    }

    // intentar encontrar el campo de nueva contraseña por el nombre 'password'
    const newPwd = form.querySelector('input[name="password"]');
    function checkPwd(val){
      const s = val || '';
      const okLength = s.length >=8 && s.length <=20;
      const okUpper = /[A-Z]/.test(s);
      const okLower = /[a-z]/.test(s);
      const okDigit = /\d/.test(s);
      const okSymbol = /[@$!%*?&]/.test(s);

      if(ruleItems['length']){ ruleItems['length'].textContent = (okLength? '✓ ':'✕ ') + '8-20 caracteres'; ruleItems['length'].style.color = okLength? '#10b981':'#b91c1c'; }
      if(ruleItems['upper']){ ruleItems['upper'].textContent = (okUpper? '✓ ':'✕ ') + 'Una letra mayúscula'; ruleItems['upper'].style.color = okUpper? '#10b981':'#b91c1c'; }
      if(ruleItems['lower']){ ruleItems['lower'].textContent = (okLower? '✓ ':'✕ ') + 'Una letra minúscula'; ruleItems['lower'].style.color = okLower? '#10b981':'#b91c1c'; }
      if(ruleItems['digit']){ ruleItems['digit'].textContent = (okDigit? '✓ ':'✕ ') + 'Un número'; ruleItems['digit'].style.color = okDigit? '#10b981':'#b91c1c'; }
      if(ruleItems['symbol']){ ruleItems['symbol'].textContent = (okSymbol? '✓ ':'✕ ') + 'Un símbolo (@$!%*?&)'; ruleItems['symbol'].style.color = okSymbol? '#10b981':'#b91c1c'; }
    }

    if(newPwd){ checkPwd(newPwd.value); newPwd.addEventListener('input', function(e){ checkPwd(e.target.value); }); }
  }catch(e){console.error(e)}
});
