document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('login-form');
  form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (validateForm()) {
          submitForm();
      }
  });

  function validateForm() {
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;

      if (username.trim() === '') {
          alert('Username is required');
          return false;
      }

      if (password.trim() === '') {
          alert('Password is required');
          return false;
      }

      return true;
  }

  function submitForm() {
      const formData = new FormData(form);

      fetch('/php/login.php', {
          method: 'POST',
          body: formData,
      })
          .then(response => response.json())
          .then(data => {
              if (data.status === 'success') {
                  alert(data.message);

                  // Store the token in localStorage
                  localStorage.setItem('token', data.token);

                  // Redirect to the desired page
                  window.location.href = '/php/profile.html';
              } else {
                  alert(data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
  }

  // Handle click event on the "Sign Up" link
  const signUpLink = document.querySelector('a[href="/php/register.html"]');
  signUpLink.addEventListener('click', function (event) {
      event.preventDefault();
      // Redirect to register.html
      window.location.href = '/php/register.html';
  });
});
