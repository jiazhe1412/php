<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Booking</title>
        <link href="css/DeleteBooking.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/helper2.php';
        ?>

        <h1 style='text-align:center;font-family:cursive;'>Delete Student</h1>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            isset($_GET["bookingID"]) ? $bookingID = strtoupper(trim($_GET["bookingID"])) : $bookingID = "";
            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
            $sql = "SELECT * FROM bookinglist WHERE bookingID = '$bookingID'";
            $result = $con->query($sql);

            if ($record = $result->fetch_object()) {
                //record found
                $bookingid = $record->bookingID;
                $memberid = $record->memberID;
                $eventid = $record->eventID;
                $ticketPurchase = $record->ticketNumberPurchase;
                $unitPrice = $record->unitPrice;
                $eventPurchase = $record->eventPurchaseDate;

                printf("<div class='deleteBooking'>
                        <h3 style='color:red;'>Are you sure you want to delete the Booking?</h3>
                        <table>
                        <tr>
                            <th>Booking ID:</th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Member ID:</th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Event ID:</th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Ticket Purchase:</th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Unit Price:</th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Event Date Purchase:</th>
                            <td>%s</td>
                        </tr>
                        </table>
                        <form action='' method='POST'>
                        <input type='hidden' name='hdBookingID' value='%s' />
                        <input type='submit' value='Delete Booking' name='btnBookingDelete' />
                        <input type='button' value='Cancel' name='btnBookingCancel' onclick='location = \"ListBooking.php\"'/>
                        </form>
                        </div>", $bookingid, $memberid, $eventid, $ticketPurchase, $unitPrice, $eventPurchase, $bookingid);
            
                $result->free();
                $con->close();
                
                
            } else {
                //record not found
                echo "<div class='error'>Unable to retrieve record![<a href='ListBooking.php'>Back to Booking list</a>]</div>";
            }
        } else {
            //POST
            $bookid = $_POST['hdBookingID'];
            
            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
            
            $sql = "DELETE FROM bookinglist WHERE bookingID = ?"; 
            
            $stmt = $con->prepare($sql);
            
            $stmt->bind_param('s', $bookid);
            
            if($stmt->execute()){
                //deleted
                 printf("<div class='sucess'>%s has been deleted.
                            [<a href='ListBooking.php'>Back to Booking List</a>]
                             </div>", $bookid);
            }else{
                //unable to delete
                echo "<div class='error'>Unable to Delete Booking!</div>
                    [<a href='ListBooking.php'>Back to Bookiing List</a>]";
            }
            $con->close();
            $stmt->close();
        }
        ?>



        <?php
        include './footer.php';
        ?>
    </body>
</html>
