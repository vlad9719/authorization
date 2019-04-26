$(document).ready(() => {

    $('.registration-form').submit(function (event) {
        event.preventDefault();

        removeErrorMessages();

        const inputs = $(".registration-form input:not([type='submit'])");
        let userData = {};
        $.each(inputs, function(key, input) {
            userData[input.name] = input.value;
        });

        $.ajax({
            url: '../php/register.php',
            type: 'POST',
            data: {...userData},
            success: function(){
                alert('You are succesfully registered!');
                window.location.href = "login.html";
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