<!DOCTYPE html>
<html>
    <!--  
     * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
     * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
     */ -->

    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <link href="css/LoginNew2.css" rel="stylesheet" type="text/css"/>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>



    </head>
    <body>

        <header>
            <div class="header">

            </div>
            <div class="logo">
                <img class="pic"src="logo.png" alt="alt" width="80"/>
            </div>

            <header>
                <?php
                include './header.php';
                require_once './config/database_connection.php';
                ?>
            </header>

            <div class="whole" style="position: absolute; top: 70%; right:30%;">


                <span class='icon-close'><ion-icon name="close" onclick="location = 'user_event.php'"></ion-icon></span>
                <div class="form-box login">
                    <p style="font-size:120%;background-color: pink;text-align: center;">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "GET") {
                            (isset($_GET["eventid"])) ? $eventID = strtoupper(trim($_GET["eventid"])) : $eventID = '';

                            if ($eventID != NULL) {
                                echo "<p style='background-color:transparent;color:#ff91ae;font-weight:bold;'>Please login first to make event booking.</p>";
                            }
                        } else {
                            (isset($_COOKIE['email'])) ?
                                            $emails = array_filter(explode('|', $_COOKIE['email'])) : $emails = array();

                            $email = trim($_POST['email']);
                            $password = trim($_POST['password']);
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                            $sql = "SELECT * FROM register";
                            if ($result = $con->query($sql)) {
                                if ($email == "staff@gmail.com") {
                                    if ($password == "staff001") {
                                        header('Location:adminHome.php');
                                    }
                                } else {
                                    $checkEmail = 0;
                                    $checkPass = 0;
                                    while ($record = $result->fetch_object()) {

                                        if ($email == $record->memberGmail) {
                                            $checkEmail++;
                                            if ($password == $record->password) {
                                                $checkPass++;
                                                if (!empty($_POST['remember'])) {
                                                    $emails[] = $email;

                                                    setcookie('email', implode('|', $emails), time() + 365 * 24 * 60 * 60);
                                                    setcookie('password', implode('|', $passwords), time() + 365 * 24 * 60 * 60);
                                                }
                                                $_SESSION['memberID'] = $record->memberID;
                                                $_SESSION['email'] = $email;
                                                header('Location:user_event.php');
                                            }
                                        }
                                    }

                                    if ($checkEmail == 0) {
                                        echo "<p style='background-color:transparent;color:#ff91ae;font-weight:bold;'>Wrong <b>EMAIL.</b></p>";
                                    } else if ($checkPass == 0) {
                                        echo "<p style='background-color:transparent;color:#ff91ae;font-weight:bold;'>Wrong <b>PASSWORD</b></p>";
                                    }
                                }
                            }
                        }
                        ?>

                    </p>
                    <h2>Login</h2>
                    <form action="" method="POST">
                        <div class="input-box"> 
                            <span class='icon'>
                                <ion-icon name="mail-outline"></ion-icon>
                            </span>
                            <input type="email" name="email" value="<?php echo (isset($email) ? $email : "") ?>" required>
                            <label>Email</label>
                        </div>

                        <div class="input-box">
                            <span class='icon'>
                                <ion-icon name="lock-closed-outline"></ion-icon>
                            </span>
                            <input type="password" name="password" value="<?php echo (isset($password) ? $password : "") ?>" required>
                            <label>Password</label>
                        </div>

                        <div class="remember-forget">
                            <input type="checkbox" name="remember">
                            <label>Remember me</label>


                        </div>
                        <button type="submit" class="btn" name="login">Login</button>

                        <div class="login-register">
                            <p>Don't have an account?
                                <a href="memberRegistration.php">Register Now</a>
                            </p> 
                        </div>
                    </form>
                </div>



                </form>
            </div> 
        </div>

        <div style="position: absolute; top: 165%; width: 100%;"> 
            <?php
            include "./footer.php";
            ?>
        </div>

</body>
<script src="LoginNew2.js" type="text/javascript"></script>
</html>

