<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Member Notice</title>
        <link href="css/memberNotification.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './header.php';
        require_once './config/helperFile.php';
        ?>
         <?php
         global $photo;
        $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

        $sql = "SELECT * FROM register WHERE memberID='$memberID'";
        $result = $con->query($sql);

        if ($record = $result->fetch_object()) {
            $photo = $record->profilePhoto;
        }
        $con->close();
        $result->free();
        ?>
        <div class='profile'>
            <div class='profilePic'>
                <img class="img" src="image/<?php echo $photo; ?>" alt=""/>
            </div> 
            <div class='tablePic'>
                <table class='txt'>
                    <tr>
                        <td><br/><a href='user_event.php' alt=''>Home</a> <hr></td>
                    </tr>
                    <tr>
                        <td><br/><a href='user_event.php#A' alt=''>Event</a><hr></td>
                    </tr>                    
                    <tr>
                        <td><br/><a href='member.php' alt=''>Profile</a><br/><br/></td>                      
                    </tr>
                </table>
            </div>
        </div>
        <div class='showNotice'>
            <table>
                <?php

                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                $sql = "SELECT * FROM notice";
                $result = $con->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        echo "<tr><hr /><td>" . $row->notice . "</td></tr>";
                    }
                } else {
                    echo '<tr><td class="txt1">No Notification Yet!</td></tr>';
                }
                $con->close();
                ?>
            </table>            
        </div>
        <?php include './footer.php'; ?>
    </body>
</html>
