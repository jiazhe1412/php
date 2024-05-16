<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Booking record-admin</title>
        <link href="css/admin_booking.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class='top'><h2>Booking Record</h2></div>
        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';
        
        $eventHeader = array(
            "ticketID" => "Ticket ID",
            "paymentID" => "Payment ID",
            "eventID" => "Event ID",
            "ticketQty" => "Ticket quantity",
            "totalPrice" => "Total Price(RM)",
            "bookingDate" => "Date"    
        );

        global $sort, $type;
        //check $type $sort variable -> prevent sql error
        //which column to sort
        if (isset($_GET['type']) && isset($_GET['sort'])) {
            $type = (array_key_exists($_GET['type'], $eventHeader) ? $_GET['type'] : 'eventID');
            //how to arrange order sequence ASC/DESC
            $sort = ($_GET['sort'] == 'DESC') ? 'DESC' : 'ASC';
        } else {
            $type = "ticketID";
            $sort = "ASC";
        }
        ?>
        <div class="recordList">
            <div id="searchBar">
                <div id="heading">
                    <h2 id="record">Record</h2>
                </div>
                <form action="" method="post">
                    <input type="text" name="ticketID" placeholder="Ticket ID" value=""/>
                    <input type="text" name="paymentID" placeholder="Payment ID" value=""/>
                    <input type="text" name="eventID" placeholder="Event ID" value=""/>
                    <input type="submit" name="search" value="🔎"/>
                </form>
                <table>
                    <tr>
                    <?php
                    
                     foreach ($eventHeader as $key => $value) {
                        if ($key == $type) {
                            printf("
                                <th>
                                <a href='?type=%s&sort=%s' class = 'header'>%s</a>%s
                                </th>
                                
                                ", $key
                                    , $sort == 'ASC' ? 'DESC' : 'ASC'
                                    , $value
                                    , $sort == 'ASC' ? '🔽' : '🔼');
                        } else {
                            printf("
                                <th>
                                <a href='?type=%s&sort=ASC' class = 'header'>%s</a>
                                </th>
                                ", $key
                                    , $value);
                        }
                    }
                     echo "</tr>";
                    
                    if (isset($_POST['search'])) {


                        $ticketID = trim($_POST['ticketID']);
                        $paymentID = trim($_POST['paymentID']);
                        $eventID = trim($_POST['eventID']);

                        if ($ticketID == NULL && $paymentID == NULL && $eventID == NULL) {
                            echo "<div class='error'>No input found.Please search again.[<a href='admin_booking.php'>See all record</a>]</div>";
                        } else {
                            if ($ticketID == NULL) {
                                $ticketID = "%";
                            }

                            if ($paymentID == NULL) {
                                $paymentID = "%";
                            }

                            if ($eventID == NULL) {
                                $eventID = "%";
                            }

                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                            $sql = "SELECT * FROM bookingrecord WHERE ticketID LIKE '$ticketID' AND paymentID LIKE '$paymentID' AND eventID LIKE '$eventID' ORDER BY $type $sort";

                            $result = $con->query($sql);

                            if ($result->num_rows != 0) {
                                //record found
                                while ($record = $result->fetch_object()) {
                                    printf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
                        <td>%s</td>
                        
<td><a href='edit-booking-record.php?ticketid=%s'  class = 'header'>Edit</a> || <a href='delete-booking-record.php?ticketid=%s'  class = 'header'>Delete</a></td>
</tr>
                        ", $record->ticketID,
                                            $record->paymentID,
                                            $record->eventID,
                                            $record->ticketQty,
                                            $record->totalPrice,
                                            $record->bookingDate,
                                            $record->ticketID,
                                            $record->ticketID
                                    );
                                }

                                $con->close();
                                $result->free();
                            } else {
                                echo "<div class='error'>No record found.Please search again.[<a href='admin_booking.php'>See all record</a>]</div>";
                            }
                        }
                    } else {
                        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                        $sql = "SELECT * FROM bookingrecord ORDER BY $type $sort";

                        $result = $con->query($sql);

                        while ($record = $result->fetch_object()) {

                            printf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
                        <td>%s</td>
                        
<td><a href='edit-booking-record.php?ticketid=%s'  class = 'header'>Edit</a> || <a href='delete-booking-record.php?ticketid=%s'  class = 'header'>Delete</a></td>
</tr>
                        ", $record->ticketID,
                                    $record->paymentID,
                                    $record->eventID,
                                    $record->ticketQty,
                                    $record->totalPrice,
                                    $record->bookingDate,
                                    $record->ticketID,
                                    $record->ticketID
                            );
                        }

                        $con->close();
                        $result->free();
                    }
                    ?>

                </table>
            </div>
        </div>
    </body>
</html>
