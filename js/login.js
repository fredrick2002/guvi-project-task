$(document).ready(function(){
    $('#login').submit(function(e){
        e.preventDefault(); // Prevent form submission

        // Get form data
        var email = $('#email').val();
        var pwd = $('#pwd').val();

        // Post data to PHP script
        $.ajax({
            type: 'POST',
            url: './php/login.php', // Replace 'login.php' with your PHP script path
            data: {
                email: email,
                pwd: pwd
            },
            success: function(response){
                // Handle successful login response
                console.log(response); // You can log or display the response
                if (response.status.trim() === "Login successful!") {
                    // Redirect to profile.html if login is successful
                    var objectId = response.objectId;
                    localStorage.setItem('objectId', objectId);
                    window.location.href = "profile.html";
                } else {
                    // Handle unsuccessful login
                    alert("Invalid email or password!");
                }
            },
            error: function(xhr, status, error){
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
});
