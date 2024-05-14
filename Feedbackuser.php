<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Feedback</title>
        <link href="css/feedbackuser.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <br />
        <?php
        session_start();
        $memberID = $_SESSION['memberID'];
        require_once './config/helper2.php';
        ?>
        
         <?php
        $con1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $sql1 = "SELECT * FROM register WHERE memberID = '$memberID'";
        $result1 = $con1->query($sql1);
        if ($record1 = $result1->fetch_object()) {
            $name = $record1->memberName;
            $email = $record1->memberGmail;
        }
        ?>
        <section>
            <form action="" method="POST">
                <div class="center">
                    <h1>Feedback and Suggest Form</h1>
                    <?php
                    global $hideForm;
                    $hideForm = false;
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    $sql = "SELECT DISTINCT e.eventID FROM bookingrecord bk,payment p ,event e  WHERE bk.paymentID = p.paymentID AND bk.eventID = e.eventID AND memberID = '$memberID'";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<select name='slcfeedback' class='slcEvent'>";

                        while ($record = $result->fetch_object()) {
                            $eventName = getEventName($record->eventID);
                            printf("
                           <option value='%s'>%s</option>
                                 ", $record->eventID, $eventName);
                        }
                        echo "</select>";
                    } else {
                        echo "<div style='font-size:200%;'>Please booking an event before provide the feedback.";
                        echo ' <input type="button" value="Back to Home" name="home" id="back" onclick="location = \'user_event.php\'"/></div>';
                        $hideForm = true;
                    }
                    $con->close();
                    $result->free();
                    ?>

                   <?php
                    (isset($_POST['slcfeedback'])) ?
                                    $eventid = $_POST['slcfeedback'] :
                                    $eventid = "";

                    global $name, $gender, $email, $feedback;
                    if (isset($_POST['submitForm'])) {
                        isset($_POST['txtname']) ?
                                        $name = trim($_POST['txtname']) :
                                        $name = "";

                        

                        isset($_POST['txtemail']) ?
                                        $email = trim($_POST['txtemail']) :
                                        $email = "";

                        isset($_POST['textfb']) ?
                                        $feedback = trim($_POST['textfb']) :
                                        $feedback = "";

                        $error["name"] = checkName($name);
//                        $error["gender"] = checkGender($gender);
                        $error["email"] = checkEmail($email);
                        $error["feedback"] = checkFeedback($feedback);
                        $error = array_filter($error);

                        if (empty($error)) {
                            //NO ERROR, INSERT RECORD
                            $feedbackID = "F" . getFeedbackID();

                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            $sql = "INSERT INTO feedback VALUES(?, ?, ?, ?, ?, ?)";

                            $statement = $con->prepare($sql);
                            $statement->bind_param("ssssss", $feedbackID, $eventid, $memberID, $name, $email, $feedback);

                            $statement->execute();
                            if ($statement->affected_rows > 0) {
                                //Insert Successful
                                printf("<div class='info'>Your Feedback Form has been sent.
                            [<a href='user_event.php'>Back to Home</a>]
                             </div>");
                            } else {
                                //print 
                                echo "<div class='error'>Unable to Sent!</div>
                            [<a href='user_event.php'>Back to Home</a>]";
                            }
                        } else {
                            //WITH ERROR, DISPLAY ERROR
                            echo "<ul class='error'>";
                            foreach ($error as $value) {
                                echo "<li>$value</li>";
                            }
                            echo "</ul>";
                        }
                    }
                    ?>



                </div>
                <?php
                if ($hideForm == false):
                    ?>
                    </div>
                <div class="content">
                    <div class="row">
                        <label for="fir">Name : </label>
                        <input class='fix' type="text" readonly name="txtname" value="<?php echo $name ?>" class="input" id="fir">

                    </div>

                    <div class="row">
                        <label>Email Address : </label>
                        <input class='fix' type="text" readonly name="txtemail" value="<?php echo $email ?>" /> 
                    </div>
                    <div class="row">
                        <label>Fill Out Your Suggestion and Feedback here. </label><br/>
                        <input type="text" name="textfb" value="<?php echo $feedback ?>" id="bigInput"/><br/>
                    </div>
                    <div class="button">
                        <input type="button" value="Back to Home" name="home" onclick="location = 'user_event.php'"/>
                        <input type="button" value="Clear Form" name="ticketcancel" onclick="location = 'Feedbackuser.php'"/>
                        <input type="submit" value="Submit Form" name="submitForm" />
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </section>
        <br /><br /><br />
        <?php
        include './footer.php';
        ?>

    </body>
</html>
