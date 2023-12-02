$(document).ready(function() {
  // Get the profile form
  const profileForm = $('#profileForm');

  // Handle the form submission event
  profileForm.submit(function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the form data
    const formData = profileForm.serialize();

    // Make an AJAX request to submit the form data and get the preview of the profile details
    $.ajax({
      url: '/profile/update',
      method: 'POST',
      data: formData,
      success: function(response) {
        // Display the preview of the profile details
        $('#profile-preview').html(response);
      }
    });
  });
});
