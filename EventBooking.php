<?php

function checkBookingID() {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM bookinglist";

    $result = $con->query($sql);

    $countRow = $result->num_rows;
    if ($countRow == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
            $bookID = $record->bookingID;
        }
        $bookID = str_split($bookID);
        array_shift($bookID);
        array_shift($bookID);
        $bookID = implode($bookID);
        return ($bookID + 1);
    }
    $con->close();
    $result->close();
}
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Event Booking Page</title>
        <link href="css/eventBooking.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php
        include './header.php';
        require_once './config/helper2.php';
        ?>



        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            (isset($_GET['eventid'])) ?
                            $id = $_GET['eventid'] :
                            $id = "";

            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
            $sql = "SELECT * FROM event WHERE eventID = '$id'";
            $result = $con->query($sql);

            if ($record = $result->fetch_object()) {
                $startDay = $record->startDay;
                $endDay = $record->endDay;
                $EventTitle = $record->eventName;
                $eventPhoto = $record->eventPhoto;
                $datebegin = new DateTime("$startDay");
                $dateend = new DateTime("$endDay");
                $price = $record->price;
                printf('
                        <table class="booking">
                        <form action="" method="POST">
                            <tr>
                            <th><h3 class="backEvent"><a href="user_event.php">️◀️ Back to Event Page</a></h3></th>
                            </tr>
                            
                            <tr>
                            <td class="title"><h1>%s</h1><hr/></td>
                            <input type="hidden" name="hdTitle" value="%s" />
                            <input type="hidden" name="hdPrice" value="%s" />
                            </tr>
                            <tr>
                            <th><img class="eventPhoto" src="image/%s"</th>
                            </tr>
                    <tr>
                <td class="orderDetail">Select Ticket Quantity: </td>
                <td><select name="ticketselect" id="quantity">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select></td>
            </tr>    
                        
                    ', $EventTitle, $EventTitle, $price, $eventPhoto);
                echo "<tr> <td class='orderDetail'> Select Date: </td>";
                echo "<td>";

                echo "<select name='dateSelect' id='dateSelect'>";
                for ($i = $datebegin; $i <= $dateend; $i->modify('+1 day')) {
                    printf("<option value='%s'>%s</option>
                                ", $i->format("Y-m-d"), $i->format("Y-m-d"));
                }
                echo "</select>";
                echo "</td> </tr>";

                printf("

                    <tr>
                   <td colspan='2' class='submit'>
                  <input type='submit' value='Book the Product' name='btnBook' '/>   
                  <input type='button' value='Back to Product' onclick='location=\"user_event.php\"'/>
                    </td>
                    </tr>
                    
                        "); /* onclick='alert(\"Your Booking is Sucessed\"); */

                echo "</form>
                    </table>";
            } else {
                echo "<div class='errorEvent'>Unable to Found the Product!<br/>[<a href='user_event.php'>Back to Event</a>]</div>";
            }
        }
        ?>
        <?php
        isset($_POST['hdTitle']) ?
                        $title = $_POST['hdTitle'] :
                        $title = "";
        isset($_POST['hdPrice']) ?
                        $price = $_POST['hdPrice'] :
                        $price = "";

        (isset($_GET['eventid'])) ?
                        $id = $_GET['eventid'] :
                        $id = "";
        $_SESSION['eventID'] = $id;

        if (isset($_POST['ticketselect']) && isset($_POST['dateSelect'])) {
            //ticket session
            $ticketQuantity = $_POST['ticketselect'];
            $_SESSION['eventQty'] = $ticketQuantity;

            //date session
            $ticketDate = $_POST['dateSelect'];
            $_SESSION['eventDate'] = $ticketDate;

            //Name session
            $_SESSION['eventName'] = $title;

            $_SESSION['price'] = $price;

            printf("<table class='orderList'>
               <form method='POST' action=''>
                    <tr>
                            <th colspan='2'>%s</th>
                    </tr>
                    <tr>
                    <th style='color:green'>Your Booking List = </th>
                    </tr>
                        <tr>
                            <td>Total Ticket Quantity Book :</td>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <td>Ticket Date Book :</td>
                            <td>%s</td>
                            
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        <td class='cart'><input type='submit' value='Add To Cart' name='addCart'/>&nbsp;
                        <input type='button' value='Cancel' name='btncancel' onclick='location=\"user_event.php\"'/></td>
                        </tr>
                        </form>
                    </table>
                    ", $title, $ticketQuantity, $ticketDate);
        }

        if (isset($_POST['addCart'])) {

            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);  //

            $eventID = $_SESSION['eventID'];
            $ticketQty = $_SESSION['eventQty'];
            $dateTicket = $_SESSION['eventDate'];
            $nameEvent = $_SESSION['eventName'];
            $priceEvent = $_SESSION['price'];
            //insert the data to filter the record based on member id
            $memberID = $_SESSION['memberID'];

            $sql = "SELECT * FROM bookinglist WHERE eventID = '$eventID'";
            $record = $con->query($sql);
            $count = 0;
            if ($result = $record->fetch_object()) {
                while ($result = $record->fetch_object()) { //
                    if ($result->eventID == $eventID && $memberID == $result->memberID) {
                        $sql = "UPDATE bookinglist SET ticketNumberPurchase = ticketNumberPurchase + $ticketQty,countPrice = ticketNumberPurchase * unitPrice WHERE eventID = '$eventID' AND memberID='$memberID'";
                        $con->query($sql);
                        printf("
                           <div class='bookingList'>   
                           
                           Sucessful Add to Cart.
                           <input type='submit' value='Go to Cart' name='btnCart' onclick='location=\"ListBooking.php\"'/>

                           </div>
                        ");
                        $count++;
                    }
                }
                $result->free();
                $record->close(); //
            }
            if ($count == 0) {


                $bookingID = "BK" . checkBookingID();

                $total = 0;

                $countPrice = $ticketQty * $priceEvent;
                $total += $countPrice;

                $sql = "INSERT INTO bookinglist VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param('sssddsd', $bookingID, $memberID, $eventID, $ticketQty, $priceEvent, $dateTicket, $countPrice);

                if ($stmt->execute()) {
                    printf("
                           <div class='bookingList'>   
                           
                           Sucessful Add to Cart.
                           <input type='submit' value='Go to Cart' name='btnCart' onclick='location=\"ListBooking.php\"'/>

                           </div>
                        ");
                } else {
                    echo "Fail to Add to Cart!";
                }
//                $stmt->execute();
                $con->close();
                $stmt->close();
            }
        }
        ?>
        <?php
        include './footer.php';
        ?>
    </body>
</html>
