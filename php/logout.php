<?php

session_start();

//unset all cookies of an authorized user
setcookie('PHPSESSID', '', time() - 1000, '/');
setcookie('userName', '', time() - 1000, '/');

session_destroy();
