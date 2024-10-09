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
if($width !== " " && $height !== " "){
    $width = $width / 1000;
    $height = $height / 1000;
    $A = $width * $height;
    $Price = round((($A * $A) + (70 * $A) + 7),2);
    echo("<p>Your frame will cost £$Price</p>\n");
}
?>

<form action="" method="post">
    Photo Width: <input type="number" name="width"><br>
    Photo Height: <input type="number" name="height"><br>
    <input type="submit">
</form>

</body>
</html>




