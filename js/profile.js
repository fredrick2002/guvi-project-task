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
            return true;
        } else {
            return false;
        } 
    }

    // Check if the ObjectId is present
    if (objectId) {
        // Send a GET request to your PHP script
        $.get("./php/profile.php", { objectId: objectId }, function(response) {
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
        // Send a DELETE request to clear Redis storage
        $.ajax({
            url: "./assets/clear_redis.php",
            method: "DELETE",
            data: { objectId: objectId },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
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

    // Define a global variable to store original form data
var originalData = {};
populateOriginalData();

// Function to populate originalData with current form values
function populateOriginalData() {
    originalData.first_name = $('#first_name').val();
    originalData.last_name = $('#last_name').val();
    originalData.email = $('#email').val();
    originalData.dob = $('#dob').val();
    originalData.gender = $('#gender').val();
    originalData.phone = $('#phone').val();
    originalData.street_name = $('#street_name').val();
    originalData.city = $('#city').val();
    originalData.state = $('#state').val();
    originalData.pincode = $('#pincode').val();
}
    


    $('#updateButton').click(function(event) {
        event.preventDefault();
    
        // Initialize an empty object to store modified values
        var modifiedData = {};
    
        // Function to compare original and current values
        function compareAndAdd(fieldName, currentValue) {
            if (originalData[fieldName] !== currentValue) {
                modifiedData[fieldName] = currentValue;
            }
        }
    
        // Compare each field with its original value and add to modifiedData if modified
        compareAndAdd('first_name', $('#first_name').val());
        compareAndAdd('last_name', $('#last_name').val());
        compareAndAdd('email', $('#email').val());
        compareAndAdd('dob', $('#dob').val());
        compareAndAdd('gender', $('#gender').val());
        compareAndAdd('phone', $('#phone').val());
        compareAndAdd('street_name', $('#street_name').val());
        compareAndAdd('city', $('#city').val());
        compareAndAdd('state', $('#state').val());
        compareAndAdd('pincode', $('#pincode').val());

        modifiedData.objectId = objectId;
    
        // Send the modified values to the PHP script via AJAX using PATCH method
        $.ajax({
            url: './assets/update_profile.php',
            method: 'PATCH',
            contentType: 'application/json',
            data: JSON.stringify(modifiedData),
            success: function(response) {
                // Handle success response
                
                alert("Updated Successfully");
                populateOriginalData();
                // console.log('Response:', modifiedData);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error('Error updating profile:', error);
            }
        });
    });
    
    
});
