<?php

//include file with function which returns error messages
include 'returnErrorMessages.php';

//set header for json response
header('Content-Type: application/json');

//initialize variables for response and potential errors
$response = [];
$errors = [];

//get users from xml file
$users = simplexml_load_file('../users.xml');

//check for empty fields in request
foreach ( $_POST as $field => $value ) {
    if (empty($value)) {
        $errors[$field] = 'Please fill out this field';
    }
}

//retrieve user input from post request
$login = $_POST['login'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

//check if password and confirm password match
if (!empty($password) && !empty($confirmPassword) && $password != $confirmPassword) {
    $errors['confirm_password'] = 'Password and Confirm Password fields do not match';
}

//check if such login or email is already registered
foreach ($users as $user) {
    if ($user->login == $login) {
        $errors['login'] = 'Such a login has already been taken';
    }

    if ($user->email == $email) {
        $errors['email'] = 'Such email has already been taken';
    }

}

//return error response if there are errors
if (!empty($errors)) {
    returnErrorMessage($errors);
}

//create a new item for users array and write new data to it
$user = $users->addChild('user');
$user->addChild('login', $login);
$user->addChild('name', $name);
$user->addChild('email', $email);

//hash password and write it to a new xml node
$user->addChild('password', sha1('соль' . $password));

//save array with a new user to xml file
$users->asXML('../users.xml');

//create success response and return it
$response = [
    'message' => 'You are succesfully registered',
];
echo json_encode($response);
