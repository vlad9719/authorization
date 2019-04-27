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
foreach ($_POST as $field => $value) {
    if (empty($value)) {
        $errors[$field] = 'Please fill out this field';
    }
}

//return empty fields errors if they are present
if (!empty($errors)) {
    returnErrorMessage($errors);
}

//retrieve login and password from post request
$login = $_POST['login'];
$password = $_POST['password'];

//hash password
$hashedPassword = sha1('соль' . $password);

//loop through the list of users to find the necessary login-password combination
foreach ($users as $user) {

    //if we found the right combination...
    if ($user->login == $login && $user->password == $hashedPassword) {
        session_start();
        setcookie('userName', $user->name, time() + 3600, '/');
        //...create success response array...
        $response = [
            'message' => 'You are successfully logged in',
            'user' => $user,
        ];

        //... and return success response
        echo json_encode($response);
        die();
    }
}

//return wrong credentials error in case we did not found the right combination
$errors['wrongCredentials'] = 'Wrong Login Or Password';
returnErrorMessage($errors);
