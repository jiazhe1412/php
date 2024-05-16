<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>

    <head>
        <meta charset="UTF-8">
        <title>Edit Booking Record</title>
        <link href="css/edit-delete.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        ?>
        <div class='top'><h2>Edit Booking Record</h2></div>
        <div id="detail">
            <form method="POST">
                <table>
                    <tr>
                        <td colspan="3">
                            <?php
                            global $ticketQty,$eventID;
                            $hideform = false;
                            if ($_SERVER["REQUEST_METHOD"] == "GET") {


                                (isset($_GET["ticketid"])) ? $ticketID = strtoupper(trim($_GET["ticketid"])) : $ticketID = '';

                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                                $sql = "SELECT * FROM bookingrecord WHERE ticketID = '$ticketID'";
                                $result = $con->query($sql);

                                if ($record = $result->fetch_object()) {
                                    $ticketID = $record->ticketID;
                                    $paymentID = $record->paymentID;
                                    $eventID = $record->eventID;
                                    $ticketQty = $record->ticketQty;
                                    $totalPrice = $record->totalPrice;
                                    $date = $record->bookingDate;
                                    addBackQty($eventID, $ticketQty);
                                } else {
                                    //record not found
                                    echo "<div class='error'>Unable to retrieve record![<b><a href='admin_booking.php' >Back to list</a></b>]</div>";
                                    $hideform = true;
                                }
                                $price = $totalPrice;
                                $con->close();
                                $result->free();
                            } else if(isset($_POST['cancel'])){
                                (isset($_GET["ticketid"])) ? $ticketID = strtoupper(trim($_GET["ticketid"])) : $ticketID = '';

                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                                $sql = "SELECT * FROM bookingrecord WHERE ticketID = '$ticketID'";
                                $result = $con->query($sql);

                                if ($record = $result->fetch_object()) {
                                    $ticketID = $record->ticketID;
                                    $paymentID = $record->paymentID;
                                    $eventID = $record->eventID;
                                    $ticketQty = $record->ticketQty;
                                    $totalPrice = $record->totalPrice;
                                    $date = $record->bookingDate;
                                    deleteQty($eventID, $ticketQty);
                                }
                                
                                $con->close();
                                $result->free();
                                header("Location:admin_booking.php");
                            }
                            
                            else {
                                
                                
                                //update record
                                $ticketID = $_POST['ticketID'];
                                $paymentID = $_POST['paymentID'];
                                $eventID = $_POST['eventID'];
                                $ticketQty = $_POST['qty'];
                                $price = $_POST['existingPrice'];
                                $date = $_POST['date'];
                                deleteQty($eventID, $ticketQty);
                                $error = array();

                                $error['eventid'] = eventIDvalidation($eventID);
                                $error['qty'] = checkQty($ticketQty);

                                $error = array_filter($error);

                                if (empty($error)) {
                                    //update record,no error
                                    $totalPrice = calculatePrice($eventID, $ticketQty);
                                    $checkprice = checkTotalPrice($price, $eventID, $ticketQty);
                                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                                    $sql = "UPDATE bookingrecord SET paymentID = ?,eventID = ?,ticketQty = ?, totalPrice = ?, bookingDate = ? WHERE ticketID = ?";

                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param("ssddss", $paymentID, $eventID, $ticketQty, $totalPrice, $date, $ticketID);

                                    if ($stmt->execute()) {
                                        //insert succesful
                                        echo "<div class='info'>Ticket [$ticketID] has been updated.[<a href='admin_booking.php'>See Result</a>]<br />";
                                        echo "$checkprice";
                                        echo "</div>";
                                        echo"<tr><td><br /></td></tr>";
                                    } else {
                                        echo "<div class='error'>Unable to update!</div>";
                                    }
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
                        <tr>
                            <th>Ticket ID</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="ticketID" value="<?php echo isset($ticketID) ? $ticketID : ""; ?>" readonly/></td>
                        </tr>

                        <tr>
                            <th>Payment ID</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="paymentID" value="<?php echo isset($paymentID) ? $paymentID : ""; ?>" readonly/></td>
                        </tr>

                        <tr>
                            <th>Event ID</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="eventID" value="<?php echo isset($eventID) ? $eventID : ""; ?>" /></td>
                        </tr>

                        <tr>
                            <th>Ticket Quantity</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="qty" value="<?php echo isset($ticketQty) ? $ticketQty : ""; ?>" /></td>
                        </tr>

                        <tr>
                            <th>Total price (RM)</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="price" value="<?php echo isset($totalPrice) ? $totalPrice : ""; ?>" readonly/></td>
                        <input type="hidden" name="existingPrice" value="<?php echo isset($price) ? $price : ""; ?>" />
                        </tr>

                        <tr>
                            <th>Booking Date</th>
                            <td style="width:10%;">:</td>
                            <td><input type="date" name="date" value="<?php echo isset($date) ? $date : ""; ?>" readonly/></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" class="button" value="Update" name="update" /><input type="submit" value="Cancel" name="cancel" class="button"></td>
                        </tr>
                    <?php endif; ?>
                </table>

            </form>
        </div>

    </body>
</html>
