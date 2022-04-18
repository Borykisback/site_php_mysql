<?php

// Удаление аккаунта пользователя
$shield_user = $_SESSION["username"]; // Берём из текущей сессии ник пользователя
$shield_query_user = "SELECT * FROM admin WHERE username = '$shield_user'"; //Делаем запрос в базу данных
$shield_result_user = mysqli_query($link, $shield_query_user); //Берём результаты из базы данных и присваиваем их переменной

if(mysqli_num_rows($shield_result_user)==0) { // Проверяем на количество символ в нике
	if ($_SESSION != NULL){ // Сообщение о том что его удалили
		echo'<body onpageshow="myFunction()">';
		echo'<script>';
		echo'function myFunction() {';
		echo'	alert("Ваш аккаунт удалён");';
		echo'}';
		echo'</script>';
		echo'</body>';
	}
	$_SESSION =session_destroy(); // Если в нике нету символов тогда принудительно завершаем сессию для пользователя и выходим из его аккаунта
}

// Изменить статус пользователя 
$shield_admORuser = $_SESSION["admORuser"]; // Берём из текущей сессии статус пользователя
$shield_admORuser_username = $_SESSION["username"]; // Берём из текущей сессии ник пользователя
$shield_query_admORuser = "SELECT * FROM admin WHERE admORuser = '$shield_admORuser' AND username = '$shield_admORuser_username'" ; //Делаем запрос в базу данных
$shield_result_admORuser = mysqli_query($link, $shield_query_admORuser); //Берём результаты из базы данных и присваиваем их переменной
$shield_admORuser_row = mysqli_fetch_assoc($shield_result_admORuser); // Превращаю в строку

if ($shield_admORuser != $shield_admORuser_row["admORuser"]) //Делаю проверку на то чтобы статус текущей сессий совпадал с тем статусом который прописан в базе данных
{
	if ($_SESSION != NULL){ // Сообщение о том что пользователю изменили статус
		echo'<body onpageshow="myFunction()">';
		echo'<script>';
		echo'function myFunction() {';
		echo'	alert("Вы были кикнуты (Вам изменили статус)");';
		echo'}';
		echo'</script>';
		echo'</body>';
	}
	$_SESSION =session_destroy(); // Кикаем пользователя чтобы применить его новый статус 
}

?>
