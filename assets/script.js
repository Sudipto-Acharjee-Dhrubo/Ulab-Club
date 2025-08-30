// Simple client-side validation for register form
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const name = form.querySelector('#name');
    const email = form.querySelector('#email');
    const password = form.querySelector('#password');
    const club = form.querySelector('#club_id');

    const errors = [];
    if (!name.value.trim()) errors.push('Name is required');
    if (!email.value.trim() || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value)) errors.push('A valid email is required');
    if (!password.value || password.value.length < 6) errors.push('Password must be at least 6 characters');
    if (!club.value) errors.push('Please select a club');

    if (errors.length) {
      e.preventDefault();
      alert(errors.join('\n'));
    }
  });
});
