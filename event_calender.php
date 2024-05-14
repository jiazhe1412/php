<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    //current year
    $ym = date('Y');
}

$time = strtotime($ym);

$yearMonth = date('Y-m', $time);
$prev = date("Y-m", strtotime("-1 month", $time));
$next = date("Y-m", strtotime("+1 month", $time));
$month = date("m", $time);
$year = date("Y", $time);
?> 
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Event Calender</title>
        <link href="css/event_calender.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class='top'><h2>Event Calender</h2></div>

        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        ?>
        <div id="selectDay"><a href="?ym=<?php echo $prev; ?>">◀️</a><?php echo $yearMonth ?><a href="?ym=<?php echo $next; ?>">▶️</a></div> 

        <table>
            <?php
            date_default_timezone_set('Asia/Kuala_Lumpur');

            $day = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
            $num = 0;

            $months = array(
                1 => "JANUARY",
                2 => "FEBRUARY",
                3 => "MARCH",
                4 => "APRIL",
                5 => "MAY",
                6 => "JUNE",
                7 => "JULY",
                8 => "AUGUST",
                9 => "SEPTEMBER",
                10 => "OCTOBER",
                11 => "NOVEMBER",
                12 => "DECEMBER"
            );

//get the month Name according to user select month
            foreach ($months as $key => $value) {
                if ($key == $month) {
                    $monthName = "$value";
                }
            }

            $firstDay = date("N", strtotime("$monthName 2023"));

            //display the day name
            echo "<tr>";
            foreach ($day as $value) {
                echo "<th >$value</th>";
            }
            echo "</tr>";

            // get the date for every month
            $date = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            //the array let the day for last month become empty
            //the first date will start at the according day
            $days = array_fill(0, ($firstDay - 1), "");

            for ($a = 1; $a <= $date; $a++) {
                //it will let the date start at the accurate day
                $days[] = $a;
            }

            //to display the event on the calender
            //step 1:make connection
            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $sql = "SELECT * FROM event";
            $i = 0;
            if ($result = $con->query($sql)) {

                while ($record = $result->fetch_object()) {
                    $eventID[$i] = $record->eventID;
                    $eventName[$i] = $record->eventName;
                    $photoName[$i] = $record->eventPhoto;
                    $startDay[$i] = $record->startDay;
                    $endDate[$i] = $record->endDay;
                    $startTime[$i] = $record->startTime;
                    $endTime[$i] = $record->endTime;
                    $description[$i] = $record->description;
                    $price[$i] = $record->price;
                    $ticketNum[$i] = $record->ticketNumber;
                    $venue[$i] = $record->venue;
                    $i++;
                }
            }


            $today = date('Y-m-d');
            //form to post the date
            echo "<form action='add_event.php' method='POST'>";

            echo "<input type='hidden' name='monthValue' value='$month' />";
            echo "<input type='hidden' name='yearValue' value='$year' />";
            echo "<input type='hidden' name='totalDate' value='$date' />";
            $count = 0;
            foreach ($days as $value) {
                ($value < 10 && $value > 0) ? $value = "0" . $value : "";
                echo ($count == 0) ? "<tr>" : "";
                ($value == "") ? $disabled = ' disabled' : $disabled = '';
                $thisday = ($year . "-" . "$month" . "-" . "$value");
                ($today == $thisday) ? $style = '#F2C5E0' : $style = '';
                echo "<td style='background-color:$style;'><input type='submit' value='$value' name = 'dateSubmit' $disabled style='background-color:$style;'/>";
                for ($a = 0; $a < $i; $a++) {
                    if ($startDay[$a] == $thisday) {
                        printf("<div class='eventName'><a href='admin-event-detail.php?eventid=%s'>%s</a></div>", $eventID[$a],$eventName[$a]);
                    } else {
                        echo "<br/>";
                    }
                }
                echo "</td>";
                echo ($count == 7) ? "</tr><tr>" : "";
                $count++;
                ($count == 7) ? $count = 0 : "";
            }
            while ($count < 7) {

                echo "<td></td>";
                $count++;
            }
            echo "</tr>";

            $result->free();
            $con->close();
            echo "</form>";
            ?>
        </table>


    </body>
</html>
