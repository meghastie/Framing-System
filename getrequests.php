
<form action="" method="post">
    <input type="text" name="password">
    <input type="submit">



<?php
$password = isset($_POST['password'])? $_POST['password'] : "";

if($password == "WinTheGameNow"){
    //display table
}

//Add a new page - getrequests.php that lists the entries in the database as a simple HTML table. Initially the page should only show a password field and submit button. On submission back to getrequests.php it should show the table if the password is correct. A single password of WinTheGameNow should be used (normally bad practice!).
//
//Show in bold any orders that are overdue (over 1 day old for next-day, over 3 days old for rapid and over 7 days old for economy postage).
?>

