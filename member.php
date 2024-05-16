<?php
include './header.php';
require_once './config/helperFile.php';
(isset($_SESSION['email'])) ? $email = $_SESSION['email'] : $email = "";

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location:LoginNew2a.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Member</title>
        <link href="css/member.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="option">
            <hr>
            <hr>
            <hr>
            <div class="option1">
                <p><u>Go to...</u></p><br/>
                <a href="memberNotification.php">Notice</a>   
                <br/><hr><br/>
                <a href="editMember.php">Edit</a>
                <br/><hr><br/>
                <a href="deleteMember.php">Delete</a>
                <br/><hr><br/>
                <a href="?logout=yes" onclick="return confirm('Are you sure to log out?')">LogOut</a>
                <br/>
            </div>
        </div>
        <?php
        $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

        $sql = "SELECT * FROM register WHERE memberID='$memberID'";
        $result = $con->query($sql);

        if ($record = $result->fetch_object()) {
            $photo = $record->profilePhoto;
        }
        $con->close();
        $result->free();
        ?>

        <div class="circle">
            <img class="img" src="image/<?php echo $photo; ?>" alt=""/>
        </div>
        <div class="textMember">
<?php
if (isset($_SESSION['email'])) {

    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    $stmt = "SELECT memberName,memberAge,memberID,memberTel,profilePhoto FROM register WHERE memberGmail='$email'";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_assoc($result);

    if (!empty($row)) {
        $name = $row['memberName'];
        $age = $row['memberAge'];
        $id = $row['memberID'];
        $tel = $row['memberTel'];

        printf("
   <div class='shapearea'>
        <div class='shape'>%s</div>
        <div class='shape2'>%s Years Old</div>                            
        <div class='shape3'>%s</div>
        <div class='shape4'>0%s</div>   
        </div>
    ", $name, $age, $id, $tel);
    } else {
        echo "No member found.<a href='LoginNew2a.php'>Back to Login!</a>";
    }
}

$con->close();

?>
        </div>

<?php
include'./footer.php'
?>
    </body>
</html>
