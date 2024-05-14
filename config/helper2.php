<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'assignment');

function getAllDate($startDay, $endDay) {
    $datebegin = new DateTime("$startDay");
    $dateend = new DateTime("$endDay");

    $date = array();

    for ($i = $datebegin; $i <= $dateend; $i->modify('+1 day')) {
        $thisdate = $i->format("Y-m-d");
        $date[] = array('first' => $thisdate, 'last' => $thisdate);
    }

    return $date;
}

function checkMemberID($memberID) {
    if ($memberID == NULL) {
        return "Please Enter <b>Member ID</b> You Need to Edit!";
    }
}

function checkTicketPurchase($ticketPurchase) {
    if ($ticketPurchase == NULL) {
        return "Please Enter the <b>Ticket Quantity</b> You need to Purchase!";
    } else if ($ticketPurchase > 50) {
        return "Ticket Quantity Purchase <b>cannot more than 50</b>";
    }
}

function getAllGender() {
    return array(
        "M" => "♂ ️Male",
        "F" => "♀️ Female"
    );
}

function checkName($name) {
    if ($name == NULL) {
        return "Please Enter your <b>Name</b>";
    } else if (!preg_match('/^[a-zA-z @,\"\-\.\/]+$/', $name)) {
        return "Invalid character in <b>Name</b>";
    } else if (strlen($name) > 60) {
        return "You name is <b>Too Long<b>. Only accept 60 characters.";
    }
}

function checkGender($gender) {
    if ($gender == NULL) {
        return "Please Select your <b>GENDER.</b>";
    } else if (!array_key_exists($gender, getAllGender())) {
        return "Invalid <b>GENDER</b> detected.";
    }
}

function checkEmail($email) {
    if ($email == NULL) {
        return "Please Enter your <b>Email</b>!";
    } else if (!preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $email)) {
        return "Invalid email, Try Again!";
    }
}

function checkFeedback($feedback) {
    if ($feedback == NULL) {
        return "Please Enter your <b>Feedback</b>!";
    }
}

function getFeedbackID() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = "SELECT * FROM feedback";

    $result = $con->query($sql);

    $countRow = $result->num_rows;
    if ($countRow == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
            $feedbackID = $record->feedbackID;
        }
        $feedbackID = str_split($feedbackID);
        array_shift($feedbackID);
        $feedbackID = implode($feedbackID);
        return ($feedbackID + 1);
    }
    $con->close();
    $result->close();
}

function getAllEvent() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = "SELECT eventID, eventName FROM event";

    $result = $con->query($sql);

    while ($record = $result->fetch_object()) {
        $eventID = $record->eventID;
        $eventName = $record->eventName;
        $events[$eventID] = $eventName;
    }
    return $events;
}

function getEventName($eventID) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = "SELECT * FROM event WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    if ($record = $result->fetch_object()) {
        return $record->eventName;
    }

    $con->close();
    $result->free();
}

function deleteQty($eventID, $ticketQty) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = "UPDATE event SET ticketNumber = ticketNumber - $ticketQty WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    $con->close();
}

function addBackQty($eventID, $ticketQty) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = "UPDATE event SET ticketNumber = ticketNumber + $ticketQty WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    $con->close();
    
}