
<title>Frame Price Estimator Requests</title>


<?php
$formPassword = isset($_POST['password'])? $_POST['password'] : '';

//connect to our database
$host = "devweb2024.cis.strath.ac.uk";
$username = "tjb22146";
$password = "Riu2othacaec";
$dbname = $username;
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

//get all orders to display
$sql = "SELECT * FROM `tjb22146`.`framingSystem`";
$result = $conn->query($sql);
if ($result === FALSE) {
    die ("Error: " . $sql . "<br>" . $conn->error);//FIXME only use during debugging
}

//display table if the correct password is entered
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

            //calculate if our order is overdue using DateTime class
            $requestedDate = DateTime::createFromFormat('d/m/Y - H:i', $row['Requested']);
            $currentDate = new DateTime();

            $interval = $requestedDate->diff($currentDate);
            $daysInterval = $interval->d;
            $overdue = false;

            $postageType = $row['Postage'];

            if(($postageType == 'Economy' && $daysInterval > 7) || ($postageType == 'Rapid' && $daysInterval > 3) || ($postageType == 'Next Day' && $daysInterval > 1) ){
                $overdue = true;
            }

            echo "<tr>";
            foreach ($row as $data) {
                if($overdue){
                    echo "<td  style = 'width: 166px; text-align: center; font-weight:bold'>" . htmlspecialchars($data) . "</td>";
                }else{
                    echo "<td  style = 'width: 166px; text-align: center'>" . htmlspecialchars($data) . "</td>";
                }

            }
            echo "</tr>";
        }
    echo "</table>";

}else{ //if the wrong password is entered, show form back to user
    if($_SERVER["REQUEST_METHOD"] == "POST"){ //only send error message IF the form has been submitted
        echo "Wrong password. Please try again.";
    }
    ?>
    <form action="" method="post">
        <input type="text" name="password" value = "<?php echo $formPassword?>">
        <input type="submit">
    </form>
<?php
}
$conn->close();
?>

