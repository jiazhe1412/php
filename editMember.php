<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->

<?php
include './header.php';
require_once './config/helperFile.php';

$email_from_session = $_SESSION['email'];
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Member Data</title>
        <link href="css/editmember.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="form">
            <h1 class="title">Edit Member Data</h1>
            <?php
            if (!empty($email_from_session)) {
                $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                if ($con->connect_errno) {
                    printf("Connect failed: %s\n", $con->connect_error);
                    exit();
                }

                $stmt = $con->prepare("SELECT * FROM register WHERE memberGmail = ?");
                $stmt->bind_param("s", $email_from_session);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $name = $row['memberName'];
                $age = $row['memberAge'];
                $tel = $row['memberTel'];
                $email = $row['memberGmail'];
                $oldPhoto = $row['profilePhoto'];

                $con->close();
                $stmt->close();
            } else {
                header("Location:member.php");
            }

            if (isset($_POST['submit'])) {
                $name = $_POST['memberName'];
                $age = $_POST['memberAge'];
                $tel = $_POST['memberTel'];
                $email = $_POST['memberGmail'];

                $error["Name"] = checkName($name);
                $error["Age"] = checkAge($age);
                $error["phonenumber"] = checkTel($tel);
                
                if (isset($_FILES['image'])) {
                    //yes, has a photo uploaded
                    //store the photo
                    $photo = $_FILES['image'];

                    if ($photo['error'] > 0) {
                        //have error, display msg
                        switch ($photo['error']) {
                            case UPLOAD_ERR_NO_FILE:
                                //if no picture,then continue updated
                                $photo = "nothing";
                                $newFileName = $oldPhoto;
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                $error['photo'] = "<b>PROFILE PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
                                break;
                            default:
                                $error['photo'] = "There was an error when uploading the <b>PROFILE PICTURE</b>.";
                        }
                    } else if ($photo['size'] > 1048576) {
                        //validate the photo size
                        //1MB = 1024 x 1024
                        $error['photo'] = "<b>PROFILE PICTURE</b> uploaded is too large. Maximum 1MB allowed!";
                    }
                }


                $error = array_filter($error);

                if (empty($error)) {
                    if ($con->connect_errno) {
                        printf("Connect failed: %s\n", $con->connect_error);
                        exit();
                    }

                    if ($photo != "nothing") {
                        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
                        ////no problem,save in the file
                        //create a uniqueid and use it as the filename
                        $newFileName = uniqid() . "." . "$ext";

                        //move the file and save in the image folder
                        move_uploaded_file($photo['tmp_name'], 'image/' . $newFileName);

                        $path = "image/$oldPhoto";
                        unlink($path);
                    }
                    $con1 = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
                    $stmt1 = $con1->prepare("UPDATE register SET memberName = ?, memberAge = ?, memberTel = ?,profilePhoto = ? WHERE memberGmail = ?");
                    $stmt1->bind_param("sssss", $name, $age, $tel, $newFileName, $email);
                    if ($stmt1->execute()) {
                        printf("<div id='succesful'>Member data has been updated.<a href='member.php'>Back to Members Page</a></div>");
                    } else {
                        printf("<div>Failed to update member data.<a href='member.php'>Back to Members Page</a></div>");
                    }

                    $con1->close();
                    $stmt1->close();
                } else {
                    echo "<ul class='txtarea'>";
                    foreach ($error as $value) {
                        echo"<li class='txterr'>$value</li>";
                    }
                    echo "</ul>";
                }
            }
            ?>
            <form method="post" action=""  enctype="multipart/form-data">
                <table class='table'>
                    <tr>
                        <th class='txt'><b>Enter The New Member Details</b></th>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="memberName" placeholder="Name" value="<?php echo (isset($name)) ? $name : "" ?>"/></td>
                    </tr>
                    <tr>
                        <td>Age:</td><td><input type="text" name="memberAge" placeholder="Age" value="<?php echo (isset($age)) ? $age : "" ?>"/>
                        </td>
                    </tr>
                    <td>Telephone number:</td><td><input type="text" name="memberTel" placeholder="Telephone" value="<?php echo (isset($tel)) ? $tel : "" ?>"/></td>
                    </tr>
                    <tr>
                        <td>Email:</td><td><input type="text" name="memberGmail" placeholder="Email" value="<?php echo (isset($email)) ? $email : "" ?>" readonly/></td>
                    </tr>

                    <tr>
                        <td>Profile Photo:</td><td><input type="file" name="image" placeholder="Email" value=""/></td>

                    </tr>
                    <tr class='editbtn'>
                        <td><input class='cancel' type='button' value='Cancel' name='btnCancel' onclick='location = "member.php"' /></td>
                        <td><button class='submit' type="submit" name="submit">Update</button></td></tr>

                </table>
            </form>
        </div>

        <?php
        include './footer.php';
        ?>
    </body>
</html>


