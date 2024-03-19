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
         date: true
       },
       phone: {
         required: true,
         digits: true,
         minlength: 10,
         maxlength: 15
       },
       password: {
         required: true,
         minlength: 8,
       },
       street_name: "required",
       city: "required",
       state: "required",
       pincode: {
         required: true,
         digits: true,
         minlength: 6,
         maxlength: 6
       },
       gender: "required"
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
         minlength: "Your password must be at least 8 characters long"
       },
       street_name: "Please enter your street name",
       city: "Please enter your city",
       state: "Please enter your state",
       pincode: {
         required: "Please enter your pincode",
         digits: "Please enter a valid pincode",
         minlength: "Your pincode must be 6 digits",
         maxlength: "Your pincode must be 6 digits"
       },
       gender: "Please select your gender"
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
