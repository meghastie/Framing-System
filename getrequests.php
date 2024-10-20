

<?php
$formPassword = isset($_POST['password'])? $_POST['password'] : "";

$host = "devweb2024.cis.strath.ac.uk";
$username = "tjb22146";
$password = "Riu2othacaec";
$dbname = $username;
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}
$sql = "SELECT * FROM `tjb22146`.`framingSystem`";
$result = $conn->query($sql);
if ($result === FALSE) {
    die ("Error: " . $sql . "<br>" . $conn->error);//FIXME only use during debugging
}

if($formPassword == "WinTheGameNow"){
    echo" <table style = 'width: 1000px'>";
        echo"<tr>
            <th>Width</th>
            <th>Height</th>
            <th>Postage</th>
            <th>Email</th>
            <th>Price (ex vat)</th>
            <th>Requested</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $data) {
                echo "<td  style = 'width: 166px; text-align: center'>" . htmlspecialchars($data) . "</td>";
            }
            echo "</tr>";
        }
    echo "</table>";

//Add a new page - getrequests.php that lists the entries in the database as a simple HTML table. Initially the page should only show a password field and submit button. On submission back to getrequests.php it should show the table if the password is correct. A single password of WinTheGameNow should be used (normally bad practice!).
//
//Show in bold any orders that are overdue (over 1 day old for next-day, over 3 days old for rapid and over 7 days old for economy postage).
}else{
    echo"Wrong password. Please try again";
    ?>
    <form action="getrequests.php" method="post">
        <input type="text" name="password" value = "<?php echo $formPassword?>">
        <input type="submit">
    </form>
<?php
}
$conn->close();
?>

