<?php
require("functions.php");
$target_users = 'users.json';
$target_db = 'visits.json';

begining:

echo "Hello! Welcome to apartment cleaning service booking application.\n";
echo "Please, enter your ID code.\n";

$json = file_get_contents($target_users);
$json = json_decode($json, true);

$input_id = trim(fgets(STDIN, 1024));

if (!ctype_digit($input_id)) {
    echo "Invalid id format\n";
    goto begining;
}

foreach ($json as $key => $value) {
    foreach ($value as $kchild => $kvalue) {
        if ($kchild == 'id' && $kvalue == $input_id) {
            $user_id = $input_id;
            goto user;
        }
    }
}

echo "Your id does not exist.\n";
goto begining;

user:
if ($user_id == 501) {
    echo 'Hello admin!';
    echo "\n";
    require('admin.php');
} else {
    echo 'Hello neighbour!';
    echo "\n";
    require('user.php');
}

exit_app:
echo "Are you sure you want to exit? Yes(Press 1) No(Press 2)\n";
$option_choice = trim(fgets(STDIN, 1024));


switch ($option_choice) {
    case 1:
        break;
    case 2:
        goto begining;
    default:
        echo "Your option choice is invalid.\n";
        goto exit_app;
}
