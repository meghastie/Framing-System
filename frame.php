
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


$email = isset($_POST['email'])? $_POST['email'] : "";
$width = isset($_POST['width'])? $_POST['width'] : "";
$height = isset($_POST['height'])? $_POST['height'] : "";
$unit = isset($_POST['units'])? $_POST['units'] : "";
$delivery = isset($_POST['postage'])? $_POST['postage'] : "";
$vat = isset($_POST['inclVat']);

$isFormValid = true;

    if (empty($email)) {
        $isFormValid = false;
    }

    if (empty($width) || empty($height)) {
        $isFormValid = false;
    }else{
        switch ($unit) {
            case 'cm':
                if ($width < 20 || $width > 200 || $height < 20 || $height > 200) {
                    echo "Width and Height must be between 20 and 200 cm\n";?> <br> <?php
                    $isFormValid = false;
                }
                $divideByVal = 100;
                break;
            case 'inch':
                if ($width < 7.87 || $width > 78.74 || $height < 7.87 || $height > 78.74) {
                    echo "Width and Height must be between 7.87 and 78.74 inches\n";?> <br> <?php
                    $isFormValid = false;
                }
                $divideByVal = 39.37;
                break;
            default:
                if ($width < 200 || $width > 2000 || $height < 200 || $height > 2000) {
                    echo "Width and Height must be between 200 and 2000 mm\n"; ?> <br> <?php
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

        if ($vat) {
            $totalPrice = $Price + $postagePrice;
            $vatPrice = round($totalPrice * 0.2, 2);
            $totalPrice = $totalPrice + $vatPrice;
            $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice including VAT.\n";
            echo("<p>$message1</p>\n");
            mail($email, "Frame Cost", $message1 . $thankYou);
        } else {
            $totalPrice = $Price + $postagePrice;
            $message1 = "Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice .\n";
            echo("<p>$message1</p>\n");
            mail($email, "Frame Cost", $message1 . $thankYou);
        }
    } else {
        if (empty($email)) {
            echo "Email is required\n";?> <br> <?php
        }

        if (empty($width) || empty($height)) {
            echo "Both width and height is required\n";?> <br> <?php
        }
        ?>
<form action="" method="post">
    Email: <input type="email" name="email" value = "<?php echo $email?>"><br>
    Photo Width: <input type="number" name="width" value = "<?php echo $width?>">
    <select name="units" >
        <option value="mm" > mm<br>
        <option value="cm"> cm <br>
        <option value="inch"> inch <br>
    </select> <br>
    Photo Height: <input type="number" name="height" value = "<?php echo $height?>"><br>
    Postage: <input type="radio" name="postage" value="E" checked> Economy
    <input type="radio" name="postage" value="R"> Rapid
    <input type="radio" name="postage" value="ND"> Next Day<br>
    <input type="checkbox" name="inclVat" value="VAT" <?php if ($vat) echo "checked"; ?>> Include VAT in price<br>
    <input type="submit">
</form>
<?php
}
?>

</body>
</html>

