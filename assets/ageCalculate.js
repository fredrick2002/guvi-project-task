$(document).ready(function() {
    $('#dob').change(function() {
        var dob = $(this).val();
        var ageDisplay = $('#ageDisplay');

        if (dob) {
            var today = new Date();
            var birthDate = new Date(dob);

            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--; // Subtract a year if the birthday hasn't occurred yet this year
            }

            ageDisplay.text("Age: " + age);
        } else {
            ageDisplay.text("Age: ");
        }
    });
});