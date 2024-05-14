<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/memberRegistration.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1><a href="LoginNew2a.php" style="width:10%;color:black;">Log In</a></h1>
        </header>
        <div class="register">
            <?php
            require_once './config/helperFile.php';
            global $id, $name, $age, $gender, $telNo, $email, $password, $confirmPassword;

            if (!empty($_POST)) {
                if (!empty($_POST['Name']) && !empty($_POST['Age']) && !empty($_POST['Gender']) && !empty($_POST['phonenumber']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
                    $name = trim($_POST["Name"]);
                    $age = trim($_POST["Age"]);
                    $gender = trim($_POST["Gender"]);
                    $telNo = trim($_POST["phonenumber"]);
                    $email = trim($_POST["email"]);
                    $password = trim($_POST["password"]);
                    $confirmPassword = trim($_POST["confirmPassword"]);
                    if (isset($_FILES['profileImg'])) {
                        //yes, has a photo uploaded
                        //store the photo
                        $photo = $_FILES['profileImg'];
                    }

                    $error["Name"] = checkName($name);
                    $error["Age"] = checkAge($age);
                    $error["gender"] = checkGender($gender);
                    $error["phonenumber"] = checkTel($telNo);
                    
                    $error["email"] = checkGmail($email);
                    $error["password"] = checkPassword($password);
                    $error["confirmPassword"] = checkConfirmPassword($confirmPassword);
                    $error = array_filter($error);
                    if (empty($error)) {
                        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
                        ////no problem,save in the file
                        //create a uniqueid and use it as the filename
                        $newFileName = uniqid() . "." . "$ext";

                        //move the file and save in the image folder
                        move_uploaded_file($photo['tmp_name'], 'image/' . $newFileName);

                        $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        if ($con->connect_errno) {
                            echo '<div>Failed to connect to MySQL: ' . $con->connect_error . '</div>';
                            exit();
                        }
                        $id = "M" . checkMemberID($id);

                        $stmt = $con->prepare("INSERT INTO register (memberID,password,memberName, memberAge, memberGender, memberTel,profilePhoto, memberGmail) VALUES (?,?, ?, ?, ?, ?, ?, ?)");

                        $phone = '0' . $_POST['phonenumber'];
                        $stmt->bind_param("ssssssss", $id, $password, $name, $age, $gender, $telNo, $newFileName, $email);
                        if ($stmt->execute()) {
                            printf("<div>Member %s has been register.</div>", $name);
                        } else {
                            echo '<div>Unable to register!</div>';
                        }

                        $stmt->close();
                        $con->close();
                    } else {
                        echo "<ul class='txtarea'>";
                        foreach ($error as $value) {
                            echo"<li class='txterr'>$value</li>";
                        }
                        echo "</ul>";
                    }
                } else {
                    echo '<div class="txtarea">Please fill in all required fields!</div>';
                }
            }
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <table class="table">
                    <tr>
                        <th colspan="3">
                            <b><u>Member Registration</u></b><br/><br/><br/>
                        </th>
                    </tr>
                    <tr>

                        <th class="detail"> <label>Name</label></th>
                        <td>:</td>
                        <td><input type="text" name="Name" value="<?php echo $name ?>" placeholder="eg: Abu"/></label></td>
                    </tr>
                    <tr>
                        <th class="detail"><label>Age</label></th>
                        <td>:</td>
                        <td><input type="text" name="Age" value="<?php echo $age ?>" placeholder="eg: 18"/></label></td>                
                    </tr>

                    <tr>
                        <th class="detail"><label> Gender</label></th>
                        <td>:</td>
                        <td>
                            <input type="radio" name="Gender" value="M" checked="checked" />👨Male
                            <input type="radio" name="Gender" value="F" checked="checked" />👩Female 
                        </td>                
                    </tr>

                    <tr>
                        <th class="detail"> <label>Telephone.No</label></th>
                        <td>:</td> 
                        <td><input type="text" name="phonenumber" value="<?php echo $telNo ?>" placeholder="eg: 0123456789"/></label></td>
                    </tr>
                    <tr>
                        <th class="detail"><label>Profile Picture</label></th>
                        <td>:</td>
                        <td><input type="file" name="profileImg" /></td>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    </tr>

                    <tr> 
                        <th class="detail"> <label>Email address</label></th>
                        <td>:</td>
                        <td><input type="text" name="email" value="<?php echo $email ?>" placeholder="eg: example@mail.com"/></label></td>
                    </tr>
                    <tr> 
                        <th class="detail"> <label>Password</label></th>
                        <td>:</td>
                        <td><input type="text" name="password" value="<?php echo $password ?>" placeholder="eg: aBu102."/></label></td>
                    <tr> 
                        <th class="detail"> <label>Confirm Password</label></th>
                        <td>:</td>
                        <td><input type="text" name="confirmPassword" value="<?php echo $confirmPassword ?>" placeholder="Enter again your password."/></label></td>
                    </tr>
                </table>
                <div class='button'>
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Reset" />
                </div>
            </form>
        </div>
        <?php
        include'./footer.php'
        ?>
    </body>
</html>
