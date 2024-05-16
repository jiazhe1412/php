<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

define('DB_HOST', 'dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com');
define('DB_USER', 'nbuser');
define('DB_PASS', '12345678');
define('DB_NAME', 'music');
define('DB_PORT', '3306');

function checkCardNumber($cardNum) {
    if ($cardNum == NULL) {
        return "Please Enter <b>Card Number</b>";
    } else if (!preg_match('/^[0-9]{16}$/', $cardNum)) {
        return "Invalid <b>card number</b>. Must be <b>16 digits</b> and contain <b>only numbers</b>.";
    }
}

function checkCardName($cardName) {
    if ($cardName == NULL) {
        return "Please Enter <b>Card Name</b>";
    } else if (!preg_match('/^[a-zA-Z ]{1,50}$/', $cardName)) {
        return "Card Name must contain only <b>alphabets</b> and should <b>not exceed 50 characters</b>.";
    }
}

function checkCardCvv($cardCVV) {
     if ($cardCVV == NULL) {
         return "Please Enter <b>Card Cvv</b>";
     }else if(!preg_match('/^[0-9]{3}$/', $cardCVV)){
         return "Invalid <b>card cvv</b>. Must be <b>3 digits</b> and contain <b>only numbers</b>.";
     }
}

function checkCardEpyDate($cardEpyDate){
    if ($cardEpyDate == NULL) {
        return "Please Enter <b>Card Expiry Date</b>";
    }else if(!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $cardEpyDate)){ 
        return "Incorret <b>Expiry Date</b>, the correct format is <b>MM/YY</b>";
    }
}

function checktngPhone($tngPhone){
    if ($tngPhone == NULL) {
        return "Please Enter <b>Card Expiry Date</b>";
    }else if(!preg_match('/^(01)[0-9]{8,9}$/', $tngPhone)){
        return "Phone Number must <b>only number</b> and <b>contain 10 or 11 characters</b>. The Format is <b>01234567890 Without Dash</b>.";
    }
} 

function checktngPin($tngPin){
    if ($tngPin == NULL) {
        return "Please Enter <b>Card Expiry Date</b>";
    }else if(!preg_match('/^[0-9]{6}$/', $tngPin)){
        return "PIN Number must contain <b>only number</b> and must <b> 6 characters</b>.";
    }
}

function getTicketID() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    $sql = "SELECT * from bookingrecord";

    $result = $con->query($sql);
    $recordNum = $result->num_rows;
    if ($recordNum == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
//record found
            $ticketID = $record->ticketID;
        }
        $ticketID = str_split($ticketID);
        array_shift($ticketID);
        $ticketID = implode($ticketID);

        return ($ticketID + 1);
    }

    $con->close();
    $result->free();
}

function deleteQty($eventID, $ticketQty) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    $sql = "UPDATE event SET ticketNumber = ticketNumber - $ticketQty WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    $con->close();
}

function addBackQty($eventID, $ticketQty) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    $sql = "UPDATE event SET ticketNumber = ticketNumber + $ticketQty WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    $con->close();
    
}

