$(document).ready(function() {
  $('#myForm').submit(function(event) {
    // If form is not valid, return
    if (!$(this).valid()) {
      return;
      // console.log("invalid")
    }

    // Prepare form data
    var formData = $(this).serialize();

     // Send AJAX request
     $.ajax({
        type: 'POST',
        url: './php/register.php', // URL of your server-side script
        data: formData,
        success: function(response) {
           // Handle success (e.g., show a success message)
           console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           // Handle error (e.g., show an error message)
           console.error(textStatus, errorThrown);
        }
     });
  });
});
