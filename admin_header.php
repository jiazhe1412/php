<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/admin_header.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#down').click(function () {
                    $('#content').slideToggle();
                });
            });
        </script>
    </head>
    <body>
        <div class='sidebar'>
            <h2>Admin</h2>
            <a href='adminHome.php' >Home</a><hr />
            <a id="down" ">Product</a><hr />
            <div id="content">
                <a href='event_calender.php' >Product Calender</a><br />
                <a href='admin-event-detail.php' >Product Detail</a><br />        
                <a href='list-event.php' >Product List</a><br />
            </div>
            <a href='admin_booking.php' >Booking Record</a><hr />
            <a href='Feedbackadmin.php' >Feedback Record</a><hr />
            <a href='LoginNew2a.php' >LogOut</a><hr />
        </div>
    </body>
</html>
