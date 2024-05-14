<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Notification - admin </title>
        <link href="css/notification.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './admin_header.php';
        require_once './config/helperFile.php';
        ?>
        
        <div class='top'>
            <h2>Recently Notification</h2>
         <div class="dropdown2">
                <a href="admin.php" alt="">Admin Page</a>
                <hr>
                <a href="notification.php" alt="">Notification</a>
                <hr>
            </div>
        </div>
        <div class="notice">
            
            <form class="recentNotification" method="post">
 
                <?php
                global $_SESSION;
                $con=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
                
                if (!empty($_POST)) {
                    $notice = trim($_POST["notice"]);
                    $sql="INSERT INTO notice (notice) VALUES (?)";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("s", $notice);
                    $stmt->execute();
                }
                
                $sql="SELECT * FROM notice";
                $result = mysqli_query($con, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                 
                <div class="txtNotification">
                    <?php 
                    echo '' . $row['notice'];
                    ?>
                </div>
                
                <?php                 
                    }
                }
                ?>
                
                <div class="txtNotification">
                    <input class="txtNotice" type="text" name="notice" placeholder="Text Notice...."/>
                    <input  class="buttonSend" type="submit" value="Send" />
                </div>
                
            </form>
        </div>
        
    </body>
</html>
