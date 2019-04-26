$(document).ready(() => {

    $('.login-form').submit(function (event) {
        event.preventDefault();

        removeErrorMessages();

        const inputs = $(".login-form input:not([type='submit'])");
        let userData = {};
        $.each(inputs, function(key, input) {
            userData[input.name] = input.value;
        });

        $.ajax({
            url: '../php/login.php',
            type: 'POST',
            data: {...userData},
            success: function(response){
                alert('You are succesfully logged in');
            },
            error: function(error) {
                const errors = error.responseJSON.errors;
                displayErrorMessages(errors);
            }
        });
    });
});

const displayErrorMessages = errors => {
    for (let error in errors) {
        const errorHTML =`<div class="error-message">${errors[error]}<div>`;
        $(errorHTML).insertAfter(`input[name=${error}`);
    }
};

const removeErrorMessages = () => {
    $('.error-message').remove();
};