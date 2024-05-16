<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home page</title>
        <link href="css/user_event.css" rel="stylesheet" type="text/css"/>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <?php
        include './header.php';
        require_once './config/database_connection.php';
        ?>
        <h1 id='A'>All Product</h1>

        <table>
            <?php
            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

            $sql = "SELECT * FROM event";

            if ($result = $con->query($sql)) {
                //record found
                $i = 0;
                while ($record = $result->fetch_object()) {
                    echo ($i == 0) ? "<tr>" : "";
                    echo "<td>";
                    echo '<div class = "event">';
                    printf("
                        <img src='image/%s' alt='logo' /><br />
                        <b>%s</b><br /><br /><br /><br />
                        <div class = 'detail'>
                            <i class='far fa-clock' style='font-size:16px;color:#303030;' > %s</i><br />
                            &nbsp;<i class='fa fa-dollar' style='font-size:16px;color:#303030;' >&nbsp;&nbsp;RM%d per person</i><br />
                        </div>
                        <a href='%s?eventid=%s'>Booking Now</a>"
                            , $record->eventPhoto
                            , $record->eventName
                            , date("H:i", $record->startTime)
                            , $record->price
                            , $href
                            , $record->eventID
                    );

                    echo "</div>";
                    echo "</td> ";

                    $i++;

                    if ($i == 3) {
                        echo "</tr>";
                        $i = 0;
                    }
                }
                $con->close();
                $result->free();
            }
            ?>


        </table>
        


        <?php
        include "./footer.php";
        ?>
    </body>
</html>
