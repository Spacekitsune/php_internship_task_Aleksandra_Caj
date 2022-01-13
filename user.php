<?php
options:
echo "\n";
echo "Your options:\n";
echo "Book cleaning service(Press 1)\n";
echo "Edit your booking(Press 2)\n";
echo "Delete your booking(Press 3)\n";
echo "Exit(Press 4)\n";

$option_choice = trim(fgets(STDIN, 1024));

switch ($option_choice) {
    case 1: //New booking

        enter_name:
        echo "Enter your Name:\n";
        $user_name = trim(fgets(STDIN, 1024));
        if ( !preg_match('/[A-Za-z0-9]/', $user_name) && strlen($user_name) <= 35) {
            goto enter_name;
        }

        enter_email:
        echo "Enter your email:\n";
        $user_email = trim(fgets(STDIN, 1024));
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            goto enter_email;
        }

        enter_phone:
        echo "Enter your phone number:\n";
        $user_phone = trim(fgets(STDIN, 1024));
        if (!ctype_digit($user_phone) && strlen($user_phone) <= 15) {
            goto enter_phone;
        }

        enter_apartment:
        echo "Enter your appartment address (Use only letters and digits):\n";
        $user_appartment = trim(fgets(STDIN, 1024));
        if (!preg_match('/[A-Za-z0-9]/', $user_appartment)  && strlen($user_appartment) <= 35) {
            goto enter_apartment;
        }

        enter_date:
        echo "Enter preffered date and time (YYYY-MM-DD hh:mm):\n";
        echo "(Your chosen date should't be out of 30 days range)\n";
        $user_date_time = trim(fgets(STDIN, 1024));

        if (!is_date_ok($user_date_time)) {
            echo "Date and time format was entered wrong.\n";
            goto enter_date;
        }

        $json = file_get_contents($target_db);
        $json = json_decode($json, true);

        $new_booking = array(
            "id" => $user_id,
            "booking_id" => create_booking_id($json),
            "name" => $user_name,
            "email" => $user_email,
            "phone" => $user_phone,
            "address" => $user_appartment,
            "booking" => $user_date_time
        );




        array_push($json, $new_booking);
        $json = json_encode($json);
        if (file_put_contents($target_db, $json)) {
            echo "Your booking was successfully entered\n";
        }

        exit_options:
        echo "Back to menu(Press 1)\n";
        echo "Exit(Press 2)\n";

        $option_choice = trim(fgets(STDIN, 1024));


        if ($option_choice == 1) {
            goto options;
        } else if ($option_choice == 2) {
            break;
        } else {
            echo "your option choice is invalid.\n";
            goto options;
        }

    case 2: // Edit existing booking

        $json = file_get_contents($target_db);
        $json = json_decode($json, true);

        $my_booking = [];

        foreach ($json as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                if ($kindex == 'id' && $kvalue == $user_id) {
                    array_push($my_booking, $value);
                }
            }
        }

        $counter = 0;

        foreach ($my_booking as $index => $value) {
            echo "Booking id: " . $my_booking[$index]['booking_id'] . " Booking date: " . $my_booking[$index]['booking'] . "\n";
            $counter++;
        }

        if ($counter == 0) {
            echo "No bookings were found.\n";
            goto exit_options;
        }

        echo "Enter booking id you want to edit:\n";
        $option_choice = trim(fgets(STDIN, 1024));

        edit_date:
        echo "Enter new date and time:\n";
        echo "(Your chosen date should't be out of 30 days range)\n";
        $edit_date = trim(fgets(STDIN, 1024));
        if (!is_date_ok($edit_date)) {
            echo "Date and time format was entered wrong.\n";
            goto edit_date;
        }

        foreach ($json as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                if ($kindex == 'booking_id' && $kvalue == $option_choice) {
                    $json[$index]['booking'] = $edit_date;

                    $json = json_encode($json);
                    if (file_put_contents($target_db, $json)) {
                        echo "Your booking was successfully edited.\n";
                        goto exit_options;
                    }
                }
            }
        }

        goto exit_options;

        break;
    case 3: // Delete existing booking

        $json = file_get_contents($target_db);
        $json = json_decode($json, true);

        $my_booking = [];

        foreach ($json as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                if ($kindex == 'id' && $kvalue == $user_id) {
                    array_push($my_booking, $value);
                }
            }
        }

        $counter = 0;

        foreach ($my_booking as $index => $value) {
            echo "Booking id: " . $my_booking[$index]['booking_id'] . " Booking date: " . $my_booking[$index]['booking'] . "\n";
            $counter++;
        }

        if ($counter == 0) {
            echo "No bookings were found.\n";
            goto exit_options;
        }

        echo "Enter booking id you want to delete:\n";
        $option_choice = trim(fgets(STDIN, 1024));


        foreach ($json as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                if ($kindex == 'booking_id' && $kvalue == $option_choice) {
                    unset($json[$index]);

                    $json = json_encode($json);
                    if (file_put_contents($target_db, $json)) {
                        echo "Your booking was successfully deleted.\n";
                        goto exit_options;
                    }
                }
            }
        }

        goto exit_options;
        break;
    case 4:
        break;
    default:
        echo "Your option choice is invalid.\n";
        goto options;
}
