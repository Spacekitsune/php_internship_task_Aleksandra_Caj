<?php
admin_options:
echo "\n";
echo "Your options:\n";
echo "See registered bookings(Press 1)\n";
echo "Exit(Press 2)\n";
echo "\n";

$option_choice = trim(fgets(STDIN, 1024));

switch ($option_choice) {
    case 1:
        echo "Enter date (YYYY-MM-DD):\n";
        $admin_date = trim(fgets(STDIN, 1024));

        $json = file_get_contents($target_db);
        $json = json_decode($json, true);

        $date_bookings = [];
        $pattern = "#^$admin_date#";

        foreach ($json as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                if ($kindex == 'booking' && preg_match($pattern, $kvalue)) {
                    array_push($date_bookings, $value);
                }
            }
        }

        //sort array by time
        foreach ($date_bookings as $index => $value) {
            foreach ($value as $kindex => $kvalue) {
                $time = substr($date_bookings[$index]['booking'], 11);
                $time1 = substr($time, 0, 2);
                $time2 = substr($time, 3, 5);
                $time = $time1 . $time2;
                $date_bookings[$index]['check-time'] = $time;
            }
        }

        $columns = array_column($date_bookings, 'check-time');
        array_multisort($columns, SORT_ASC, $date_bookings);

        // print array in list
        foreach ($date_bookings as $index => $value) {
            echo $date_bookings[$index]['booking'] . " " . $date_bookings[$index]['name'] . " " . $date_bookings[$index]['address'] . " " . $date_bookings[$index]['phone'] . " " . $date_bookings[$index]['email'] . "\n";
        }

        admin_sub_options:
        echo "\n";
        echo "Print CSV file (Press 1):\n";
        echo "Back to options (Press 2):\n";
        echo "Exit (Press 3):\n";
        echo "\n";

        $option_choice = trim(fgets(STDIN, 1024));

        if ($option_choice == 1) {

            //export csv
            foreach ($date_bookings as $index => $value) {
                unset($date_bookings[$index]['id']);
                unset($date_bookings[$index]['booking-id']);
                unset($date_bookings[$index]['check-time']);
            }


            $csv = 'csv/' . $admin_date . '.csv';
            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer, array('booking ID', 'Name', 'Email', 'Phone', 'Address', 'Date'));
            foreach ($date_bookings as $index => $value) {
                fputcsv($file_pointer, $value);
            }

            fclose($file_pointer);
            goto admin_options;
        } else if ($option_choice == 2) {

            goto admin_options;
        } else if ($option_choice == 3) {

            break;
        } else {
            echo "Your option choice is invalid.\n";
            goto admin_sub_options;
        }

    case 2:
        break;

    default:
        echo "Your option choice is invalid.\n";
        goto admin_options;
}
