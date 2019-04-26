<?php

function returnErrorMessage($errors) {
    http_response_code(400);
    $response['errors'] = $errors;
    echo json_encode($response);
    die();
}
