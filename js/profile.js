
$(document).ready(function() {
    
    var objectId = localStorage.getItem("objectId");
    var isLoggedIn = checkLoginStatus();
    // If not logged in, display warning and redirect to login page
    if (!isLoggedIn) {
        alert("You need to log in to access your profile.");
        window.location.href = "login.html"; // Change to your login page URL
    }

    // Function to check login status (replace with your actual login check logic)
    function checkLoginStatus() {
        if(objectId){
            return true
        }else{
        return false;
        } 
    }


    // Check if the ObjectId is present
    if (objectId) {
        // Send a POST request to your PHP script
        $.post("./php/profile.php", { objectId: objectId }, function(response) {
            // Handle the response from the server
            console.log(response);
            // You can parse the response JSON if needed
            var redisData = JSON.parse(response);
            // Do something with the data
            // For example, display it on the webpage
            $("#first_name").val(redisData.first_name);
            $("#last_name").val(redisData.last_name);
            $("#email").val(redisData.email);
            $("#dob").val(redisData.dob);
            $("#gender").val(redisData.gender);
            $("#phone").val(redisData.phone);
            $("#street_name").val(redisData.street_name);
            $("#city").val(redisData.city);
            $("#state").val(redisData.state);
            $("#pincode").val(redisData.pincode);
            
            
        }).fail(function(xhr, status, error) {
            // Handle any errors that occur during the AJAX request
            console.error("Error:", error);
        });
    } else {
        console.log("ObjectId is empty or not available in localStorage.");
    }
    function clearLocalStorage() {
        localStorage.clear();
    }
    
        // Function to clear Redis storage
        function clearRedisStorage() {
            // Send a POST request to clear Redis storage
            $.post("./assets/clear_redis.php", function(response) {
                console.log(response);
            }).fail(function(xhr, status, error) {
                console.error("Error:", error);
            });
        }
    
        // Function to handle logout
        function logout() {
            // Clear localStorage
            clearLocalStorage();
            // Clear Redis storage
            clearRedisStorage();
            // Redirect the user to the logout page or any other desired location
            window.location.href = "index.html";
        }
    
        // Event listener for logout button click
        $("#logoutButton").click(function() {
            logout();
        });


        $('#updateButton').click(function(event) {
            // Get the updated values from the input fields
            var dob = $('#dob').val();
            var gender = $('#gender').val();
            var phone = $('#phone').val();
            var street_name = $('#street_name').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var pincode = $('#pincode').val();

            event.preventDefault();
    
            // Send the updated values to the PHP script via AJAX
            $.ajax({
                url: './assets/update_profile.php',
                method: 'POST',

                data: {
                    objectId: objectId,
                    dob: dob,
                    gender: gender,
                    phone: phone,
                    street_name: street_name,
                    city: city,
                    state: state,
                    pincode: pincode
                },
                success: function(response) {
                    // Handle success response
                    // console.log('Profile updated successfully');
                    // console.log(response);
                    alert("Updated Successfully")
                    // Optionally, display a success message to the user
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Error updating profile:', error);
                    // Optionally, display an error message to the user
                }
            });
        });
});