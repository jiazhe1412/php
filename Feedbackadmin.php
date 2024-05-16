<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Feedback Admin</title>
        <link href="css/Feedbackadmin.css" rel="stylesheet" type="text/css"/>
<!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="../Practical4/jQuery/feedback.js" type="text/javascript"></script>-->
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/helper2.php';
        ?>
        <div class="horizontal">

            <form action="" method="POST">
                <div class="selectEvent">
                    <h1>Select Event</h1>
                    <table>
                        
                            <?php
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                            $sql = "SELECT * FROM event";
                            $result = $con->query($sql);

                            if ($result = $con->query($sql)) {

                                while ($record = $result->fetch_object()) {
                                    printf("
                                   
                                    <tr>
                                    
                                        <td><br /><img src='image/%s'/>
                                        <p>%s</p>
                                        <input type='hidden' name='hdeventId' value='%s' />
                                        <input type='button' value='Display Feedback' name='btnDisplay' onclick='location=\"DisplayFeedbackadmin.php?id=%s\"'/>
                                        <br /><br /><br />
                                      </td>
                                    </tr>

                                    ", $record->eventPhoto, $record->eventName, $record->eventID, $record->eventID);
                                }
                            }
                            $con->close();
                            $result->free();
                            ?>
                    </table>
                </div>
            </form>
        </div>

    </body>
</html>
