<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Event detail</title>
        <link href="css/admin-event-detail.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        ?>

        <div class='top'><h2>Product Detail</h2></div>
        <div id="all">

            <div id="heading">
                <h2 id="record">Detail</h2>
            </div>
            <table>
                <thead>


                    <tr>
                        <th>Product Name:</th>
                        <td>
                            <form method="POST">
                                <select name="eventName">
                                    <option>-</option>

                                    <?php
                                    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                                    $sql = "SELECT * FROM event";

                                    //get and store all the record
                                    $i = 0;
                                    if ($result = $con->query($sql)) {

                                        while ($record = $result->fetch_object()) {
                                            $eventID[$i] = $record->eventID;
                                            $eventName[$i] = $record->eventName;
                                            echo "<option value='$eventID[$i]'>$eventName[$i]</option>";
                                            $photoName[$i] = $record->eventPhoto;
                                            $startDate[$i] = $record->startDay;
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
                                    $result->free();
                                    $con->close();

                                    if (isset($_POST['getName'])) {
                                        (isset($_POST['eventName'])) ? $getEventID = $_POST['eventName'] : $getEventID = "";

                                        $a = 0;
                                        $check = 0;
                                        while ($a < $i) {
                                            if ($getEventID == $eventID[$a]) {
                                                $eventID = $eventID[$a];
                                                $eventName = $eventName[$a];
                                                isset($photoName) ? $photoName = $photoName[$a] : $photoName = "";
                                                $startDate = $startDate[$a];
                                                $endDate = $endDate[$a];
                                                $startTime = $startTime[$a];
                                                $endTime = $endTime[$a];
                                                $description = $description[$a];
                                                $price = $price[$a];
                                                $ticketNum = $ticketNum[$a];
                                                $venue = $venue[$a];

                                                $startTime = date("H:i", $startTime);
                                                $endTime = date("H:i", $endTime);
                                                $a = 8;
                                                $check++;
                                            }
                                            $a++;
                                        }

                                        if ($check == 0) {
                                            $eventID = "";
                                            $eventName = "";
                                            $photoName = "";
                                            $startDate = "";
                                            $endDate = "";
                                            $startTime = "";
                                            $endTime = "";
                                            $description = "";
                                            $price = "";
                                            $ticketNum = "";
                                            $venue = "";
                                        }
                                    } else {
                                        $check = 1;
                                        $a = 0;

                                        $eventName = $eventName[$a];
                                        $photoName = $photoName[$a];
                                        $startDate = $startDate[$a];
                                        $endDate = $endDate[$a];
                                        $startTime = $startTime[$a];
                                        $endTime = $endTime[$a];
                                        $description = $description[$a];
                                        $price = $price[$a];
                                        $ticketNum = $ticketNum[$a];
                                        $venue = $venue[$a];

                                        $startTime = date("H:i", $startTime);
                                        $endTime = date("H:i", $endTime);
                                    }



                                    //the record from event calender
                                    if ($_SERVER["REQUEST_METHOD"] == "GET") {
                                        //get method            
                                        //retreive data and display in the form
                                        //retrieve the id from the url
                                        (isset($_GET["eventid"])) ? $eventID = strtoupper(trim($_GET["eventid"])) : $eventID = '';

                                        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

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
                                        }
                                        $result->free();
                                        $con->close();
                                    }
                                    ?>


                                </select>

                        </td>
                        <td><input type="submit" value="search" name="getName" /></td>

                        </form>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <?php
                            if ($check == 0) {
                                echo "<div class='error'>No product found.</div>";
                            }
                            ?>
                        </td>
                    </tr>
                </thead>



                <tbody>
                <input type="hidden" name="eventid" value="<?php echo (isset($eventID) ? $eventID : "") ?>" readonly/>
                <tr id='dateSelected'>
                    <th>Start Date</th>
                    <td style='width:10%;'>:</td>
                    <td><input type="date" name="startDate" value="<?php echo (isset($startDate) ? $startDate : "") ?>" readonly/> > <input type='date' name='endDay' value="<?php echo (isset($endDate) ? $endDate : '') ?>" readonly></td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <td style="width:10%;">:</td>
                    <td><input type="text" name="eventName" value="<?php echo (isset($eventName) ? $eventName : "") ?>" readonly/></td>

                </tr>

                <tr rowspan="2">
                    <th style="vertical-align: top;">Product Picture</th>
                    <td style="width:10%;vertical-align: top;">:</td>
                    <td><img src="image/<?php echo (isset($photoName) ? $photoName : "") ?>" width="40%" height="50%" /></td>
                <input type="hidden" name="eventPhoto" value="<?php echo (isset($photoName) ? $photoName : "") ?>" readonly/>
                </tr>


                <tr>
                    <th>Time</th>
                    <td style="width:10%;">:</td>
                    <td><input type="time" name="startTime" style="width:15%;" value="<?php echo (isset($startTime) ? $startTime : "") ?>" readonly/> > <input type="time" name="endTime" style="width:15%;"  value="<?php echo (isset($endTime) ? $endTime : "") ?>" readonly/></td>
                </tr>

                <tr>
                    <th style="vertical-align:top;">Description</th>
                    <td style="width:10%;vertical-align: top;">:</td>
                    <td><textarea rows="5" cols="40" id="descr" name="descr" readonly><?php echo (isset($description) ? $description : "") ?></textarea></td>
                </tr>

                <tr>
                    <th>Price(RM)</th>
                    <td style="width:10%;">:</td>
                    <td><input style="width:15%;" type="number" name="price" placeholder="0.00" value="<?php echo (isset($price) ? $price : "") ?>" min="0" max="999" step=".01" readonly/></td>
                </tr>
                <tr>
                    <th>number of product quantity</th>
                    <td style="width:10%;">:</td>
                    <td><input style="width:15%;" type="number" name="ticketNum" placeholder="0" value="<?php echo (isset($ticketNum) ? $ticketNum : "") ?>" min="0" max="9999" readonly/></td>
                </tr>
                <tr>
                    <th style="vertical-align:top;">Venue</th>
                    <td style="width:10%;vertical-align: top;">:</td>
                    <td><textarea id="descr" name="venue" rows="5" cols="40" readonly/><?php echo (isset($venue) ? $venue : "") ?></textarea></td>
                </tr>


                </tbody>


            </table>

        </div>
    </body>
</html>
