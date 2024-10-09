
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



