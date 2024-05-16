<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Booking</title>
        <link href="css/EditBooking.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/helper2.php'
        ?>
        <h1 style="text-align:center;font-family:cursive;">Edit Booking</h1>
        <?php
        global $hideForm;
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            isset($_GET["bookingID"]) ? $bookingID = strtoupper(trim($_GET["bookingID"])) : $bookingID = "";
            $con1 = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
            $sql1 = "SELECT * FROM bookinglist WHERE bookingID = '$bookingID'";
            $result1 = $con1->query($sql1);

            if ($record1 = $result1->fetch_object()) {
                //record found
                $bookingid = $record1->bookingID;
                $memberID = $record1->memberID;
                $eventid = $record1->eventID;
                $ticketPurchase = $record1->ticketNumberPurchase;
                $unitPrice = $record1->unitPrice;
                $eventPurchase = $record1->eventPurchaseDate;
            } else {
                //record not found
                echo "<div class='error'>Unable to retrieve record![<a href='ListBooking.php'>Back to Booking list</a>]</div>";
                $hideForm = true;
            }
            $result1->free();
            $con1->close();

            isset($_GET["eventID"]) ? $eventID = strtoupper(trim($_GET["eventID"])) : $eventID = "";
            $con2 = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
            $sql2 = "SELECT * FROM event WHERE eventID = '$eventID'";
            $result2 = $con2->query($sql2);

            $startDay = "";
            $endDay = "";

            if ($record2 = $result2->fetch_object()) {
                $unitPrice = $record2->price;
                $startDay = $record2->startDay;
                $endDay = $record2->endDay;
//                $datebegin = new DateTime("$startDay");
//                $dateend = new DateTime("$endDay");
            }
            $result2->free();
            $con2->close();
        } else {
            $startDay = $_POST['hdstartdate'];
            $endDay = $_POST['hdenddate'];

            //POST
            $bookingID = strtoupper(trim($_POST['hdBookingID']));
            $eventID = trim($_POST['eventSelect']);
            isset($_POST['txtMemberID']) ?
                            $memberID = strtoupper(trim($_POST['txtMemberID'])) :
                            $memberID = "";
            $ticketPurchase = trim($_POST['txtTicketPc']);
            $unitPrice = trim($_POST['hdUnitPrice']);
            $eventDatePurchase = trim($_POST['dateSelect']);
            $countPrice = $ticketPurchase * $unitPrice;

            $error['ticketPurchase'] = checkTicketPurchase($ticketPurchase);
            $error = array_filter($error);

            if (empty($error)) {
                //No error
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                $sql = "UPDATE Bookinglist SET eventID = ?, ticketNumberPurchase = ?, eventPurchaseDate = ?, countPrice = ? WHERE bookingID = ?";

                $statement = $con->prepare($sql);

                $statement->bind_param("ssssd", $eventID, $ticketPurchase, $eventDatePurchase, $countPrice, $bookingID);

                if ($statement->execute()) {
                    //updated sucessful
                    header('Location:ListBooking.php');
                    printf("<div class='sucessful'>
                            Booking Event Detail Successful Update![<a href='ListBooking.php'>Back to Booking List</a>]
                            </div>");
                } else {
                    //fail to update
                    echo "<div class='error'>Unable to Edit!</div>
                    [<a href='ListBooking.php'>Back to Student List</a>]";
                }
                $con->close();
                $statement->close();
            } else {
                //same insert page
                //WITH ERROR, DISPLAY ERROR
                echo "<ul class='error'>";
                foreach ($error as $value) {
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
        ?>



        <?php if ($hideForm == false): ?>
            <form action="" method="POST">
                <table class='editbooking'>
                    <tr>
                        <td>Booking ID:</td>
                        <td><?php echo $bookingID; ?>
                            <input type="hidden" name="hdBookingID" value="<?php echo $bookingID; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Member ID:</td>
                        <td><?php echo $memberID; ?>
                            <input type="hidden" name="hdMemberID" value="<?php echo $memberID; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Event ID:</td>
                        <td><?php
                            echo "<select name='eventSelect'>";
                            $events = getAllEvent();
                            foreach ($events as $eventID => $eventName) {
                                printf("<option value='$eventID'>$eventName</option>");
                            }
                            echo "</select>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Ticket Purchase:</td>
                        <td><input type="text" name="txtTicketPc" value="<?php echo $ticketPurchase ?>" /></td>
                    </tr>
                    <tr>
                        <td>Unit Price(RM):</td>
                        <td><?php echo $unitPrice; ?>
                            <input type="hidden" name="hdUnitPrice" value="<?php echo $unitPrice; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Event Date Purchase</td>
                        <td><?php
                            printf('<input type="hidden" name="hdstartdate" value="%s" />
                                    <input type="hidden" name="hdenddate" value="%s" />', $startDay, $endDay);

                            $alldate = getAllDate($startDay, $endDay);
                            echo "<select name='dateSelect' id='dateSelect'>";
                            foreach ($alldate as $date) {
                                printf("<option value='%s'>%s</option>", $date['first'], $date['last']);
                            }
                            echo "</select>";
                            ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="editbtn"><input type="submit" value="Update" name="btnUpdate" />
                            <input type="button" value="Cancel" name="btnCancel" onclick="location = 'ListBooking.php'"/></td>
                    </tr>
                </table>

            </form>
        <?php endif; ?>


        <?php
        include './footer.php';
        ?>
    </body>
</html>
