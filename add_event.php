<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Event Adding Form</title>
        <link href="css/add_event.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class='top'><h2>Add New Event</h2></div>


        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        ?>
        <?php ?>
        <table class = "addEvent">
            <form action="" method = "POST" enctype="multipart/form-data">

                <tr>
                    <td colspan="3">
                        <?php
                        global $endDate, $startDate, $eventName, $startTime, $endTime, $description, $price, $ticketNum, $venue;

                        if (isset($_POST['addForm'])) {
                            $eventID = "E" . checkEventID();
                            $startDate = $_POST['startDate'];
                            $endDate = $_POST['endDay'];
                            $eventName = trim($_POST['eventName']);
                            $startTime = $_POST['startTime'];
                            $endTime = $_POST['endTime'];
                            $description = trim($_POST['descr']);
                            $price = $_POST['price'];
                            $ticketNum = $_POST['ticketNum'];
                            $venue = trim($_POST['venue']);

                            //check the photo
                            if (isset($_FILES['eventImg'])) {
                                //yes, has a photo uploaded
                                //store the photo
                                $photo = $_FILES['eventImg'];
                            }

                            //checking of current form
                            $error = array();

                            if ($startDate == NULL) {
                                $error['startdate'] = "The <b>START DATE</b> is empty.";
                            } else if ($startDate < date("Y-m-d")) {
                                $error['startdate'] = "The <b>START DATE</b> already past, please select the new day.";
                            }


                            $error['enddate'] = checkEndDate($endDate, $startDate);

                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                            $sql = "SELECT * from event";
                            $checking = 0;
                            if ($result = $con->query($sql)) {
                                //record found

                                while ($record = $result->fetch_object()) {
                                    if ($eventName == $record->eventName) {
                                        $checking++;
                                    }
                                }



                                //check the event name
                                if ($eventName == NULL) {
                                    $error['name'] = "The <b>EVENT NAME</b> is empty.";
                                } else if (strlen($eventName) > 50) {
                                    $error['name'] = "The <b>EVENT NAME</b> is too long.Make it short.";
                                } else if (!preg_match("/^[a-zA-Z0-9\-,]{1,50}?/", $eventName)) {
                                    $error['name'] = "The <b>EVENT NAME</b> contain invalid character(s).";
                                } else if ($checking > 0) {
                                    $error['name'] = "Duplicated <b>EVENT NAME</b> found. Please change new name.";
                                }
                            }
                            $con->close();
                            $result->free();
                            
                            $error['photo'] = checkPhoto($photo);
                            $error['starttime'] = checkStartTime($startTime);
                            $error['endtime'] = checkEndTime($endTime, $startTime);
                            $error['description'] = checkDescription($description);
                            $error['price'] = checkPrice($price);
                            $error['ticket'] = checkTicketNum($ticketNum);
                            $error['venue'] = checkVenue($venue);
                            $error = array_filter($error);

                            if (empty($error)) {

                                $startTime = strtotime($startTime);
                                $endTime = strtotime($endTime);

                                $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
                                ////no problem,save in the file
                                //create a uniqueid and use it as the filename
                                $newFileName = uniqid() . "." . "$ext";

                                //move the file and save in the image folder
                                move_uploaded_file($photo['tmp_name'], 'image/' . $newFileName);

                                //insert into database
                                //step 1:make connection to database
                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                                $sql = "INSERT INTO event (eventID,eventName,eventPhoto,startDay,endDay,startTime,endTime,description,price,ticketNumber,venue) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssssssdds", $eventID, $eventName, $newFileName, $startDate, $endDate, $startTime, $endTime, $description, $price, $ticketNum, $venue);
                                $stmt->execute();

                                if ($stmt->affected_rows > 0) {
                                    //insert succesful
                                    echo "<div class='info'>Event [$eventName] has been inserted.</div>";
                                } else {
                                    echo "<div class='error'>Unable to insert!</div>";
                                }

                                $endDate = false;
                                $startDate = false;
                                $eventName = false;
                                $startTime = false;
                                $endTime = false;
                                $description = false;
                                $price = false;
                                $ticketNum = false;
                                $venue = false;

                                $stmt->close();
                                $con->close();
                            } else {
                                printf("<ul class='error'><li>%s</li></ul>", implode('</li><li>', $error));
                            }
                        } else {
                            //no, user do not make any event adding
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" id="title"><h2>Event Registration Form</h2></td>
                </tr>

                <tr id='dateSelected'>
                <input type="hidden" name="eventid" value="<?php echo (isset($eventID) ? $eventID : "") ?>" />
                <th>Start Date</th>
                <td style='width:10%;'>:</td>
                <td><input type="date" name="startDate" value="<?php echo (isset($startDate) ? $startDate : "") ?>"/> > <input type='date' name='endDay' value="<?php echo (isset($endDate) ? $endDate : '') ?>"></td>
                </tr>



                <tr>
                    <th>Event Name</th>
                    <td style="width:10%;">:</td>
                    <td><input type="text" name="eventName" value="<?php echo (isset($eventName) ? $eventName : "") ?>"/></td>

                </tr>

                <tr>
                    <th>Event Picture</th>
                    <td style="width:10%;">:</td>
                    <td><input type="file" name="eventImg" /></td>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                </tr>

                <tr>
                    <th>Time</th>
                    <td style="width:10%;">:</td>
                    <td><input type="time" name="startTime" width="30%" value="<?php echo (isset($startTime) ? $startTime : "") ?>"/> > <input type="time" name="endTime" width="30%" value="<?php echo (isset($endTime) ? $endTime : "") ?>"/></td>
                </tr>

                <tr>
                    <th style="vertical-align:top;">Description</th>
                    <td style="width:10%;vertical-align:top;">:</td>
                    <td><textarea rows="5" cols="40" id="descr" name="descr"><?php echo (isset($description) ? $description : "") ?></textarea></td>
                </tr>

                <tr>
                    <th>Price(RM)</th>
                    <td style="width:10%;">:</td>
                    <td><input style="width:15%;" type="number" name="price" placeholder="0.00" value="<?php echo (isset($price) ? $price : "") ?>" min="0" max="999" step=".01"/></td>
                </tr>
                <tr>
                    <th>number of ticket provided</th>
                    <td style="width:10%;">:</td>
                    <td><input style="width:15%;" type="number" name="ticketNum" placeholder="0" value="<?php echo (isset($ticketNum) ? $ticketNum : "") ?>" min="0" max="9999"/></td>
                </tr>
                <tr>
                    <th>Venue</th>
                    <td style="width:10%;">:</td>
                    <td><textarea id="descr" name="venue" rows="5" cols="40"/><?php echo (isset($venue) ? $venue : "") ?></textarea></td>
                </tr>
                <tr>
                    <td id="complete" colspan="3"><input type="submit" value="Submit" name="addForm" /><input type="button" value="Reset" name="btnReset" onclick="location = 'add_event.php'"/></td>
                </tr>
        </table>
    </form>

</body>
</html>
