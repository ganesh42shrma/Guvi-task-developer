$(document).ready(function () {
    $("#signup-form").submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Gather form data
        var formData = {
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            username: $("#username").val(),
            email: $("#email").val(),
            password: $("#password").val()
        };

        // Perform client-side validation
        if (!validateForm(formData)) {
            return; // Do not proceed if validation fails
        }

        // Use jQuery AJAX to send data to the server
        $.ajax({
            type: "POST",
            url: "../php/register.php",
            data: formData,
            success: function (response) {
                // Handle successful response from the server
                console.log(response);

                if (response.status === 'success') {
                    // Handle the success message (e.g., show a success message to the user)
                    alert("Registration successful!");

                    // Redirect to login.html directly
                    console.log('Redirecting to login.html');
                    window.location.href = "../php/login.html";
                } else {
                    // Handle other response scenarios if needed
                    alert("Registration failed: " + response.message);
                }
            },
            error: function (error) {
                // Handle error from the server
                console.error("Error:", error);

                if (error.responseJSON && error.responseJSON.status === 'error' && error.responseJSON.message === 'Username already taken') {
                    alert("Registration failed: Username already taken");
                } else {
                    alert("Registration failed. Please try again.");
                }
            }
        });
    });

    // Redirect to login.html when "Sign In" link is clicked
    $("a[href='#']").click(function (event) {
        event.preventDefault();
        window.location.href = "../php/login.html";
    });
});

function validateForm(formData) {
    // Your validation code here
    return true; // Return true for testing purposes
}
