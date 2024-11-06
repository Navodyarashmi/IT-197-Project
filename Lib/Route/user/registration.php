<?php

include_once('../../Functions/userFunction.php');


$result = userRegistration($_POST['userName'], $_POST['userEmail'], $_POST['userPass'], $_POST['userPhone'], $_POST['userNic']);



echo($result);

?>