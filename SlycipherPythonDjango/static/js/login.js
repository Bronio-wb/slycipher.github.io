document.addEventListener('DOMContentLoaded', function(){
  // adjuntar manejador a cualquier botón de alternar contraseña (estado SVG sutil)
  document.querySelectorAll('.password-toggle').forEach(btn=>{
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const target = btn.getAttribute('data-target');
      const input = document.getElementById(target);
      if(!input) return;
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.classList.toggle('visible', show);
    });
  });
});
