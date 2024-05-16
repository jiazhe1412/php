<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Product Order Page</title>
        <link href="css/EventDetail.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/helper2.php';
        ?>



        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            //Order.php?eventID=%s
            isset($_GET["eventid"]) ?
                            $event = strtoupper(trim($_GET["eventid"])) :
                            $event = "";
            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            $sql = "SELECT * FROM event WHERE eventID = '$event'";
            $result = $con->query($sql);

            if ($record = $result->fetch_object()) {
                $eventName = $record->eventName;
                $eventPhoto = $record->eventPhoto;
                $startDay = $record->startDay;
                $endDay = $record->endDay;
                $startTime = $record->startTime;
                $endTime = $record->endTime;
                $price = $record->price;
                $ticketNumber = $record->ticketNumber;
                $venue = $record->venue;
                $desc = $record->description;

                $startTime = date("H:i", $startTime);
                $endTime = date("H:i", $endTime);
                printf("
                            <form action='action' method='POST'>
                    <table class='order-content'>
                <tr>
                    <th colspan='2'><h3><a href='user_event.php'>️◀️ Back to Product Page</a></h3></th>
                </tr> 
                            
                            <tr>
                                <th colspan='2'>
                                <h1 class='title'>%s</h1><hr/>    
                            <th>
                            </tr>
                            <tr>
                                <th><img class='eventPhoto' src='image/%s'</th>
                                <td class='info'>
                                <h1>Description</h1>
                                <p class='highlight'>📅 Date: %s to %s</p>
                                <p class='highlight'>⏲ Time: %s to %s</p>
                                <p class='highlight'>📍 Location: %s</p>
                                <p class='highlight'>💵 Price: RM%s</p>
                                <p>📅 Description: %s</p>
                                </td>
                            </tr>
                           <tr>
                            <td colspan='2' class='submit'>
                              <input type='button' value='Order Now' name='ticketsubmit' onclick='location=\"EventBooking.php?eventid=%s\"'/>
                              <sub>[%s left]</sub>
                            </td>
                            
                           </tr>
                    </table>
                   
                    </form>
                     ", $eventName, $eventPhoto, $startDay, $endDay, $startTime, $endTime, $venue, $price, $desc, $event, $ticketNumber);
                $con->close();
                $result->close();
            } else {
                printf("<div class='notFound'>
                        <h1>Event not Found!</h1>
                        <a href='user_event.php'>️◀️ Back to Event Page</a>
                        </div> 
                        ");
            }
        }
        ?>

        <?php
        include './footer.php';
        ?>
    </body>
</html>
