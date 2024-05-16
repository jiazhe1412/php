<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Selected Event</title>
        <link href="css/user_event.css" rel="stylesheet" type="text/css"/>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>

        <?php
        include './header.php';
        require_once './config/database_connection.php';
        ?>
        <h1 id='A'>Product Selected</h1>
        <table>
            <?php
            global $eventName;
            if (isset($_POST['tosearch'])) {
                $eventName = $_POST['eventName'];
                $day = $_POST['day'];
                $month = $_POST['month'];
                $year = $_POST['year'];

                if (!empty($day) && !empty($month) && !empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $startDate = "$year" . "-" . "$month" . "-" . "$day";
                    $endDate = "$year" . "-" . "$month" . "-" . "$Totaldate";
                } else if (!empty($day) && empty($month) && empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $year = date("Y");
                    $firstMonth = "01";
                    $endMonth = "12";
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, 12, $year);
                    $startDate = "$year" . "-" . "$firstMonth" . "-" . "$day";
                    $endDate = "$year" . "-" . "$endMonth" . "-" . "$Totaldate";
                } else if (empty($day) && !empty($month) && empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $day = "01";
                    $year = date("Y");
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $startDate = "$year" . "-" . "$month" . "-" . "$day";
                    $endDate = "$year" . "-" . "$month" . "-" . "$Totaldate";
                } else if (empty($day) && empty($month) && !empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $day = "01";
                    $firstMonth = "01";
                    $endMonth = "12";
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, 12, $year);
                    $startDate = "$year" . "-" . "$firstMonth" . "-" . "$day";
                    $endDate = "$year" . "-" . "$endMonth" . "-" . "$Totaldate";
                } else if (empty($day) && empty($month) && empty($year)) {

                    $startDate = "%";
                    $endDate = "";
                } else if (!empty($day) && !empty($month) && empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $year = date('Y');
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $startDate = "$year" . "-" . "$month" . "-" . "$day";
                    $endDate = "$year" . "-" . "$month" . "-" . "$Totaldate";
                } else if (empty($day) && !empty($month) && !empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $day = "01";
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $startDate = "$year" . "-" . "$month" . "-" . "$day";
                    $endDate = "$year" . "-" . "$month" . "-" . "$Totaldate";
                } else if (!empty($day) && empty($month) && !empty($year)) {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $firstMonth = "01";
                    $endMonth = "12";
                    $Totaldate = cal_days_in_month(CAL_GREGORIAN, 12, $year);
                    $startDate = "$year" . "-" . "$firstMonth" . "-" . "$day";
                    $endDate = "$year" . "-" . "$endMonth" . "-" . "$Totaldate";
                } else {
                    if (empty($eventName)) {
                        $eventName = "%";
                    }
                    $startDate = "";
                    $endDate = "";
                }


                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                if ($startDate == "%") {
                    $sql = "SELECT * FROM event WHERE eventName LIKE '$eventName'";
                } else {
                    $sql = "SELECT * FROM event WHERE eventName LIKE '$eventName' AND startDay BETWEEN '$startDate' AND '$endDate'";
                }
                $result = $con->query($sql);

                if ($result->num_rows != 0) {
                    //record found
                    $i = 0;
                    while ($record = $result->fetch_object()) {
                        echo ($i == 0) ? "<tr>" : "";
                        echo "<td>";
                        echo '<div class = "event">';
                        printf("
                  <img src='image/%s' alt='logo' /><br />
                  <b>%s</b><br /><br /><br /><br />
                  <div class = 'detail'>
                  <i class='far fa-clock' style='font-size:16px;color:#303030;' > %s</i><br />
                  &nbsp;<i class='fa fa-dollar' style='font-size:16px;color:#303030;' >&nbsp;&nbsp;RM%d per person</i><br />
                  </div>
                  <a href='EventDetail.php?eventid=%s'>Booking Now</a>"
                                , $record->eventPhoto
                                , $record->eventName
                                , date("H:i", $record->startTime)
                                , $record->price
                                , $record->eventID
                        );

                        echo "</div>";
                        echo "</td> ";
                        $i++;
                        if ($i == 3) {
                            echo "</tr>";
                            $i = 0;
                        }
                    }

                    if ($result->num_rows == 1) {
                        echo "<th></th><th></th>";
                    } else if ($result->num_rows == 2) {
                        echo "<th></th>";
                    }

                    $eventName = "";
                    $day = "";
                    $month = "";
                    $year = "";
                    $startDate = "";
                    $endDate = "";
                    $firstMonth = "";
                    $endMonth = "";
                    $Totaldate = "";
                } else {
                    echo "<div class='error'>No record found.Please search again.</div>";
                }

                $con->close();
                $result->free();
            }
            ?>

        </table>
        <input type="hidden" name="haha" value="<?php echo (isset($eventName)) ? $eventName : "" ?>" />
        <input type="hidden" name="haha" value="<?php echo (isset($startDate)) ? $startDate : "" ?>" />
        <input type="hidden" name="haha" value="<?php echo (isset($endDate)) ? $endDate : "" ?>" />
        <input type="hidden" name="haha" value="<?php echo (isset($day)) ? $day : "" ?>" />
        <input type="hidden" name="haha" value="<?php echo (isset($month)) ? $month : "" ?>" />
        <input type="hidden" name="haha" value="<?php echo (isset($year)) ? $year : "" ?>" />


    </body>
</html>
