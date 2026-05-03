function updateQuery(params) {
  const url = new URL(window.location.href);
  Object.keys(params).forEach((key) => {
    const value = params[key];
    if (value === null || value === undefined || value === '') {
      url.searchParams.delete(key);
    } else {
      url.searchParams.set(key, value);
    }
  });
  window.location = url.toString();
}

function getCookie(name) {
  let cookieValue = null;
  if (document.cookie && document.cookie !== '') {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i += 1) {
      const cookie = cookies[i].trim();
      if (cookie.substring(0, name.length + 1) === `${name}=`) {
        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
        break;
      }
    }
  }
  return cookieValue;
}

function debounce(fn, wait) {
  let timeoutId;
  return function debounced(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), wait);
  };
}

document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('search');
  const filterSelects = document.querySelectorAll('.select-filter');

  if (searchInput) {
    searchInput.addEventListener(
      'input',
      debounce((event) => {
        const params = { q: event.target.value || '' };
        filterSelects.forEach((selectElement) => {
          if (selectElement.name) {
            params[selectElement.name] = selectElement.value || '';
          }
        });
        updateQuery(params);
      }, 350),
    );
  }

  filterSelects.forEach((selectElement) => {
    selectElement.addEventListener('change', () => {
      const params = { q: searchInput ? searchInput.value || '' : '' };
      filterSelects.forEach((innerSelect) => {
        if (innerSelect.name) {
          params[innerSelect.name] = innerSelect.value || '';
        }
      });
      updateQuery(params);
    });
  });

  const csrfToken = getCookie('csrftoken');
  document.querySelectorAll('.visibility-toggle[data-url]').forEach((toggleButton) => {
    toggleButton.addEventListener('click', async () => {
      const endpoint = toggleButton.getAttribute('data-url');
      if (!endpoint) return;

      toggleButton.disabled = true;
      try {
        const response = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'X-CSRFToken': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
          },
        });

        const result = await response.json();
        if (response.ok && result && result.ok) {
          window.location.reload();
          return;
        }
      } catch (error) {
        // respaldo abajo
      }

      window.location.reload();
    });
  });
});
