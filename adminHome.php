<?php  
session_start();
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Home Page</title>
    <link href='css/adminHome.css' rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
    include './admin_header.php';
    require_once './config/helperFile.php';
    if (isset($_POST["btnDelete"]) && isset($_POST["checked"])) {
        $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        foreach ($_POST["checked"] as $memberID) {
            $sql = "DELETE FROM register WHERE memberID='$memberID'";
            mysqli_query($con, $sql);
        }
        $con->close();
        echo "<div class='table'>Selected members have been deleted.</div>";
    }
     if (isset($_POST["noticeDlt"])) {
    $id = $_POST['noticeDlt'];

    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "DELETE FROM notice WHERE noticeID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    $con->close();
}

    ?>

    <?php
    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT COUNT(*) FROM register";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $count = $row[0];
        printf("<div class='rectangle'><p class='showmem'>Number Of Member :%s</p></div>", $count);
    } else {
        echo 'Failed to retrieve data!';
    }
    $con->close();
    ?>

    <?php
    $id = "";
    printf("<div class='border1'>
<form method='POST'>
<h1><b>Search for Member</b></h1>
<div class='input-group mb-3'>
<input type='text' name='txtID' class='form-control' placeholder='Member ID' aria-label='Member ID' aria-describedby='button-addon2'>
<button class='submitbtn' type='submit' name='submit' id='button-addon2'>Submit</button>
</div>
</form>
</div>");

    if (isset($_POST["submit"])) {
        $id = trim($_POST["txtID"]);

        $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $sql = "SELECT * FROM register where memberID='$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!empty($row)) {
            $name = $row['memberName'];
            $age = $row['memberAge'];
            $id = $row['memberID'];
            $tel = $row['memberTel'];
            $email = $row['memberGmail'];
            printf("
      <form method='POST'>
        <table class='table'>
       
            <tr>
              <td><b>Name</b></td>
          <td><b>Age</b></td>
          <td><b>ID</b></td>
          <td><b>Tel</b></td>
      <td><b>Email</b></td>
    </tr>
 <tr>
 <td><hr></td>
 <td><hr></td>
 <td><hr></td>
 <td><hr></td>
 <td><hr></td>
 </tr>
    <tr>
      <td>%s</td>        
      <td>%s</td>        
      <td>%s</td>       
      <td>0%s</td>        
      <td>%s</td>       
      <td><input type='checkbox' name='checked[]' value='%s' /></td>
    </tr>
</table>
<button class='dltbtn1' type='submit' value='Delete' name='btnDelete' onclick='return confirm(\"Are you sure to delete this record?\")'>Delete Member Selected</button>
  </form>
", $name, $age, $id, $tel, $email, $id);
        } else if ($id == null) {
            printf("<div class='table'>Enter member ID to search for member!</div>");
        } else {
            printf("<div class='table'>No member found.</div>");
        }
    }
    ?>
<?php //---------------------notice------------------------------------------------------- ?>

<?php
$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$id = 'N' . checkNoticeID($id);

if (!empty($_POST["notice"])) {
    $notice = trim($_POST["notice"]);
    $sql = "INSERT INTO notice (noticeID, notice) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $id, $notice);
    $stmt->execute();
    $con->close();
}

$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$sql = "SELECT * FROM notice";
$result = mysqli_query($con, $sql);

if ($result) {
    echo '
        <div class="notice">
            <form class="recentNotification" method="post">
                <p class="txtN"><b>Recently Notice</b></p>
                <div class="txtNotification">';

    while ($row = mysqli_fetch_assoc($result)) {
        $noticeID = $row['noticeID'];
        $notice = $row['notice'];

        printf("
            %s
            %s
            <a href='editNotice.php?id=%s'>🖋️</a>
            <button type='submit' value='%s' name='noticeDlt' onclick='return confirm(\"Are you sure to delete this record?\")'>🗑️</button>
            <hr><br>
            ", $noticeID, $notice, $noticeID, $noticeID);
        $_SESSION['noticeID']=$noticeID;
    }

    echo '
                <input class="txtNotice" type="text" name="notice" placeholder="Text Notice...."/>
                <input class="buttonSend" type="submit" value="Send"/>
            </div>
        </form>
    </div>';
} else {
    echo 'Failed to retrieve data!';
}
$con->close();
?>


</body>
</html>
