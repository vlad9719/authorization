<?php

include 'returnErrorMessages.php';

header('Content-Type: application/json');
$response = [];
$errors = [];

$users = simplexml_load_file('../users.xml');

foreach ( $_POST as $field => $value ) {
    if (empty($value)) {
        $errors[$field] = 'Please fill out this field';
    }
}

$login = $_POST['login'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

if (!empty($password) && !empty($confirmPassword) && $password != $confirmPassword) {
    $errors['confirm_password'] = 'Password and Confirm Password fields do not match';
}

foreach ($users as $user) {
    if ($user->login == $login) {
        $errors['login'] = 'Such a login has already been taken';
    }

    if ($user->email == $email) {
        $errors['email'] = 'Such email has already been taken';
    }

}

if (!empty($errors)) {
    returnErrorMessage($errors);
}

$user = $users->addChild('user');
$user->addChild('login', $login);
$user->addChild('name', $name);
$user->addChild('email', $email);
$user->addChild('password', sha1('соль' . $password));

$response = [
    'message' => 'You are successfully registered',
];

$users->asXML('../users.xml');

echo json_encode($response);
