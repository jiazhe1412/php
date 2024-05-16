<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Product</title>
        <link href="css/edit-delete.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class='top'><h2>Delete Product</h2></div>
        <div id="detail">
            <form method="POST">
                <table>
                    <tr>
                        <td colspan="3">
                            <?php
                            include './admin_header.php';
                            require_once './config/database_connection.php';
                            ?>
                            <?php
                            $hideform = false;
                            if ($_SERVER["REQUEST_METHOD"] == "GET") {


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

                                    $result->free();
                                    $con->close();
                                } else {
                                    //record not found
                                    echo "<div class='error'>Unable to retrieve record![<b><a href='list-event.php' >Back to list</a></b>]</div>";
                                    $hideform = true;
                                }
                            } else {
                                //post method
                                //delete the record
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

                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                                $sql = "DELETE FROM event WHERE eventID = ?";

                                $stmt = $con->prepare($sql);

                                $stmt->bind_param('s', $eventID);

                                if ($stmt->execute()) {
                                    //deleted
                                    echo "<div class='info'>Student $eventName has been deleted.[<a href='list-event.php'>See result</a>]</div>";
                                } else {
                                    //unable to delete
                                    echo "<div class='error'>Unable to delete![<a href='list-event.php'>Back to list</a>]</div>";
                                }
                            }
                            ?>

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
                            <th>Product Name</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="eventName" value="<?php echo (isset($eventName) ? $eventName : "") ?>" /></td>

                        </tr>

                        <tr rowspan="2">
                            <th style="vertical-align: top;">Product Picture</th>
                            <td style="width:10%;vertical-align: top;">:</td>
                            <td><img src="image/<?php echo (isset($photoName) ? $photoName : "") ?>" width="40%" height="50%" /></td>
                        <input type="hidden" name="eventPhoto" value="<?php echo (isset($photoName) ? $photoName : "") ?>" />
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
                            <th>stock quantity</th>
                            <td style="width:10%;">:</td>
                            <td><input style="width:15%;" type="number" name="ticketNum" placeholder="0" value="<?php echo (isset($ticketNum) ? $ticketNum : "") ?>" min="0" max="9999" /></td>
                        </tr>
                        <tr>
                            <th style="vertical-align:top;">Venue</th>
                            <td style="width:10%;vertical-align: top;">:</td>
                            <td><textarea id="descr" name="venue" rows="5" cols="40" /><?php echo (isset($venue) ? $venue : "") ?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" class="button" value="Delete" name="delete" /><input type="button" value="Cancel" name="cancel" class="button" onclick="location = 'list-event.php'" /></td>

                        </tr>

                    </table>
                </form>
            <?php endif; ?>
        </div>
    </body>
</html>
