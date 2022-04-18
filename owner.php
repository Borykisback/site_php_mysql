<?php
include "standart.php"; // подключаю "Шапку"
require_once "dbconnect.php"; // Один раз подключаю базу данных

if(!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"] === true && $_SESSION["admORuser"] !== "AAA")){ // проверяю чтобы статус был не ниже "Создатель"
    header("location: index.php"); // Если статут ниже "Создатель" перемещаю пользователя на стандартную страницу
    exit;
}
?>

<?php
$query1 = "SELECT id, username, admORuser FROM admin"; //Делаю запроб к базе данных
$result1 = mysqli_query($link, $query1);
while ($row1 = mysqli_fetch_assoc($result1)) {
echo '<ul class="list-group">';
echo '<li class="list-group-item"> ID = '.$row1["id"].' | Имя = '.$row1["username"].' | Статус = '.$row1["admORuser"].'</li>'; //Создаю список из полученных данных 
}

if (!empty($_POST["userdelete"])) // Создаю удаление выбранного пользователя (По кнопке)
{
$stmt = mysqli_prepare($link, "DELETE FROM admin WHERE id = ?"); //Удаляет пользователя по id
mysqli_stmt_bind_param($stmt, 'i', $userid);
$userid = $_POST["the_ID"]; // Проверка id пользователя
mysqli_stmt_execute($stmt);
session_reset();
header("location: owner.php");
}

if (!empty($_POST['give'])) {   // Изменяет статус пользователя
    $stmt1 = mysqli_prepare($link, "UPDATE admin SET admORuser = ? WHERE id = ?"); //Изменяет данные в базе данных
    mysqli_stmt_bind_param($stmt1, 'si', $admoruser, $idd);
    
    $admoruser = $_POST["the_admin"];  // Запись статуса (можно выбрать только Пользователя(A) или Администратора(AA))
    $idd = $_POST["the_ID"]; // Проверка id пользователя

    mysqli_stmt_execute($stmt1);
    session_reset();
    header("location: owner.php");
}
?>
<form method="POST">
<div class="container">
<select class="form-select" size="7" aria-label="size 3 select example" name="the_ID"> 
<?php foreach($result1 as $option) : ?> 
        <option value="<?php echo $option['id']; ?>">ID = <?php echo $option['id']; ?></option> 
<?php endforeach; ?>
</select>
<select class="form-select" aria-label="Default select example" name="the_admin"> 
    <option value="AA">Администратор (AA)</option>
    <option value="A" selected>Пользователь (A)</option>
</select>
<input type="submit" name="give" value="Выдать"> 
<input type="submit" name="userdelete" value="Удалить Аккаунт"> 
</div>
</form>