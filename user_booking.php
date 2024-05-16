<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Booking Record</title>
        <link href="css/user_booking.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/database_connection.php';
        ?>


        <div class="record">
            <div id="heading"><h2>Booking History</h2></div>

            <table id="list">

                <tr class="list1">

                    <th>No.</th>
                    <th>Ticket ID</th>
                    <th>Payment ID</th>
                    <th>Event Name</th>
                    <th>Quantity</th>
                    <th>Total Price(RM)</th>
                    <th class="date">Date</th>
                    <th></th>
                </tr>

                <?php
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                $sql = "SELECT ticketID,p.paymentID,eventID,ticketQty,bc.totalPrice,bookingDate FROM bookingrecord bc,payment p WHERE bc.paymentID = p.paymentID AND memberID = '$memberID'";

                if ($result = $con->query($sql)) {
                    //record found
                    $i = 1;
                    while ($record = $result->fetch_object()) {
                        $eventName = getEventName($record->eventID);
                        printf("
                        <tr  class='list1'>
                        <td>%d</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
                        <td>%s</td>
                        <td><a href='?eventid=%s'>View</a></td></tr>
                        ", $i
                                , $record->ticketID
                                , $record->paymentID
                                , $eventName
                                , $record->ticketQty
                                , $record->totalPrice
                                , $record->bookingDate
                                , $record->eventID
                        );
                        $i++;
                    }
                }

                $con->close();
                $result->free();
                ?>


            </table>



            <?php
            $hidetable = false;
            if ($_SERVER["REQUEST_METHOD"] == "GET") {

                (isset($_GET["eventid"])) ? $eventID = strtoupper(trim($_GET["eventid"])) : $eventID = '';

                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                $sql = "SELECT * FROM event WHERE eventID = '$eventID'";
                $result = $con->query($sql);

                if ($record = $result->fetch_object()) {
                    //record found
                    $eventID = $record->eventID;
                    $eventName = $record->eventName;
                    $photoName = $record->eventPhoto;
                    $startDate = $record->startDay;
                    $endDate = $record->endDay;
                    $startTime = $record->startTime;
                    $endTime = $record->endTime;
                    $description = $record->description;
                    $price = $record->price;
                    $ticketNum = $record->ticketNumber;
                    $venue = $record->venue;

                    $startTime = date("H:i", $startTime);
                    $endTime = date("H:i", $endTime);

                    $result->free();
                    $con->close();
                } else {
                    $hidetable = true;
                }
            }
            ?>
            <div id="detail">

                <?php
                if ($hidetable == false):
                    ?>
                    <table id="result">
                        <thead id="Title">
                            <tr><th colspan="3"></th></tr>
                        </thead>

                        <tbody id="r1">
                        <input type="hidden" name="eventid" value="<?php echo (isset($eventID) ? $eventID : "") ?>" />
                        <tr id='dateSelected'>
                            <th>Start Date</th>
                            <td style='width:10%;'>:</td>
                            <td><input type="date" name="startDate" value="<?php echo (isset($startDate) ? $startDate : "") ?>" /> > <input type='date' name='endDay' value="<?php echo (isset($endDate) ? $endDate : '') ?>" readonly></td>
                        </tr>
                        <tr>
                            <th>Event Name</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="eventName" value="<?php echo (isset($eventName) ? $eventName : "") ?>"  readonly/></td>

                        </tr>
                        <tr>
                            <th>Time</th>
                            <td style="width:10%;">:</td>
                            <td><input type="time" name="startTime" style="width:40%;" value="<?php echo (isset($startTime) ? $startTime : "") ?>"  readonly/> > <input type="time" name="endTime" style="width:40%;"  value="<?php echo (isset($endTime) ? $endTime : "") ?>" /></td>
                        </tr>

                       
                        <tr>
                            <th>Price(RM)</th>
                            <td style="width:10%;">:</td>
                            <td><input style="width:20%;" type="number" name="price" placeholder="0.00" value="<?php echo (isset($price) ? $price : "") ?>" min="0" max="999" step=".01"  readonly/></td>
                        </tr>
                        </tbody>

                    </table>
                <?php endif; ?>
            </div>
        </div>


        <?php
        include './footer.php';
        ?>
    </body>
</html>
