<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>List Product</title>
        <link href="css/list-event.css" rel="stylesheet" type="text/css"/>
    </head>

    <body> 
        <?php
        include './admin_header.php';
        require_once './config/database_connection.php';

        $eventHeader = array(
            "eventID" => "Product ID",
            "eventName" => "Product Name",
            "startDay" => "Start Date",
            "endDay" => "End Date",
            "startTime" => "Start Time",
            "endTime" => "End Time",
            "price" => "Price(RM)",
            "ticketNumber" => "Stock Quantity"
        );

        global $sort, $type;
        //check $type $sort variable -> prevent sql error
        //which column to sort
        if (isset($_GET['type']) && isset($_GET['sort'])) {
            $type = (array_key_exists($_GET['type'], $eventHeader) ? $_GET['type'] : 'eventID');
            //how to arrange order sequence ASC/DESC
            $sort = ($_GET['sort'] == 'DESC') ? 'DESC' : 'ASC';
        } else {
            $type = "eventID";
            $sort = "ASC";
        }
        ?>
        <div class='top'><h2>Product List</h2></div>
        <div id="all">
            <div id="heading">
                <h2 id="record">Product</h2>
            </div>

            <form action="" method="post">
                <input type="text" name="eventID" placeholder="Event ID" value=""/>
                <input type="text" name="eventName" placeholder="Event Name" value=""/>
                <input type="text" name="startDay" placeholder="start day(YYYY-MM-DD)" value=""/><b style="color:red;">*&nbsp;</b>
                <input type="text" name="endDay" placeholder="end day(YYYY-MM-DD)" value=""/><b style="color:red;">*&nbsp;</b>
                <input type="submit" name="search" value="🔎"/>
            </form>
            <br /><br />
            <table>
                <tr>
                    <?php
                    foreach ($eventHeader as $key => $value) {
                        if ($key == $type) {
                            printf("
                                <th>
                                <a href='?type=%s&sort=%s' class = 'header'>%s</a>%s
                                </th>
                                
                                ", $key
                                    , $sort == 'ASC' ? 'DESC' : 'ASC'
                                    , $value
                                    , $sort == 'ASC' ? '🔽' : '🔼');
                        } else {
                            printf("
                                <th>
                                <a href='?type=%s&sort=ASC' class = 'header'>%s</a>
                                </th>
                                ", $key
                                    , $value);
                        }
                    }
                    echo "</tr>";

                    if (isset($_POST['search'])) {

                        $eventID = trim($_POST['eventID']);
                        $eventName = trim($_POST['eventName']);
                        $startDate = trim($_POST['startDay']);
                        $endDate = trim($_POST['endDay']);

                        if ($eventID == NULL) {
                            $eventID = "%";
                        }

                        if ($eventName == NULL) {
                            $eventName = "%";
                        }

                        if ($startDate == NULL) {
                            $error['startdate'] = "The <b>START DATE</b> is empty.";
                        }

                        $error['enddate'] = checkEndDate($endDate, $startDate);
                        $error = array_filter($error);

                        if (empty($error)) {
                            $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                            $sql = "SELECT * FROM event WHERE eventID LIKE '$eventID' AND eventName LIKE '$eventName' AND startDay >= '$startDate' AND endDay <= '$endDate' ORDER BY $type $sort";

                            $result = $con->query($sql);

                            if ($result->num_rows != 0) {
                                //record found
                                while ($record = $result->fetch_object()) {
                                    printf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
<td><a href='edit-event.php?eventid=%s' class = 'header'>Edit</a> || <a href='delete-event.php?eventid=%s' class = 'header'>Delete</a></td>
</tr>
                        ", $record->eventID
                                            , $record->eventName
                                            , $record->startDay
                                            , $record->endDay
                                            , date("H:i", $record->startTime)
                                            , date("H:i", $record->endTime)
                                            , $record->price
                                            , $record->ticketNumber
                                            , $record->eventID
                                            , $record->eventID
                                    );
                                }
                            } else {
                                echo "<div class='error'>No record found.Please search again.[<a href='list-event.php'>See all record</a>]</div>";
                            }
                            $con->close();
                            $result->free();
                        } else {
                            printf("<ul class='error'><li>%s</li></ul>", implode('</li><li>', $error));
                        }
                    } else {


                        $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

                        $sql = "SELECT * FROM event ORDER BY $type $sort";

                        if ($result = $con->query($sql)) {

                            while ($record = $result->fetch_object()) {

                                printf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
<td><a href='edit-event.php?eventid=%s' class = 'header'>Edit</a> || <a href='delete-event.php?eventid=%s' class = 'header'>Delete</a></td>
</tr>
                        ", $record->eventID,
                                        $record->eventName,
                                        $record->startDay,
                                        $record->endDay,
                                        date("H:i", $record->startTime),
                                        date("H:i", $record->endTime),
                                        $record->price,
                                        $record->ticketNumber,
                                        $record->eventID,
                                        $record->eventID
                                );
                            }
                        }
                        $con->close();
                        $result->free();
                    }
                    ?>
            </table>
        </div>

    </body>
</html>
