$(document).ready(function() {
  $('#myForm').validate({
     rules: {
       first_name: "required",
       last_name: "required",
       email: {
         required: true,
         email: true
       },
       dob: {
         required: true,
         date: true // Assuming you're using a date input for date of birth
       },
       phone: {
         required: true,
         digits: true, // Assuming phone number is numeric
         minlength: 10, // Minimum length for a phone number
         maxlength: 15 // Maximum length for a phone number
       },
       password: {
         required: true,
         minlength: 8, // Minimum length for password
         // Adding a regex for password complexity
         regex: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/,
       }
     },
     messages: {
       first_name: "Please enter your first name",
       last_name: "Please enter your last name",
       email: {
         required: "Please enter your email address",
         email: "Please enter a valid email address"
       },
       dob: {
         required: "Please enter your date of birth",
         date: "Please enter a valid date"
       },
       phone: {
         required: "Please enter your phone number",
         digits: "Please enter a valid phone number",
         minlength: "Your phone number must be at least 10 digits",
         maxlength: "Your phone number must be no more than 15 digits"
       },
       password: {
         required: "Please enter a password",
         minlength: "Your password must be at least 8 characters long",
         regex: "Your password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character."
       }
     },
     submitHandler: function(form) {
       // Prevent form submission
       event.preventDefault();
 
       // Prepare form data
       var formData = $(form).serialize();
 
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
     }
  });
});
