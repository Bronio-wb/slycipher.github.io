function readJsonScript(id) {
  const element = document.getElementById(id);
  if (!element) return [];
  try {
    return JSON.parse(element.textContent);
  } catch {
    return [];
  }
}

function buildGradient(context, colorStart, colorEnd) {
  const chart = context.chart;
  const { ctx, chartArea } = chart;
  if (!chartArea) {
    return colorStart;
  }
  const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
  gradient.addColorStop(0, colorStart);
  gradient.addColorStop(1, colorEnd);
  return gradient;
}

function buildChart(canvasId, type, labels, values, label, colors) {
  const canvas = document.getElementById(canvasId);
  if (!canvas || !labels.length || !values.length || typeof Chart === 'undefined') {
    return;
  }

  const isLine = type === 'line';
  const isDoughnut = type === 'doughnut';
  const isBar = type === 'bar';
  const backgroundColor = isLine
    ? (context) => buildGradient(context, 'rgba(37, 99, 235, 0.35)', 'rgba(37, 99, 235, 0.05)')
    : colors;
  const borderColor = isLine ? '#2563eb' : colors;

  // eslint-disable-next-line no-new
  new Chart(canvas, {
    type,
    data: {
      labels,
      datasets: [{
        label,
        data: values,
        backgroundColor,
        borderColor,
        borderWidth: isLine ? 3 : isBar ? 0 : 1,
        borderRadius: isBar ? 12 : 0,
        borderSkipped: false,
        hoverOffset: isDoughnut ? 8 : 0,
        pointRadius: isLine ? 4 : 0,
        pointHoverRadius: isLine ? 6 : 0,
        pointBackgroundColor: isLine ? '#ffffff' : undefined,
        pointBorderColor: isLine ? '#f97316' : undefined,
        pointBorderWidth: isLine ? 3 : 0,
        tension: 0.35,
        fill: isLine,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: 8,
      },
      plugins: {
        legend: {
          display: isDoughnut,
          position: 'bottom',
          labels: {
            usePointStyle: true,
            boxWidth: 10,
            padding: 18,
            font: {
              size: 12,
              weight: '600',
            },
          },
        },
        tooltip: {
          backgroundColor: 'rgba(30, 58, 138, 0.94)',
          titleColor: '#ffffff',
          bodyColor: '#eff6ff',
          borderColor: 'rgba(147, 197, 253, 0.45)',
          borderWidth: 1,
          padding: 12,
          displayColors: true,
          cornerRadius: 12,
        },
      },
      scales: isDoughnut ? {} : {
        x: {
          grid: {
            display: false,
          },
          ticks: {
            color: '#1e40af',
            font: {
              size: 11,
              weight: '600',
            },
          },
        },
        y: {
          beginAtZero: true,
          grace: '10%',
          ticks: {
            precision: 0,
            color: '#1e40af',
            font: {
              size: 11,
            },
          },
          grid: {
            color: 'rgba(147, 197, 253, 0.35)',
            drawBorder: false,
          },
        },
      },
    },
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // Datos originales
  const categoryLabels = readJsonScript('stats-category-labels');
  const categoryValues = readJsonScript('stats-category-values');
  const languageLabels = readJsonScript('stats-language-labels');
  const languageValues = readJsonScript('stats-language-values');
  const usersLabels = readJsonScript('stats-users-labels');
  const usersValues = readJsonScript('stats-users-values');

  // Nuevos datos
  const nivelData = readJsonScript('stats-nivel-data');
  const tendenciaData = readJsonScript('stats-tendencia-data');
  const segmentacionData = readJsonScript('stats-segmentacion-data');

  // Paletas de colores
  const languageColorMap = languageLabels.map((rawLabel, index) => {
    const label = String(rawLabel || '').trim().toLowerCase();

    if (label.includes('javascript') || label === 'js' || label.includes('java script')) {
      return '#F7DF1E';
    }
    if (label.includes('python')) {
      return '#3776AB';
    }
    if (label.includes('java')) {
      return '#f89820';
    }

    const fallbackPalette = ['#1e40af', '#2563eb', '#3b82f6', '#60a5fa', '#f97316', '#fdba74'];
    return fallbackPalette[index % fallbackPalette.length];
  });

  const nivelColorMap = ['#10b981', '#f59e0b', '#ef4444'];
  const segmentacionColorMap = ['#3b82f6', '#8b5cf6', '#ec4899', '#6b7280'];

  // Gráfico de categorías
  buildChart(
    'categoryChart',
    'bar',
    categoryLabels,
    categoryValues,
    'Cursos por categoría',
    categoryLabels.map((_, index) => {
      const categoryPalette = ['#60a5fa', '#1f2f46', '#f97316'];
      return categoryPalette[index % categoryPalette.length];
    }),
  );

  // Gráfico de lenguajes
  buildChart(
    'languageChart',
    'doughnut',
    languageLabels,
    languageValues,
    'Cursos por lenguaje',
    languageColorMap,
  );

  // Gráfico de usuarios por mes
  buildChart(
    'usersChart',
    'line',
    usersLabels,
    usersValues,
    'Usuarios por mes',
    ['#2563eb'],
  );

  // Gráfico de nivel de cursos
  if (nivelData && nivelData.length > 0) {
    const nivelLabels = nivelData.map(item => item.nivel);
    const nivelValues = nivelData.map(item => item.cantidad);
    buildChart(
      'nivelChart',
      'doughnut',
      nivelLabels,
      nivelValues,
      'Distribución por nivel',
      nivelColorMap,
    );
  }

  // Gráfico de segmentación de usuarios
  if (segmentacionData && segmentacionData.labels) {
    buildChart(
      'segmentacionChart',
      'doughnut',
      segmentacionData.labels,
      segmentacionData.values,
      'Segmentación de usuarios',
      segmentacionColorMap,
    );
  }

  // Gráfico de tendencia 30 días
  if (tendenciaData && tendenciaData.length > 0) {
    const tendenciaLabels = tendenciaData.map(item => item.fecha);
    const tendenciaValues = tendenciaData.map(item => item.registros);
    buildChart(
      'tendenciaChart',
      'line',
      tendenciaLabels,
      tendenciaValues,
      'Registros de últimos 30 días',
      ['#10b981'],
    );
  }
});
