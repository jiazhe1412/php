<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Add to Cart</title>
        <link href="css/ListBooking.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/helper2.php';

        $header = array(
            "eventName" => "Event Name",
            "ticketNumberPurchase" => "Ticket Purchase",
            "unitPrice" => "Unit Price (RM)",
            "eventPurchaseDate" => "Event Date Booking",
            "countPrice" => "Count Price"
        );

        if (isset($_GET['event'])) {
            $event = (array_key_exists($_GET['event'], getAllEvent()) ? $_GET['event'] : "%");
        } else {
            $event = "%";
        }
        ?>
        <h1 style='text-align:center;font-family:cursive;'>Add to Cart</h1>


        <form method="POST" action='Cartpayment.php'>

            <table class="listbooking">

                <?php
                (isset($_SESSION['memberID'])) ? $memberID = $_SESSION['memberID'] : $memberID = "";
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                $sql = "SELECT * FROM bookinglist WHERE memberID='$memberID'";

                if ($result = $con->query($sql)) {
                    if ($result->num_rows > 0) {

                        echo "<tr>";
                        foreach ($header as $key => $value) {

                            printf("<th><a>%s</a></th>", $value);
                        }

                        echo "<th style='width:20%;'>&nbsp;</th>";
                        echo "</tr>";
                        while ($record = $result->fetch_object()) {
                            $eventName = getEventName($record->eventID);
                            printf("<tr>
                            
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>[<a href='EditBooking.php?bookingID=%s&eventID=%s'>Edit</a>]|[<a href='DeleteBooking.php?bookingID=%s&eventID=%s'>Delete</a>]</td>
                                </tr>
                                ", $eventName, $record->ticketNumberPurchase, $record->unitPrice, $record->eventPurchaseDate, $record->countPrice, $record->bookingID, $record->eventID, $record->bookingID, $record->eventID);
                        }




                        $con1 = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                        $sql1 = "SELECT SUM(countPrice) FROM bookinglist WHERE memberID='$memberID'";

                        if ($result1 = $con1->query($sql1)) {

                            while ($record1 = $result1->fetch_object()) {

                                $countPrice = $record1->{"SUM(countPrice)"};
                                printf("<tr>
                                <td colspan='4'>
                                Total:
                                </td>
                                <td>
                                %s
                                </td>
                                </tr>
                            
                            ", $countPrice);

                                $_SESSION['totalPrice'] = $countPrice;
                            }
                        }

                        //printf("<tr><td colspan='7'>%d record returned.</td></tr>", $result->num_rows);

                        $result->free();
                        $con->close();
                        $result1->free();
                        $con1->close();

                        printf("
                        <tr>
                        <td colspan='4'>&nbsp;</td>
                        
                            <td colspan='2' class='button'>
                            <input type='button' value='Back to Event Page' onclick='location=\"user_event.php\"'/>
                            <input type='submit' value='Go to Pay' name='sbPayment'/></td>
                        </tr>
                            ");
                    } else {
                        echo "<div class='errorEvent'>No Booking Added!<br/>[<a href='user_event.php'>Back to Event</a>]</div>";
                    }
                }
                ?>
            </table>
        </form>
        <?php
        include './footer.php';
        ?>
    </body>
</html>
