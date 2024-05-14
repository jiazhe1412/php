<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Event</title>
        <link href="css/edit-delete.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        ?>
        <div class='top'><h2>Edit Event</h2></div>
        <div id="detail">
            <form method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td colspan="3">
                            <?php
                            $hideform = false;
                            if ($_SERVER["REQUEST_METHOD"] == "GET") {


                                (isset($_GET["eventid"])) ? $eventID = strtoupper(trim($_GET["eventid"])) : $eventID = '';

                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                                $sql = "SELECT * FROM event WHERE eventID = '$eventID'";//
                                $result = $con->query($sql);//

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
                                    //record not found
                                    echo "<div class='error'>Unable to retrieve record![<b><a href='list-event.php' >Back to list</a></b>]</div>";
                                    $hideform = true;
                                }
                            } else {
                                $photoName = $_POST['eventPhoto'];
                                $eventID = $_POST['eventid'];
                                $startDate = $_POST['startDate'];
                                $endDate = $_POST['endDay'];
                                $eventName = trim($_POST['eventName']);
                                $startTime = $_POST['startTime'];
                                $endTime = $_POST['endTime'];
                                $description = trim($_POST['descr']);
                                $price = $_POST['price'];
                                $ticketNum = $_POST['ticketNum'];
                                $venue = trim($_POST['venue']);

                                $error = array();
                                //checking of current form
                                $error['startdate'] = checkStartDate($startDate);
                                $error['enddate'] = checkEndDate($endDate, $startDate);
                                $error['name'] = checkName($eventName);

                                if (isset($_FILES['newEventImg'])) {
                                    //yes, has a photo uploaded
                                    //store the photo
                                    $photo = $_FILES['newEventImg'];

                                    if ($photo['error'] > 0) {
                                        //have error, display msg
                                        switch ($photo['error']) {
                                            case UPLOAD_ERR_NO_FILE:
                                                //if no picture,then continue updated
                                                $photo = "nothing";
                                                $newFileName = $photoName;

                                                break;
                                            case UPLOAD_ERR_FORM_SIZE:
                                                $error['photo'] = "<b>EVENT PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
                                                break;
                                            default:
                                                $error['photo'] = "There was an error when uploading the <b>EVENT PICTURE</b>.";
                                        }
                                    } else if ($photo['size'] > 1048576) {
                                        //validate the photo size
                                        //1MB = 1024 x 1024
                                        $error['photo'] = "<b>EVENT PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
                                    } else {
                                        //extract file extension, eg:png,jpg,gif
                                        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

                                        //check the file extension
                                        if ($ext != 'jpg' &&
                                                $ext != 'jpeg' &&
                                                $ext != 'gif' &&
                                                $ext != 'png') {
                                            return "Only JPG, GIF and PNG images are allowed!";
                                        }
                                    }
                                }


                                $error['starttime'] = checkStartTime($startTime);
                                $error['endtime'] = checkEndTime($endTime, $startTime);
                                $error['description'] = checkDescription($description);
                                $error['price'] = checkPrice($price);
                                $error['ticket'] = checkTicketNum($ticketNum);
                                $error['venue'] = checkVenue($venue);
                                $error = array_filter($error);

                                if (empty($error)) {

                                    if ($photo != "nothing") {
                                        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
                                        ////no problem,save in the file
                                        //create a uniqueid and use it as the filename
                                        $newFileName = uniqid() . "." . "$ext";

                                        //move the file and save in the image folder
                                        move_uploaded_file($photo['tmp_name'], 'image/' . $newFileName);

                                        $path = "image/$photoName";
                                        unlink($path);
                                    }

                                    $startTime = strtotime($startTime);
                                    $endTime = strtotime($endTime);

                                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                                    $sql = "UPDATE event SET eventName = ?,eventPhoto = ?,startDay = ?,endDay = ?, startTime = ?, endTime = ?,description = ?,price = ?, ticketNumber = ?,venue = ? WHERE eventID = ?";

                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param("sssssssddss", $eventName, $newFileName, $startDate, $endDate, $startTime, $endTime, $description, $price, $ticketNum, $venue, $eventID);

                                    if ($stmt->execute()) {
                                        //insert succesful
                                        echo "<div class='info'>Event [$eventName] has been updated.[<a href='admin-event-detail.php?eventid=$eventID'>See Result</a>]</div>";
                                        $startTime = date("H:i", $startTime);
                                        $endTime = date("H:i", $endTime);
                                    } else {
                                        echo "<div class='error'>Unable to update!</div>";
                                    }

                                    $con->close();
                                    $stmt->close();
                                } else {
                                    printf("<ul class='error'><li>%s</li></ul>", implode('</li><li>', $error));
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    if ($hideform == false):
                        ?>

                        <input type="hidden" name="eventid" value="<?php echo (isset($eventID) ? $eventID : "") ?>" />
                        <tr id='dateSelected'>
                            <th>Start Date</th>
                            <td style='width:10%;'>:</td>
                            <td><input type="date" name="startDate" value="<?php echo (isset($startDate) ? $startDate : "") ?>" /> > <input type='date' name='endDay' value="<?php echo (isset($endDate) ? $endDate : '') ?>" ></td>
                        </tr>
                        <tr>
                            <th>Event Name</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="eventName" value="<?php echo (isset($eventName) ? $eventName : "") ?>" /></td>

                        </tr>

                        <tr rowspan="2">
                            <th style="vertical-align: top;">Event Picture</th>
                            <td style="width:10%;vertical-align: top;">:</td>
                            <td><img src="image/<?php echo (isset($photoName) ? $photoName : "") ?>" width="40%" height="50%" /></td>
                        <input type="hidden" name="eventPhoto" value="<?php echo (isset($photoName) ? $photoName : "") ?>" />
                        </tr>
                        <tr>
                            <th></th>
                            <td></td>
                            <td><input type="file" name="newEventImg" /></td>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo (isset($photo) ? $photo : "") ?>" />

                        </tr>

                        <tr>
                            <th>Time</th>
                            <td style="width:10%;">:</td>
                            <td><input type="time" name="startTime" style="width:20%;" value="<?php echo (isset($startTime) ? $startTime : "") ?>" /> > <input type="time" name="endTime" style="width:20%;"  value="<?php echo (isset($endTime) ? $endTime : "") ?>" /></td>
                        </tr>

                        <tr>
                            <th style="vertical-align:top;">Description</th>
                            <td style="width:10%;vertical-align: top;">:</td>
                            <td><textarea rows="5" cols="40" id="descr" name="descr" ><?php echo (isset($description) ? $description : "") ?></textarea></td>
                        </tr>

                        <tr>
                            <th>Price(RM)</th>
                            <td style="width:10%;">:</td>
                            <td><input style="width:15%;" type="number" name="price" placeholder="0.00" value="<?php echo (isset($price) ? $price : "") ?>" min="0" max="999" step=".01" /></td>
                        </tr>
                        <tr>
                            <th>number of ticket sold</th>
                            <td style="width:10%;">:</td>
                            <td><input style="width:15%;" type="number" name="ticketNum" placeholder="0" value="<?php echo (isset($ticketNum) ? $ticketNum : "") ?>" min="0" max="9999" /></td>
                        </tr>
                        <tr>
                            <th style="vertical-align:top;">Venue</th>
                            <td style="width:10%;vertical-align: top;">:</td>
                            <td><textarea id="descr" name="venue" rows="5" cols="40" /><?php echo (isset($venue) ? $venue : "") ?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" class="button" value="Update" name="update" /><input type="button" value="Cancel" name="cancel" class="button" onclick="location = 'list-event.php'" /></td>

                        </tr>

                    </table>
                </form>
            <?php endif; ?>
        </div>

    </body>
</html>
