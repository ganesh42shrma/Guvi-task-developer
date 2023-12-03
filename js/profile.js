$(document).ready(function () {
    // Event listener for form submission
    $('form').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Gather form data
        var formData = {
            name: $("#name").val(),
            gender: $("#gender").val(),
            age: $("#age").val(),
            dob: $("#dob").val(),
            contact: $("#contact").val(),
            bio: $("#bio").val()
        };

        // Use jQuery AJAX to send data to the server
        $.ajax({
            type: "POST",
            url: "profile_update.php", // Corrected file name
            data: formData,
            success: function (response) {
                // Handle the response from the server
                console.log(response);

                if (response.status === 'success') {
                    alert("Profile updated successfully");

                    // After successful update, fetch and display user data
                    fetchUserData();
                } else {
                    alert("Failed to update profile: " + response.message);
                }
            },
            error: function (error) {
                // Handle error from the server
                console.error("Error:", error);
                alert("Failed to update profile. Please try again.");
            }
        });
    });

    // Fetch user data for preview on page load
    fetchUserData();

    function fetchUserData() {
        $.ajax({
            type: "GET",
            url: "profile_get.php", // Corrected file name
            success: function (response) {
                // Handle the response from the server
                console.log(response);

                // Display the user data in the preview div
                displayUserData(response);
            },
            error: function (error) {
                // Handle error from the server
                console.error("Error:", error);
            }
        });
    }

    function displayUserData(userData) {
        var previewDiv = $('#profile-preview');

        // Example: Display username and email
        previewDiv.html('<p><strong>Name:</strong> ' + userData.name + '</p>');
        previewDiv.append('<p><strong>Gender:</strong> ' + userData.gender + '</p>');
        previewDiv.append('<p><strong>Age:</strong> ' + userData.age + '</p>');
        previewDiv.append('<p><strong>Date of Birth:</strong> ' + userData.dob + '</p>');
        previewDiv.append('<p><strong>Contact:</strong> ' + userData.contact + '</p>');
        previewDiv.append('<p><strong>Bio:</strong> ' + userData.bio + '</p>');
        // Add more information as needed
    }
});
