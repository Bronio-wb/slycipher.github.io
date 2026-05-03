document.addEventListener('DOMContentLoaded', () => {
  const titleInput = document.getElementById('titulo');
  if (titleInput && !titleInput.value) {
    titleInput.focus();
  }

  const deleteForm = document.querySelector('.delete-course-form');
  if (deleteForm) {
    deleteForm.addEventListener('submit', (event) => {
      const confirmed = window.confirm('¿Estás seguro de que deseas eliminar este curso?');
      if (!confirmed) {
        event.preventDefault();
      }
    });
  }
});
