<?php

function checkPaymentID() {
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM payment";

    $result = $con->query($sql);

    $countRow = $result->num_rows;
    if ($countRow == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
            $payID = $record->paymentID;
        }
        $payID = str_split($payID);
        array_shift($payID);
        $payID = implode($payID);
        return ($payID + 1);
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
        <title>Payment</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="payment.js" type="text/javascript"></script>
        <link href="css/Cartpayment.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/paymentHelpher.php';
        ?>


        <?php
        global $hideForm;
        if (isset($_POST['sbPayment'])) {
            (isset($_SESSION['totalPrice'])) ? $price = $_SESSION['totalPrice'] : $price = "";
            $paymentMethod = "";
            $paymentID = "P" . checkPaymentID();
            $memberID = $_SESSION['memberID'];
            if (isset($_POST['btnPay']) || isset($_POST['btnPay'])) {
                if (isset($_POST['paymentMethod'])) {
                    if ($_POST['paymentMethod'] == 'Card') {
                        $paymentMethod = "Card";
                        $cardNum = trim($_POST['txtCardNum']);

                        $cardName = trim($_POST['txtCardName']);
                        $cardCVV = trim($_POST['txtCardCVV']);
                        $cardEpyDate = trim($_POST['txtCardEpyDate']);
                        $tngPhone = "NULL";
                        $tngPIN = "NULL";
                        $error['cardNum'] = checkCardNumber($cardNum);
                        $error['cardName'] = checkCardName($cardName);
                        $error['cardCvv'] = checkCardCvv($cardCVV);
                        $error['cardEpy'] = checkCardEpyDate($cardEpyDate);
                        $error = array_filter($error);

                        if (empty($error)) {
//booking record------------------------------------------------------------------------------------------------------------
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                            $sql = "SELECT * FROM bookinglist WHERE memberID='$memberID'";

                            if ($result = $con->query($sql)) {

                                while ($record = $result->fetch_object()) {
                                    deleteQty($record->eventID, $record->ticketNumberPurchase);
                                    $ticketID = "T" . getTicketID();
                                    $sql = "INSERT INTO bookingrecord VALUES(?, ?, ?, ?, ?, ?)";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param("sssdds", $ticketID, $paymentID, $record->eventID, $record->ticketNumberPurchase, $record->countPrice, $record->eventPurchaseDate);
                                    $stmt->execute();
                                }
                            }
                            $con->close();
                            $result->free();
                            $stmt->close();
                            //save in database
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                            $sql = "INSERT INTO payment VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $date = date("Y-m-d");

                            $statement = $con->prepare($sql);
                            $statement->bind_param("sssdsdsddds",
                                    $paymentID, $memberID, $paymentMethod, $cardNum, $cardName,
                                    $cardCVV, $cardEpyDate, $tngPhone, $tngPIN, $price, $date);

                            if ($statement->execute()) {
                                //updated sucessful
                                $con1 = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                                $sql1 = "DELETE FROM bookinglist WHERE memberID = ?";
                                $statement1 = $con->prepare($sql1);
                                $statement1->bind_param("s", $memberID);
                                if ($statement1->execute()) {
                                    printf("<div class='sucessful'>
                                    Completed Payment Successful ![<a href='user_event.php'>Back to Event List</a>]
                                    </div>");
                                    unset($_SESSION['totalPrice']);
                                    header('Location:user_event.php');
                                }
                                $con->close();
                                $statement->close();
                                $con1->close();
                                $statement1->close();
                            } else {
                                //fail to update
                                echo "<div class='error'>Unable to Edit!</div>
                                        [<a href='user_event.php'>Back to Event List</a>]";
                            }
                        } else {
                            printf('<div class="error">
                                    <ul>
                                    %s
                                    %s
                                    %s
                                    %s
                                    </ul>
                                </div>
                                ', isset($error['cardNum']) ? "<li>{$error['cardNum']}</li>" : "",
                                    isset($error['cardName']) ? "<li>{$error['cardName']}</li>" : "",
                                    isset($error['cardCvv']) ? "<li>{$error['cardCvv']}</li>" : "",
                                    isset($error['cardEpy']) ? "<li>{$error['cardEpy']}</li>" : "");
                        }
                    } else if ($_POST['paymentMethod'] == 'TNG') {
                        (isset($_SESSION['totalPrice'])) ? $price = $_SESSION['totalPrice'] : $price = "";
                        $paymentMethod = "TNG";
                        $cardNum = "NULL";
                        $cardName = "NULL";
                        $cardCVV = "NULL";
                        $cardEpyDate = "NULL";
                        $tngPhone = trim($_POST['txtTNGphone']);
                        $tngPIN = trim($_POST['txtTNGpin']);

                        $error['tngPhone'] = checktngPhone($tngPhone);
                        $error['tngPin'] = checktngPin($tngPIN);
                        $error = array_filter($error);

                        if (empty($error)) {
//booking record------------------------------------------------------------------------------------------------------------
                            //save in database
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                            $sql = "SELECT * FROM bookinglist WHERE memberID='$memberID'";

                            if ($result = $con->query($sql)) {

                                while ($record = $result->fetch_object()) {
                                    deleteQty($record->eventID, $record->ticketNumberPurchase);
                                    $ticketID = "T" . getTicketID();
                                    $sql = "INSERT INTO bookingrecord VALUES(?, ?, ?, ?, ?, ?)";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param("sssdds", $ticketID, $paymentID, $record->eventID, $record->ticketNumberPurchase, $record->countPrice, $record->eventPurchaseDate);
                                    $stmt->execute();
                                    $stmt->close();
                                }
                            }

                            $result->free();

                            $sql = "INSERT INTO payment VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
                            $date = date("Y-m-d");

                            $statement = $con->prepare($sql);

                            $statement->bind_param("sssdsdsddds",
                                    $paymentID, $memberID, $paymentMethod, $cardNum, $cardName,
                                    $cardCVV, $cardEpyDate, $tngPhone, $tngPIN, $price, $date);

                            if ($statement->execute()) {
                                //updated sucessful
                                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                                $sql1 = "DELETE FROM bookinglist WHERE memberID = ?";
                                $statement1 = $con->prepare($sql1);
                                $statement1->bind_param("s", $memberID);
                                if ($statement1->execute()) {
                                    printf("<div class='sucessful'>
                                    Completed Payment Successful ![<a href='user_event.php'>Back to Event List</a>]
                                    </div>");
                                    unset($_SESSION['totalPrice']);
                                    header('Location:user_event.php');
                                }
                                $con->close();
                                $statement->close();
                                $con1->close();
                                $statement1->close();
                            } else {
                                //fail to update
                                echo "<div class='error'>Unable to Edit!</div>
                                        [<a href='user_event.php'>Back to Event List</a>]";
                            }
                        } else {
                            printf('<div class="error">
                                    <ul>
                                    %s
                                    %s
                                    </ul>
                                </div>
                                ', isset($error['tngPhone']) ? "<li>{$error['tngPhone']}</li>" : "",
                                    isset($error['tngPin']) ? "<li>{$error['tngPin']}</li>" : "",
                            );
                        }
                    }
                }
            }
        } else {
            echo "<div class='errorPayment'>
              Unable to Make Payment.
              <a href='ListBooking.php'>Back to Cart Page.</a>
                    </div>";
            $hideForm = true;
        }
        ?>
        <?php if ($hideForm == false): ?>
            <div class='payment'>
                <h1 style="text-align: center;">Payment</h1>
                <form action="" method="POST">

                    <input type="hidden" name="sbPayment" value="true">
                    <table>
                        <?php
                        printf("
                            <tr>
                            <th>Total Price: </th>
                            
                            <td>%s</td>
                            </tr>
                                ", $price);
                        ?>
                        <tr>
                            <th>Payment Method: </th>

                            <td><input id="card" type="radio" name="paymentMethod" value="Card" <?php echo ($paymentMethod == 'Card') ? 'checked' : '' ?>/>Credit/Debit Card</td>
                            <td><input id="tng" type="radio" name="paymentMethod" value="TNG" <?php echo ($paymentMethod == 'TNG') ? 'checked' : '' ?>/>Touch and Go</td>
                        </tr>
                    </table>
                    <div  id='cardInfo' style='display:none'>
                        <table>
                            <tr>
                                <th>Enter Credit Card Number(16 numbers):  </th>
                                <td><input type="text" name="txtCardNum" placeholder='XXXXXXXXXXXXXXXX' value="<?php echo (isset($cardNum)) ? $cardNum : ""; ?>" /></td>
                            </tr>
                            <tr>
                                <th> Enter Card policyholder Name:</th>
                                <td><input type="text" name="txtCardName" value="<?php echo (isset($cardName)) ? $cardName : "" ?>" /></td>
                            </tr>
                            <tr>
                                <th>Enter CVV (3 numbers):</th>
                                <td><input type="text" name="txtCardCVV" placeholder='XXX' value="<?php echo (isset($cardCVV)) ? $cardCVV : "" ?>" /></td>
                            </tr>
                            <tr>
                                <th>Enter Expiry Date:  </th>
                                <td><input type="text" name="txtCardEpyDate" placeholder='MM/YY' value="<?php echo (isset($cardEpyDate)) ? $cardEpyDate : "" ?>" /></td>
                            </tr>
                            <tr>
                                <td class="paymentbtn"><input type="button" value="Cancel" name='btnCancel' onclick='location = "ListBooking.php"'/>
                                    <input type="submit" value="Complete Payment" name='btnPay'/></td>
                            </tr>
                        </table>
                    </div>

                    <div id='tngInfo' style='display:none'>
                        <table>
                            <tr>
                                <th>Enter TNG PHONE NUMBER: </th>
                                <td><input type="text" name="txtTNGphone" placeholder='Without Dash' value="<?php echo (isset($tngPhone)) ? $tngPhone : "" ?>" /></td>
                            </tr>
                            <tr>
                                <th>Enter PIN NUMBER:</th> 
                                <td><input type="text" name="txtTNGpin" placeholder='123456' value="<?php echo (isset($tngPIN)) ? $tngPIN : "" ?>" /></td>
                            </tr>
                            <tr>
                                <td class="paymentbtn"><input type="button" value="Cancel" name='btnCancel' onclick='location = "ListBooking.php"'/>
                                    <input type="submit" value="Complete Payment" name='btnPay'/></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        <?php
        include './footer.php';
        ?>
    </body>
</html>
