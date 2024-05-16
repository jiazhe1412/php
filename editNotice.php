<?php
session_start();
$noticeID = $_SESSION['noticeID'];
include './admin_header.php';
require_once './config/helperFile.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Notice</title>
        <link href="css/editnotice.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div >
            <h1 class="h1txt">Edit Notice</h1>
            <?php
            if (!empty($noticeID)) {
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                if ($con->connect_errno) {
                    printf("Connect failed: %s\n", $con->connect_error);
                    exit();
                }

                $stmt = $con->prepare("SELECT * FROM notice WHERE noticeID = ?");
                $stmt->bind_param("s", $noticeID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $notice = $row['notice'];
            } else {
                header("Location:adminHome.php");
            }

            if (isset($_POST['submit'])) {
                $notice = $_POST['notice'];    
                    $stmt = $con->prepare("UPDATE notice SET notice = ? WHERE noticeID = ?");
                    $stmt->bind_param("ss", $notice, $noticeID);
                    if ($stmt->execute()) {
                        printf("<div class='table1'>Notice has been updated.<a href='adminHome.php'>Back to Admin</a></div>");
                    } else {
                        printf("<div class='table1'>Failed to update notice.<a href='adminHome.php'>Back to Admin</a></div>");
                    }

                    $con->close();
            }
            ?>
            <form method="post" action="">
                <table class='table78'>
                    <tr><td><b>Enter The New Notice</b><br/><br/></td></tr>
                    <tr><th style='vertical-align: top; text-align: left;'>Notice:</th><td><input class="txtbox" type="text" name="notice" placeholder="Notice" value="" required/><br/><br/></td></tr>
                 
                    <tr class='button'><td><button class='submit' type="submit" name="submit" onclick="location='adminHome.php'">Update</button></td>
                        <td><input class='submit' type='button' value='Cancel' name='btnCancel' onclick='location = "adminHome.php" '/></td></tr>

                </table>
            </form>
        </div>

      
    </body>
</html>
