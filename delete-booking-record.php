<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete record</title>
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
                            $hideform = false;
                            if ($_SERVER["REQUEST_METHOD"] == "GET") {


                                (isset($_GET["ticketid"])) ? $ticketID = strtoupper(trim($_GET["ticketid"])) : $ticketID = '';

                                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                                $sql = "SELECT * FROM bookingrecord WHERE ticketID = '$ticketID'";
                                $result = $con->query($sql);

                                if ($record = $result->fetch_object()) {
                                    $ticketID = $record->ticketID;
                                    $paymentID = $record->paymentID;
                                    $eventID = $record->eventID;
                                    $ticketQty = $record->ticketQty;
                                    $totalPrice = $record->totalPrice;
                                    $date = $record->bookingDate;
                                } else {
                                    //record not found
                                    echo "<div class='error'>Unable to retrieve record![<b><a href='admin_booking.php' >Back to list</a></b>]</div>";
                                    $hideform = true;
                                }
                                
                                $con->close();
                                $result->free();
                            } else {
                                //update record
                                $ticketID = $_POST['ticketID'];
                                $paymentID = $_POST['paymentID'];
                                $eventID = $_POST['eventID'];
                                $ticketQty = $_POST['qty'];
                                $price = $_POST['existingPrice'];
                                $date = $_POST['date'];
                                addBackQty($eventID, $ticketQty);

                                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                                
                                $sql = "DELETE FROM bookingrecord WHERE ticketID = ?";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("s",$ticketID);

                                if ($stmt->execute()) {
                                    //insert succesful
                                    echo "<div class='info'>Ticket [$ticketID] has been deleted.[<a href='admin_booking.php'>See Result</a>]</div>";
                                } else {
                                    echo "<div class='error'>Unable to delete!</div>";
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
                            <td><input type="text" name="eventID" value="<?php echo isset($eventID) ? $eventID : ""; ?>" readonly/></td>
                        </tr>

                        <tr>
                            <th>Ticket Quantity</th>
                            <td style="width:10%;">:</td>
                            <td><input type="text" name="qty" value="<?php echo isset($ticketQty) ? $ticketQty : ""; ?>" readonly/></td>
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
                            <td colspan="3"><input type="submit" class="button" value="Delete" name="delete" /><input type="button" value="Cancel" name="cancel" class="button" onclick="location = 'admin_booking.php'" /></td>
                        </tr>
<?php endif; ?>
                </table>

            </form>
        </div>

    </body>
</html>
