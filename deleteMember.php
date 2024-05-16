<?php
include './header.php';
require_once './config/helperFile.php';
(isset($_SESSION['email'])) ? $email = $_SESSION['email'] : $email = "";
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete!</title>
        <link href="css/deletemember.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <?php
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            $sql = "SELECT * FROM register WHERE memberGmail = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // record found
                $record = $result->fetch_assoc();
                $name = $record['memberName'];
                $age = $record['memberAge'];
                $tel = $record['memberTel'];
                $id = $record['memberID'];
                $email = $record['memberGmail'];
                printf("<h1 class='inform'>Are you sure to delete account?</h1>");
                printf("
                        <form method='POST'>
                            <table>
                                <tr>
                                    <td>Name:%s</td></tr>
                                    <tr> <td>Age:</td><td>%s</td></tr>
                                    <tr> <td>Member ID:</td><td>%s</td></tr>
                                    <tr> <td>Telephone number:</td><td>0%s</td></tr>
                                   <tr>  <td>Gmail:</td><td>%s</td>
                                </tr>
                            <tr class='deletebtn'></td><td>
                            <input type='hidden' name='email' value='%s' />
                            <input type='submit' value='Delete' name='btnyes' />
                            <input type='button' value='Cancel' name='btnCancel' onclick='location.href=\"member.php\"' />
                            </td></tr>
                            </table>
                        </form>
                    ", $name, $age, $id, $tel, $email, $email);
            }
            $stmt->close();
            $con->close();
            if (isset($_POST['btnyes'])) {
                // delete record 

                $email = $_SESSION['email'];
                $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
                $sql = "DELETE FROM register WHERE memberGmail = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param('s', $email);
                if ($stmt->execute()) {
                    printf("<div class='succesful'>Member has been deleted.<a href='login.php'>Back To Login!</a></div>");
                     session_destroy();
                    header('Location:LoginNew2a.php');
                } else {
                    echo '<div class="errorRetrieve">Unable to delete!<a href="member.php"> Back To Member!</a></div>';
                }
                $stmt->close();
                $con->close();
            }
        } else {
            echo '<div class="errorRetrieve">Record not found!<a href="member.php"> Back To Member!</a></div>';
        }
        ?>
        <?php include'./footer.php' ?>
    </body>
</html>
