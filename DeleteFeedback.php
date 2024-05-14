<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Feedback</title>
        <link href="css/DeleteFeedback.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/helper2.php';
        ?>
        <div class='top'><h2>Delete Feedback</h2></div>
        
        <div class="deleteFeedback">
            <h1 style='text-align:center;font-family:cursive;'>Delete Feedback</h1>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                isset($_GET['feedbackid']) ? $feedbackid = strtoupper(trim($_GET['feedbackid'])) : $feedbackid = "";
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $sql = "SELECT * FROM feedback WHERE feedbackID = '$feedbackid'";
                $result = $con->query($sql);

                if ($record = $result->fetch_object()) {
                    $feedbackid = $record->feedbackID;
                    $eventid = $record->eventID;
                    $name = $record->name;
                    //$gender = $record->gender;
                    $email = $record->email;
                    $feedback = $record->feedback;

                    printf("<div class='deleteContent'>
                        <h3 style='color:red;'>Are you sure you want to delete the Feedback?</h3>
                        <table>
                        <tr>
                            <th>Feedback ID: </th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Event ID: </th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Name: </th>
                            <td>%s</td>
                        </tr>
                        
                        <tr>
                            <th>Email: </th>
                            <td>%s</td>
                        </tr>
                        <tr>
                            <th>Feedback: </th>
                            <td>%s</td>
                        </tr>
                        </table>
                        <form action='' method='POST'>
                        <input type='hidden' name='hdFeedbackID' value='%s' />
                        <input type='hidden' name='hdFeedbackName' value='%s' />
                        <input type='submit' value='Delete Feedback' name='btnFeedbackDelete' />
                        <input type='button' value='Cancel' name='btnFeedbackCancel' onclick='location = \"Feedbackadmin.php\"'/>
                        </form>
                        </div>", $feedbackid, $eventid, $name, $email, $feedback, $feedbackid, $name);

                    $result->free();
                    $con->close();
                } else {
                    //record not found
                    echo "<div class='error'>Unable to retrieve record![<a href='Feedbackadmin.php'>Back to Booking list</a>]</div>";
                }
            } else {
                //POST
                $fbid = $_POST['hdFeedbackID'];
                $fbname = $_POST['hdFeedbackName'];

                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                $sql = "DELETE FROM feedback WHERE feedbackID = ?";

                $stmt = $con->prepare($sql);

                $stmt->bind_param('s', $fbid);
                if ($stmt->execute()) {
                    //deleted
                    printf("<div class='sucess'>%s Feedback has been deleted.
                            [<a href='Feedbackadmin.php'>Back to Feedback List</a>]
                             </div>", $fbname);
                } else {
                    //unable to delete
                    echo "<div class='error'>Unable to Delete Feedback!</div>
                    [<a href='Feedbackadmin.php'>Back to Feedback List</a>]";
                }
                $con->close();
                $stmt->close();
            }
            ?>
        </div>
    </body>
</html>
