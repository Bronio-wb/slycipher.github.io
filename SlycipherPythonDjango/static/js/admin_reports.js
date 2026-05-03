(function () {
  const dateEl = document.getElementById('fecha-actual');
  if (dateEl) {
    const now = new Date();
    dateEl.textContent = now.toLocaleString('es-ES', {
      day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
  }

  const maxDate = new Date().toISOString().split('T')[0];
  const fromInput = document.getElementById('fechaDesde');
  const toInput = document.getElementById('fechaHasta');
  if (fromInput) fromInput.max = maxDate;
  if (toInput) toInput.max = maxDate;

  const formSection = document.getElementById('generator-form');
  const cardButtons = document.querySelectorAll('.report-card[data-tipo]');
  const radios = document.querySelectorAll('input[name="tipoReporte"]');

  function selectTipo(tipo) {
    radios.forEach((radio) => {
      const label = radio.closest('.radio-item');
      const active = radio.value === tipo;
      radio.checked = active;
      if (label) {
        label.style.borderColor = active ? '#2563eb' : '#e5e7eb';
        label.style.background = active ? '#eff6ff' : '#fff';
      }
    });
  }

  cardButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const tipo = btn.getAttribute('data-tipo');
      if (formSection) {
        formSection.classList.remove('hidden');
        formSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      selectTipo(tipo);
    });
  });

  radios.forEach((radio) => {
    radio.addEventListener('change', () => selectTipo(radio.value));
  });
})();