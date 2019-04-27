$(document).ready(() => {

    //check whether login cookies are present
    const userName = getCookie('userName');

    //if they are, display welcome message and logout button
    if (getCookie('PHPSESSID') && userName) {

        $('body').html(`<h1>Welcome ${userName}</h1><button class="logout-button">Logout</button>`);
    }

    //when submitting a form...
    $('.login-form').submit(function (event) {
        //do not reload the page
        event.preventDefault();

        //remove previous error messages
        removeErrorMessages();

        //read user input into userData object
        const inputs = $(".login-form input:not([type='submit'])");
        let userData = {};
        $.each(inputs, function (key, input) {
            userData[input.name] = input.value;
        });

        //send request to a server
        $.ajax({
            url: '../php/login.php',
            type: 'POST',
            data: {...userData},
            success: function (response) {
                //reload a page
                window.location.href = 'login.html';
            },
            error: function (error) {
                //display errors
                const errors = error.responseJSON.errors;
                displayErrorMessages(errors);
            }
        });
    });

    //if user clicks logout button...
    $('.logout-button').click(function (event) {
        //do not reload the page
        event.preventDefault();
        //send logout request
        $.ajax({
            url: '../php/logout.php',
            type: 'POST',
            success: function () {
                //reload a page
                window.location.href = 'login.html';
            },
            error: function () {
                //if user cannot logout, alert him
                alert('You cannot logout');
            }
        })
    })
});

//function to display error messages under corresponding input fields
const displayErrorMessages = errors => {
    for (let error in errors) {
        const errorMessage = errors[error];
        const errorHTML = `<div class="error-message">${errorMessage}<div><br>`;

        if (error === 'wrongCredentials') {
            $(errorHTML).insertBefore(`input[type="submit"]`);
            continue;
        }

        $(errorHTML).insertAfter(`input[name=${error}`);
    }
};

//function to remove error messages
const removeErrorMessages = () => {
    $('.error-message').remove();
};

//function that returns cookie value based on cookie name
const getCookie = (cookieName) => {
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        if (cookie.includes(cookieName)) {
            return cookie.split('=')[1];
        }
    }

    return null;
};
