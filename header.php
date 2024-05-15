<?php
session_start();
$after = false;

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location:LoginNew2a.php');
}

if (isset($_SESSION['memberID']) != NULL) {
    $record = "user_booking.php";
    $feedback = "Feedbackuser.php";
    $cart = "ListBooking.php";
    $memberID = $_SESSION['memberID'];
    $href = "EventDetail.php";
    $after = true;
} else {
    $record = "LoginNew2a.php?eventid=E1001";
    $feedback = "LoginNew2a.php?eventid=E1001";
    $cart = "LoginNew2a.php?eventid=E1001";
    $href = "LoginNew2a.php";
}

(isset($memberID))?"":$memberID="";
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <title></title>
        <link href="css/header.css" rel="stylesheet" type="text/css"/>

    </head>


    <div class="header">
        <div class="logo">
            <img class="pic"src="image/logo.png" alt="alt" width="80"/>
            <a href="Home.html">MUSIC SOCIETY</a>
        </div>
    </div>
<?php if ($after == false): ?>
        <div class="nav">
            <ul class="bar">
                <li class="n1"><a href="<?php echo (isset($cart) ? $cart : "") ?>"><ion-icon name="cart-outline"></ion-icon></a></li>
                <li class="n1"><a href="memberRegistration.php">REGISTER</a></li>
                <li class="n1"><a href="LoginNew2a.php">LOGIN</a></li>

            </ul>
        <?php endif; ?>

<?php if ($after == true): ?>  
            <div class="nav">
                <ul class="bar">                
                    <li class="n1"><a href="<?php echo (isset($cart) ? $cart : "") ?>"><ion-icon name="cart-outline"></ion-icon></a></li>
                    <li class="n1"><a href="?logout=yes">LOGOUT</a></li>
                    <li class="n1"><a href="member.php">PROFILE</a></li>
                </ul>
<?php endif; ?>

            <ul class="bar1">             

                <li class="n2"><a href="user_event.php">HOME</a></li>
                <li class="n2"><a href="user_event.php#A">PRODUCT</a></li>
                <li class="n2"><a href="<?php echo (isset($record) ? $record : "") ?>">RECORD</a></li>
                <li class="n2"><a href="<?php echo (isset($feedback) ? $feedback : "") ?>" >FEEDBACK</a></li>


            </ul>
            <div class="searchbar">
                <form action="selected-event.php#A" method="POST">
                    <input type="text" name="eventName" placeholder="event name.." class = 'category'/>
                    <input type="text" name="day" class = 'category' placeholder="day"/>

                    <?php
//declare first for testing

                    $date = array("1", "2", "3");
                    $month = array(
                        "01" => "January",
                        "02" => "February",
                        "03" => "March",
                        "04" => "April",
                        "05" => "May",
                        "06" => "June",
                        "07" => "July",
                        "08" => "August",
                        "09" => "September",
                        "10" => "October",
                        "11" => "November",
                        "12" => "December");

                    echo "<select name = 'month' class = 'category' value=''>";
                    echo "<option value = ''>Month</option>";
                    foreach ($month as $key => $value) {
                        echo "<option value = '$key'>$value</option>";
                    }
                    echo "</select>";

                    echo "<select name = 'year' class = 'category'>";
                    echo "<option value = ''>Year</option>";
                    $year = date("Y");

                    for ($i = 0; $i < 5; $i++) {

                        echo "<option value='$year'>$year</option>";
                        $year--;
                    }
                    echo "</select>";
                    ?>
                    <input type="submit" value="search" name="tosearch" class = 'category'/>

                </form>
            </div>
        </div>  







    </header>

</body>
</html>