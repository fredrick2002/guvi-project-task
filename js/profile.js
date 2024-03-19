$(document).ready(function() {
    // Retrieve the ObjectId from localStorage
    var objectId = localStorage.getItem("objectId");

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
            $("#first_name").text(redisData.first_name);
            $("#last_name").text(redisData.last_name);
            $("#email").text(redisData.email);
            $("#dob").text(redisData.dob);
            $("#gender").text(redisData.gender);
            $("#phone").text(redisData.phone);
            $("#street_name").text(redisData.street_name);
            $("#city").text(redisData.city);
            $("#state").text(redisData.state);
            $("#pincode").text(redisData.pincode);
            
            
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
});