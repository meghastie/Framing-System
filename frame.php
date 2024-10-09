<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Frame Price Estimator</title>
</head>
<body>


<h1>Frame Price Estimator</h1>
<p>Please enter your photo sizes to get a framing cost estimate</p>

<?php
$width = isset($_POST['width'])? $_POST['width'] : " ";
$height = isset($_POST['height'])? $_POST['height'] : " ";
$unit = isset($_POST['units'])? $_POST['units'] : " ";
$delivery = isset($_POST['postage'])? $_POST['postage'] : " ";
$vat = isset($_POST['inclVat'])? true : false;

if($width !== " " && $height !== " "){
    $divideByVal = 1000;

    if($unit == 'cm'){
        $divideByVal = 100;
    }else if ($unit == 'inch'){
        $divideByVal = 39.37;
    }

    $width = $width / $divideByVal;
    $height = $height / $divideByVal;

    $longestEdge = max($width, $height);

    $postagePrice = 0;

    if($delivery == 'E'){
        $postagePrice = (2 * $longestEdge) + 7;
        $postageType = "economy";
    }else if($delivery == 'R'){
        $postagePrice = (3 * $longestEdge) + 7;
        $postageType = "rapid";
    }else if($delivery == 'ND'){
        $postagePrice = (4 * $longestEdge) + 7;
        $postageType = "next day";
    }

    $A = $width * $height;

    $Price = round((($A * $A) + (70 * $A) + 7),2);


    if($vat){
        $totalPrice = $Price + $postagePrice;
        $vatPrice = round($totalPrice * 0.2, 2);
        $totalPrice = $totalPrice + $vatPrice;
        echo("<p>Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice including VAT.</p>\n");
    }else{
        $totalPrice = $Price + $postagePrice;
        echo("<p>Your frame will cost £$Price plus $postageType postage of £$postagePrice giving a total price of £$totalPrice.</p>\n");
    }
}
?>

<form action="" method="post">
    Photo Width: <input type="number" name="width">
    <select name="units" >
        <option value="mm" > mm<br>
        <option value="cm"> cm <br>
        <option value="inch"> inch <br>
    </select> <br>
    Photo Height: <input type="number" name="height"><br>
    Postage: <input type="radio" name="postage" value="E" checked> Economy
    <input type="radio" name="postage" value="R"> Rapid
    <input type="radio" name="postage" value="ND"> Next Day<br>
    <input type="checkbox" name="inclVat" value="VAT"> Include VAT in price<br>
    <input type="submit">
</form>

</body>
</html>


