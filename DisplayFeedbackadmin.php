<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Feedback Admin Page</title>
        <link href="css/DisplayFeedbackadmin.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/helper2.php';
        ?>
        <div class='feedbackContent'>
            <h1 style='text-align:center;font-family:fantasy;'>Feedback Display</h1>

            <table>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    isset($_GET["id"]) ? $eventID = strtoupper(trim($_GET["id"])) : $eventID = "";
                    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                    $sql = "SELECT * FROM feedback WHERE eventID = '$eventID'";
                    if ($result = $con->query($sql)) {
                        while ($record = $result->fetch_object()) {

                            printf("<tr class='feedbackDisplay'>
                                    <td colspan='2'>%s</td>
                                    <td>%s</td>
                                    
                                </tr>
                                <tr>
                                    <td colspan='3' class='feedbackdetail'>%s</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class='btnLink'>[<a href='EditFeedback.php?feedbackid=%s'>Edit</a>]|[<a href='DeleteFeedback.php?feedbackid=%s'>Delete</a>]<br /><br /></td>
                                </tr>
                                 ", $record->name, $record->email, $record->feedback, $record->feedbackID, $record->feedbackID);
                        }
                        printf("
                        <tr style='text-align:center;color:white;'>
                            <td colspan='3'><b>%d feedback from member returned</b>.
                        </tr>
                    ", $result->num_rows);

                        $result->free();
                        $con->close();
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>
