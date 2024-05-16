<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
$DB_USER = "nbuser";
$DB_PASS = "12345678";
$DB_NAME = "music";
$DB_PORT = "3306";


function checkEventID() {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * from event";

    $result = $con->query($sql);
    $recordNum = $result->num_rows;
    if ($recordNum == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
//record found
            $eventID = $record->eventID;
        }
        $eventID = str_split($eventID);
        array_shift($eventID);
        $eventID = implode($eventID);

        return ($eventID + 1);
    }

    $con->close();
    $result->free();
}

function checkName($eventName) {

//check the event name
    if ($eventName == NULL) {
        return "The <b>EVENT NAME</b> is empty.";
    } else if (strlen($eventName) > 50) {
        return "The <b>EVENT NAME</b> is too long.Make it short.";
    } else if (!preg_match("/^[a-zA-Z0-9\-,]{1,50}?/", $eventName)) {
        return "The <b>EVENT NAME</b> contain invalid character(s).";
    }
}

function checkStartDate($startDate) {
//check the date of event
    if ($startDate == NULL) {
        return "The <b>START DATE</b> is empty.";
    }
}

function checkEndDate($endDate, $startDate) {
    if ($endDate == NULL) {
        return "The <b>END DATE</b> is empty.";
    } else if ($endDate < $startDate) {
        return "The <b>END DATE</b> is wrong,cannot less than <b>START DATE</b>.";
    }
}

function checkStartTime($startTime) {
    if ($startTime == NULL) {
        return "The <b>START TIME</b> is empty.";
    }
}

function checkEndTime($endTime, $startTime) {
    if ($endTime == NULL) {
        return "The <b>END TIME</b> is empty.";
    } else if ($endTime < $startTime) {
        return "The <b>END TIME</b> is wrong,cannot less than <b>START TIME</b>.";
    }
}

function checkDescription($description) {
//check description
    if ($description == NULL) {
        return "<b>DESCRIPTION</b> is empty.";
    } else if (strlen($description) > 1000) {
        return "<b>DESCRIPTION</b> is too long. Make it short.";
    } else if (!preg_match("/^[a-zA-Z0-9@,\'\.\-\/\?\!]{1,50}?/", $description)) {
        return "The <b>DESCRIPTION</b> contain invalid character(s).";
    }
}

function checkPrice($price) {
//check the price format
    if ($price == NULL) {
        return "<b>PRICE</b> is empty.";
    } else if (!preg_match("/^\d{1,4}\.\d[0]$/", $price)) {
        return "<b>PRICE</b> is invalid format.format:0.00";
    }
}

function checkTicketNum($ticketNum) {
//check the number of ticket
    if ($ticketNum == NULL) {
        return "<b>TICKET NUMBER</b> is empty.";
    }
}

function checkVenue($venue) {
//check the venue
    if ($venue == NULL) {
        return "<b>VENUE</b> is empty.";
// \r for enter, \n for next line
    } else if (!preg_match("/^[a-zA-Z0-9 ,\'\.\/\-\r\n]{1,300}$/", $venue)) {
        return "<b>VENUE</b> contain invalid character(s).";
    }
}

function checkPhoto($photo) {

//check the photo
//if the error of uploaded larger then 0, find the problem and display error msg
    if ($photo['error'] > 0) {
//have error, display msg
        switch ($photo['error']) {
            case UPLOAD_ERR_NO_FILE:
                return "No <b>EVENT PICTURE</b> was selected.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                return "<b>EVENT PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
                break;
            default:
                return "There was an error when uploading the <b>EVENT PICTURE</b>.";
        }
    } else if ($photo['size'] > 1048576) {
//validate the photo size
//1MB = 1024 x 1024
        return "<b>EVENT PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
    } else {
//extract file extension, eg:png,jpg,gif
        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

//check the file extension
        if ($ext != 'jpg' &&
                $ext != 'jpeg' &&
                $ext != 'gif' &&
                $ext != 'png') {
            return "Only JPG, GIF and PNG images are allowed!";
        }
    }
}

function eventIDvalidation($eventID) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM event WHERE eventID='$eventID'";

    $result = $con->query($sql);

    if ($eventID == NULL) {
        return "No <b>EVENT ID</b> entered.";
    } else if ($result->num_rows == 0) {
        return "Invalid or unavailable <b>EVENT ID</b> entered.";
    }
    $con->close();
    $result->free();
}

function checkQty($ticketQty) {
    if ($ticketQty == NULL) {
        return "No <b>TICKET QUANTITY</b> entered.";
    } else if (!preg_match("/^[0-9]{1,3}$/", $ticketQty)) {
        return "Invalid character in <b>TICKET QUANTITY</b> founded, only digit can entered.";
    }
}

function checkTotalPrice($price, $eventID, $ticketQty) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM event WHERE eventID='$eventID'";

    $result = $con->query($sql);
    if ($record = $result->fetch_object()) {
        $eventPrice = $record->price;
    }
    $newPrice = $eventPrice * $ticketQty;
    $difPrice = $newPrice - $price;

    if ($difPrice == 0) {
        return "<b>TOTAL PRICE</b> is RM$newPrice, no refund or request payment needed.";
    } else if ($difPrice > 1) {
        return "<b>TOTAL PRICE</b> is RM$newPrice, need to make request payment for RM$difPrice because the price before is RM$price.";
    } else {
        $difPrice = $price - $newPrice;
        return "<b>TOTAL PRICE</b> is RM$newPrice, need to refund for RM$difPrice.";
    }
    $newPrice = 0;
    $difPrice = 0;

    $con->close();
    $result->free();
}

function calculatePrice($eventID, $ticketQty) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT price FROM event WHERE eventID='$eventID'";

    $result = $con->query($sql);

    $result = $con->query($sql);
    if ($record = $result->fetch_object()) {
        $eventPrice = $record->price;
    }
    $price = $eventPrice * $ticketQty;

    return $price;
    $con->close();
    $result->free();
}

function findEventID($allname) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * from event";

    $result = $con->query($sql);

    while ($record = $result->fetch_object()) {
        if ($record->eventName == $allname) {
            return $record->eventID;
        }
    }
    $con->close();
    $result->free();
}

function deleteQty($eventID, $ticketQty) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "UPDATE event SET ticketNumber = ticketNumber - $ticketQty WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    $con->close();
}

function addBackQty($eventID, $ticketQty) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "UPDATE event SET ticketNumber = ticketNumber + $ticketQty WHERE eventID = '$eventID'";

    $con->query($sql);

    $con->close();
    
}


function getEventName($eventID) {
    $DB_HOST = "dbmusic.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM event WHERE eventID = '$eventID'";

    $result = $con->query($sql);

    if ($record = $result->fetch_object()) {
        return $record->eventName;
    }

    $con->close();
    
}
