<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Profile</title>
        <link href="css/admin.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <div class="top"><h2>Profile</h2></div>
        <?php
        include './admin_header.php';
        require_once './config/helperFile.php';
     session_start();
$id = isset($_SESSION['staffID']) ? $_SESSION['staffID'] : '';

        $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

        if (!$con) {
            die('Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT staffName, staffAge, staffTel FROM staff WHERE staffID='$id'";
        $result = mysqli_query($con, $sql);

        if (!$result) {
            die('Error: ' . mysqli_error());
        }

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row["staffName"];
            $age = $row["staffAge"];
            $tel = $row["staffTel"];

            printf("
                <div class='circle'></div>
                <div>
                    <table class='tableAdmin'>
                        <tr><td>Name:%s</td><td>Age:%s</td></tr>
                        <tr><td>Staff ID:%s</td><td>Telephone number:0%s</td></tr>
                    </table>
                </div>
            ", $name, $age, $id, $tel);
        } else {
            printf("<div class='err'>No records found!</div>");
        }

        mysqli_close($con);
        ?>

        <div class="adminButton">
            <a href="admin.php" alt=""><h1>Admin</h1></a>
        </div>
        <div class="linglong">
            <a href="notification.php" alt=""><h3>🔔</h3></a>
        </div>
    </body>
</html>
