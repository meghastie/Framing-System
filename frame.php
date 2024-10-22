
<title>Frame Price Estimator</title>
<h1>Frame Price Estimator</h1>

<?php
echo"<p>Please enter your photo sizes to get a framing cost estimate</p>\n";


//connecting to our database
$host = "devweb2024.cis.strath.ac.uk";
$username = "tjb22146";
$password = "Riu2othacaec";
$dbname = $username;
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

//checks if all form variables are set. if not, sets them to null.
$email = isset($_POST['email'])? $_POST['email'] : "";
$width = isset($_POST['width'])? $_POST['width'] : "";
$height = isset($_POST['height'])? $_POST['height'] : "";
$unit = isset($_POST['units'])? $_POST['units'] : "";
$delivery = isset($_POST['postage'])? $_POST['postage'] : "";
$vat = isset($_POST['inclVat']);
$receiveMail = isset($_POST['receiveMail']);

$isFormValid = true;

//if email is empty or not the correct format,and receive mail has been ticked, form is not valid
if ((empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) && $receiveMail) {
    $isFormValid = false;
}

//width and height must be entered. they must both be between the equivalent 0.2 or 2.0 m
if (empty($width) || empty($height) || !is_numeric($width) || !is_numeric($height)) {
    $isFormValid = false;
}else{
    switch ($unit) {
        case 'cm': // 20 and 200 cm
            if ($width < 20 || $width > 200 || $height < 20 || $height > 200) {
                echo "Error - Width and Height must be between 20 and 200 cm.\n";?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 100; //area is m^2 , so convert cm to meters
            break;
        case 'inch': //7.87 and 78.74 inches
            if ($width < 7.87 || $width > 78.74 || $height < 7.87 || $height > 78.74) {
                echo "Error - Width and Height must be between 7.87 and 78.74 inches.\n";?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 39.37;
            break;
        default: // our default, mm. 200 and 2000 mm.
            if ($width < 200 || $width > 2000 || $height < 200 || $height > 2000) {
                echo "Error - Width and Height must be between 200 and 2000 mm.\n"; ?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 1000;
            break;
    }
}

//form is valid, so we can continue with processing it
if ($isFormValid) {
    $width = round(($width / $divideByVal ), 2);
    $height = round(($height / $divideByVal),2);
    $A = $width * $height; //find the area in m^2

    //Postage is based on the longest edge L = max (width, height) , in meters. Economy postage costs 2L+7 pounds while rapid costs 3L+7 and next-day costs 4L+10. Default should be economy.
    $longestEdge = max($width, $height);
    $postagePrice = 0;

    if ($delivery == 'E') {
        $postagePrice = (2 * $longestEdge) + 7;
        $postageType = "Economy";
    } else if ($delivery == 'R') {
        $postagePrice = (3 * $longestEdge) + 7;
        $postageType = "Rapid";
    } else if ($delivery == 'ND') {
        $postagePrice = (4 * $longestEdge) + 7;
        $postageType = "Next Day";
    }

    $Price = round((($A * $A) + (70 * $A) + 7), 2); //calculate our area price to two decimal places
    $totalPrice = $Price + $postagePrice; // calcuate our total price by adding on the postage price

    $thankYou = "Thank you for using the Frame price estimator. You can place an order at this link: https://devweb2024.cis.strath.ac.uk/~tjb22146/index.html .\n";

    //display message based on whether vat was selected to be included in price or not
    if ($vat) {
        $vatPrice = round($totalPrice * 0.2, 2);
        $totalPrice = $totalPrice + $vatPrice;
        $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice including VAT.\n";

    } else {
        $vatPrice = 0;
        $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice .\n";
    }

    echo("<p>$message1</p>\n");

    //if user has opted in to receive mail, send email and their details to database
    if($receiveMail){
        mail($email, "Frame Cost", $message1 . $thankYou);

        $date = date('d/m/Y - H:i'); //date they have made order
        $totalPrice = $totalPrice - $vatPrice; //price ex vat for the database

        $sql = "INSERT INTO `tjb22146`.`framingSystem`(`Width`,`Height`,`Postage`,`E-mail`,`Price (ex vat)`,`Requested`) VALUES($width, $height, '$postageType', '$email', $totalPrice, '$date')"; //insert our data to the database

        if (!($conn->query($sql) === TRUE)) {
            die ("Error: " . $sql . "<br>" . $conn->error);
        }

        $conn->close();
    }

} else { //extra error handiling. show form back to user if invalid input
    if (empty($email) && $receiveMail) {
        echo "Error - Email is required. Please try again.\n";?> <br> <?php
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && $receiveMail){
        echo "Error - Email is not valid. Please enter a valid email and try again.\n";?> <br> <?php
    }



    if (empty($width) || empty($height) || !is_numeric($width) || !is_numeric($height)) {
        echo "Error - Both width and height is required. Please try again.\n";?> <br> <?php
    }
    ?>
    <form action="" method="post">
        Email: <input type="email" name="email" value = "<?php echo $email?>"><br>
        Photo Width: <input type="number" name="width" value = "<?php echo $width?>">
        <select name="units" >
            <option value="mm" <?php if($unit == 'mm')echo "selected" ?>> mm<br>
            <option value="cm" <?php if($unit == 'cm')echo "selected" ?>> cm <br>
            <option value="inch"<?php if($unit == 'inch')echo "selected" ?>> inch <br>
        </select> <br>
        Photo Height: <input type="number" name="height" value = "<?php echo $height?>"><br>
        Postage: <input type="radio" name="postage" value="E" <?php if($delivery == 'E')echo "checked" ?> > Economy
        <input type="radio" name="postage" value="R" <?php if($delivery == 'R')echo "checked" ?>> Rapid
        <input type="radio" name="postage" value="ND" <?php if($delivery == 'ND')echo "checked" ?>> Next Day<br>
        <input type="checkbox" name="inclVat" value="VAT" <?php if ($vat) echo "checked"; ?>> Include VAT in price<br>
        <input type="checkbox" name="receiveMail" value="receiveMail" <?php if ($receiveMail) echo "checked"; ?>> Receive mail and future information about my framing calculation<br>
        <input type="submit">
    </form>
    <?php
}
?>

</body>
</html>




