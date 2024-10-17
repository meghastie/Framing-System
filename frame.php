
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Frame Price Estimator</title>
</head>
<body>


<h1>Frame Price Estimator</h1>

<?php
echo"<p>Please enter your photo sizes to get a framing cost estimate</p>\n";


$host = "devweb2024.cis.strath.ac.uk";
$username = "tjb22146";
$password = "Riu2othacaec";
$dbname = $username;
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

$email = isset($_POST['email'])? $_POST['email'] : "";
$width = isset($_POST['width'])? $_POST['width'] : "";
$height = isset($_POST['height'])? $_POST['height'] : "";
$unit = isset($_POST['units'])? $_POST['units'] : "";
$delivery = isset($_POST['postage'])? $_POST['postage'] : "";
$vat = isset($_POST['inclVat']);
$receiveMail = isset($_POST['receiveMail']);

$isFormValid = true;

if ((empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) && $receiveMail) {
    $isFormValid = false;
}


if (empty($width) || empty($height)) {
    $isFormValid = false;
}else{
    switch ($unit) {
        case 'cm':
            if ($width < 20 || $width > 200 || $height < 20 || $height > 200) {
                echo "Error - Width and Height must be between 20 and 200 cm.\n";?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 100;
            break;
        case 'inch':
            if ($width < 7.87 || $width > 78.74 || $height < 7.87 || $height > 78.74) {
                echo "Error - Width and Height must be between 7.87 and 78.74 inches.\n";?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 39.37;
            break;
        default:
            if ($width < 200 || $width > 2000 || $height < 200 || $height > 2000) {
                echo "Error - Width and Height must be between 200 and 2000 mm.\n"; ?> <br> <?php
                $isFormValid = false;
            }
            $divideByVal = 1000;
            break;
    }
}

if ($isFormValid) {
    $width = $width / $divideByVal;
    $height = $height / $divideByVal;

    $longestEdge = max($width, $height);

    $postagePrice = 0;

    if ($delivery == 'E') {
        $postagePrice = (2 * $longestEdge) + 7;
        $postageType = "economy";
    } else if ($delivery == 'R') {
        $postagePrice = (3 * $longestEdge) + 7;
        $postageType = "rapid";
    } else if ($delivery == 'ND') {
        $postagePrice = (4 * $longestEdge) + 7;
        $postageType = "next day";
    }

    $A = $width * $height;

    $Price = round((($A * $A) + (70 * $A) + 7), 2);

    $thankYou = "Thank you for using the Frame price estimator. You can place an order at this link: https://devweb2024.cis.strath.ac.uk/~tjb22146/index.html .\n";

    $totalPrice = $Price + $postagePrice;

    if ($vat) {
        $vatPrice = round($totalPrice * 0.2, 2);
        $totalPrice = $totalPrice + $vatPrice;
        $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice including VAT.\n";

    } else {
        $vatPrice = 0;
        $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice .\n";
    }

    echo("<p>$message1</p>\n");

    if($receiveMail){
        mail($email, "Frame Cost", $message1 . $thankYou);

        $date = date('d/m/Y - H:i');
        $totalPrice = $totalPrice - $vatPrice;

        $sql = "INSERT INTO `tjb22146`.`framingSystem`(`Width`,`Height`,`Postage`,`E-mail`,`Price (ex vat)`,`Requested`) VALUES($width, $height, '$postageType', '$email', $totalPrice, '$date')";

        if (!($conn->query($sql) === TRUE)) {
            die ("Error: " . $sql . "<br>" . $conn->error);//FIXME only use during debugging
        }

        $conn->close();
    }

} else {
    if (empty($email) && $receiveMail) {
        echo "Error - Email is required. Please try again.\n";?> <br> <?php
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && $receiveMail){
        echo "Error - Email is not valid. Please enter a valid email and try again.\n";?> <br> <?php
    }



    if (empty($width) || empty($height)) {
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




