/* strict */
$(document).ready(function() {
    $('#imageSlider').carousel();
  });
  
  $(document).ready(function() {
  // Event listener for Sign Up button
  $('#btn-register').on('click', function() {
    window.location.href = '../php/register.html'; // Replace with the actual path to your sign-up page
  });
  
  // Event listener for Log In button
  $('#btn-login').on('click', function() {
    window.location.href = '../php/login.html'; // Replace with the actual path to your login page
  });
  });
  