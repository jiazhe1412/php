<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Feedback</title>
        <link href="css/EditFeedback.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/helper2.php';
        ?>
        <div class='editfeedback'>
            <h1 style='text-align:center;font-family:cursive;'>Edit Feedback</h1>
            <?php
            global $hideForm;
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                isset($_GET["feedbackid"]) ? $feedbackid = strtoupper(trim($_GET["feedbackid"])) : $feedbackid = "";
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                $sql = "SELECT * FROM feedback WHERE feedbackID = '$feedbackid'";
                $result = $con->query($sql);

                if ($record = $result->fetch_object()) {
                    //record found
                    $feedbackid = $record->feedbackID;
                    $eventid = $record->eventID;
                    $name = $record->name;
                    $email = $record->email;
                    $feedbackDetail = $record->feedback;
                } else {
                    //record not found
                    echo "<div class='error'>Unable to retrieve record![<a href='Feedbackadmin.php'>Back to Booking list</a>]</div>";
                    $hideForm = true;
                }
                $result->free();
                $con->close();
            } else {
                //POST
                $feedbackid = strtoupper(trim($_POST['hdFeedbackID']));
                $eventID = strtoupper(trim($_POST['slcfeedback']));
                $name = trim($_POST['txtName']);
                $email = trim($_POST['txtemail']);
                $feedbackDetail = trim($_POST['txtfeedback']);

                $error['name'] = checkName($name);
                $error['email'] = checkEmail($email);
                $error['feedback'] = checkFeedback($feedbackDetail);
                $error = array_filter($error);

                if (empty($error)) {
                    //No error
                    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                    $sql = "UPDATE feedback SET eventID = ?, name = ?, email = ?, feedback = ? WHERE feedbackID = ?";

                    $statement = $con->prepare($sql);

                    $statement->bind_param("sssss", $eventID, $name, $email, $feedbackDetail, $feedbackid);

                    if ($statement->execute()) {
                        //updated sucessful
                        printf("<div class='sucessful'>
                            Feedback Successful Update![<a href='Feedbackadmin.php'>Back to Booking List</a>]
                            </div>");
                    } else {
                        //fail to update
                        echo "<div class='error'>Unable to Edit!</div>
                    [<a href='Feedbackadmin.php'>Back to Feedback List</a>]";
                    }
                    $con->close();
                    $statement->close();
                } else {
                    //same insert page
                    //WITH ERROR, DISPLAY ERROR
                    echo "<ul class='error'>";
                    foreach ($error as $value) {
                        echo "<li>$value</li>";
                    }
                    echo "</ul>";
                }
            }
            ?>

            <?php if ($hideForm == false): ?>
                <form action="" method="POST">

                    <table>
                        <tr>
                            <th>Feedback ID: </th>
                            <td><?php echo $feedbackid ?>
                                <input type="hidden" name="hdFeedbackID" value="<?php echo $feedbackid; ?>" /></td>
                        </tr>
                        <tr>
                            <th>Event ID: </th>
                            <td><?php
                                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                                $sql = "SELECT * FROM event";
                                $result = $con->query($sql);
                                echo "<select name='slcfeedback' class='slcEvent'>";
                                while ($record = $result->fetch_object()) {
                                    if ($eventid == $record->eventID) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    printf("
                           <option value='%s' $selected>%s</option>
                                 ", $record->eventID, $record->eventName);
                                }echo "</select>";

                                $con->close();
                                $result->free();
                                ?></td>
                        </tr>
                        <tr>
                            <th>Name: </th>
                            <td><input type="text" name="txtName" value=" <?php echo $name ?> " /></td>
                        </tr>

                        <tr>
                            <th>Email: </th>
                            <td><input type="email" name="txtemail" value="<?php echo $email ?>" /></td>
                        </tr>
                        <tr>
                            <th>Feedback: </th>
                            <td class='feedbackInput'><input type="text" name="txtfeedback" value="<?php echo $feedbackDetail ?>" /></td>
                        </tr>
                    </table>
                    <div class='editbtn'>
                        <input type="submit" value="Update" name="btnUpdate" />
                        <input type="button" value="Cancel" name="btnCancel" onclick="location = 'Feedbackadmin.php'"/>
                    </div>
                </form>    
            </div>

        <?php endif; ?>
    </body>
</html>
