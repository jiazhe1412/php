<?php

global $password;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT;
$DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
$DB_USER = "nbuser";
$DB_PASS = "12345678";
$DB_NAME = "music";
$DB_PORT = "3306";

function checkMemberID() {
    $DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * from register";

    $result = $con->query($sql);
    $recordNum = $result->num_rows;
    if ($recordNum == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
            //record found
            $memberID = $record->memberID;
            $password = $record->password;
            $memberName = $record->memberName;
            $memberAge = $record->memberAge;
            $memberGender = $record->memberGender;
            $memberTel = $record->memberTel;
            $memberGmail = $record->memberGmail;
        }
        $memberID = str_split($memberID);

        array_shift($memberID);
        $memberID = implode($memberID);

        return ($memberID + 1);
    }
}

function checkNoticeID() {
    $DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    
    $sql = "SELECT * from notice";

    $result = $con->query($sql);
    $recordNum = $result->num_rows;
    if ($recordNum == 0) {
        return 1001;
    } else {
        while ($record = $result->fetch_object()) {
            //record found
            $noticeID = $record->noticeID;
            $notice = $record->notice;
        }
        $noticeID = str_split($noticeID);

        array_shift($noticeID);
        $noticeID = implode($noticeID);

        return ($noticeID + 1);
    }
}

function checkPassword($password) {
    if ($password == null) {
        return'Please enter your password!';
    } else if (!preg_match("/^[a-zA-Z0-9 .?!]+$/", $password)) {
        return'Invalid character inside Password!';
    } else if (strlen($password) > 15) {
        return'Length of password too long, should be shorter!';
    }
}

function checkConfirmPassword($confirmPassword) {
    global $password;
    if ($confirmPassword == null) {
        return'Please confirm your password!';
    } else if ($confirmPassword != $password) {
        return'Different password entered!';
    }
}

function checkName($name) {
    if ($name == NULL) {
        return'Invalid enter name!';
    } else if (strlen($name) > 30) {
        return'Invalid name length! The characters is only allow maximum 30.';
    } else if (!preg_match("/^[A-Za-z ]+$/", $name)) {
        return'Invalid character inside name!';
    }
}

function checkAge($age) {
    if ($age == NULL) {
        return'Please enter your age!';
    } else if (!preg_match("/^[0-9]+$/", $age)) {
        return'Invalid character inside age!';
    }
}

function checkGender($gender) {
    if ($gender == NULL) {
        return'Please select your gender!';
    }
}

function checkTel($telNo) {
    if ($telNo == Null) {
        return'Please enter your telephone number!';
    } else if (!preg_match("/^[0-9]+$/", $telNo)) {
        return'Invalid telephone number!';
    } else if (strlen($telNo) < 10 || strlen($telNo) > 11) {
        return'Invalid length of telephone number!';
    }
}

function checkGmail($email) {
    if ($email == NULL) {
        return'Please enter your gmail!';
    } else if (!preg_match("/^[A-Za-z0-9]+@[A-Za-z0-9\.]+$/", $email)) {
        return'Invalid gmail entered!';
    } else if (memberGmailExist($email)) {
        return'This gmail had been used!';
    }
}

function deleteMember($member) {
    $DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    $sql = "DELETE register FROM memberID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $id);
    if ($stmt->execute()) {
        //
        return'Member has been deleted.<a href="login.php">Back To Login!</a>>';
    } else {
        return'Unable to delete!<a href="login.php">BACK TO Login!</a>';
    }
}

function memberGmailExist($email) {
    $DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $exist = false;
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    $sql = "SELECT * FROM register WHERE memberGmail = '$email'";
    if ($result = $con->query($sql)) {
        if ($result->num_rows > 0) {
            $exist = true;
        }
    }
    $result->free();
    $con->close();
    return $exist;
}

function getNotice() {
    $DB_HOST = "music-rdsdb.c1iugiocociv.us-east-1.rds.amazonaws.com";
    $DB_USER = "nbuser";
    $DB_PASS = "12345678";
    $DB_NAME = "music";
    $DB_PORT = "3306";
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $sql = "SELECT * FROM notice";
    $result = mysqli_query($con, $sql);
    $output = '';

    if ($result && mysqli_num_rows($result) > 0) {
        $output .= "<div class='notice'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $noticeID = $row['noticeID'];
            $noticeText = $row['notice'];
            $output .= $noticeText . " <a href='editNotice.php'>🖋️</a> <a href='deleteNotice.php'>🗑️</a><hr>";
        }
        $output .= "</div>";
    }

    return $output;
}
