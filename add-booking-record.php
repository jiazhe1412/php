

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>testing</title>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/database_connection.php';
        ?>

        <?php
        if (isset($_POST['payment'])) {
            //get method            
            //retreive data and display in the form
            //retrieve the id from the url
            $paymentID = $_POST['paymentID'];
            $date = date("Y-m-d");
            if (isset($_SESSION['eventQty']) && isset($_SESSION['eventDate']) && $_SESSION['eventName'] && isset($_SESSION['price']) && isset($_SESSION['eventID']) && isset($_SESSION['memberID'])) {
                //retrieve session



                $memberID = $_SESSION['memberID'];
                $allticket = $_SESSION['eventQty'];
                $alldate = $_SESSION['eventDate'];
                $allname = $_SESSION['eventName'];
                $allprice = $_SESSION['price'];

                foreach ($allname as $key => $value) {
                    $ticketID = "T" . getTicketID();
                    $totalPrice = $allticket[$key] * $allprice[$key];
                    $eventID = findEventID($allname[$key]);

                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

                    $sql = "INSERT INTO bookingrecord VALUES (?, ?, ?, ?, ?, ?)";

                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('sssdds', $ticketID, $paymentID, $eventID, $allticket[$key], $totalPrice, $date);
                    $stmt->execute();
                    
                    deleteQty($eventID,$allticket[$key]);
                    $con->close();
                    $stmt->close();
                }
            }
        }
        ?>
        <table>
            <tr>
                <th>ticket id:</th>
                <td><?php echo (isset($ticketID)) ? $ticketID : "" ?></td>
            </tr>

            <tr>
                <th>payment id:</th>
                <td><?php echo (isset($paymentID)) ? $paymentID : "" ?></td>
            </tr>

            <tr>
                <th>event id:</th>
                <td><?php echo (isset($eventID)) ? $eventID : "" ?></td>
            </tr>

            <tr>
                <th>member id:</th>
                <td><?php echo (isset($memberID)) ? $memberID : "" ?></td>
            </tr>

            <tr>
                <th>total price(RM):</th>
                <td><?php echo (isset($totalPrice)) ? $totalPrice : "" ?></td>
            </tr>

            <tr>
                <th>date:</th>
                <td><?php echo (isset($date)) ? $date : "" ?></td>
            </tr>


        </table>


    </body>
</html>
