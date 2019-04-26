<?php

include 'returnErrorMessages.php';

header('Content-Type: application/json');
$response = [];
$errors = [];

$users = simplexml_load_file('../users.xml');

foreach ($_POST as $field => $value) {
    if (empty($value)) {
        $errors[$field] = 'Please fill out this field';
    }
}

if (!empty($errors)) {
    returnErrorMessage($errors);
}

$login = $_POST['login'];
$password = $_POST['password'];
$hashedPassword = sha1('соль' . $password);

foreach ($users as $user) {
    if ($user->login == $login && $user->password == $hashedPassword) {
        $response = [
            'message' => 'You are successfully logged in',
        ];

        echo json_encode($response);
        die();
    }
}

$errors['wrongCredentials'] = 'Wrong Login Or Password';
returnErrorMessage($errors);
