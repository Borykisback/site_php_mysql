<?php
require_once "dbconnect.php";
?>

<!DOCTYPE html>
<html>
<title>Главная</title>

<?php
include "standart.php";
echo '<h3 style = "text-align: center;">Ваш статус  - '.$who.'</h3><br>';
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
echo '<h3 style = "text-align: center;">Ваш никнейм - '.$_SESSION["username"].'</h3><br>';
}
else {
	echo '<h3 style = "text-align: center;">Войдите в ваш аккаунт</h3>';
}

?>

</body>
</html>