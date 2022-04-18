<?php
require_once "standart.php"; // Один раз подключаю "Шапку"
require_once "dbconnect.php"; // Один раз подключаю базу данных

// У меня есть 3 типа пользователей 
// 1) Обычный пользователь, уровень - A
// 2) Администратор. Может добавлять, удалять и менять новость, уровень - AA
// 3) Создатель. Может всё что администратор также может изменять статус пользователей или удалять (Выдать "Создателя" можно только через базу данных), уровень - AAA

if(!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"] == true && $_SESSION["admORuser"] !== "AA" && $_SESSION["admORuser"] !== "AAA")){ // Делаю защиту, никто ниже уровня AA не может зайти на эту страницу
    header("location: index.php"); //Отправляю на стандартную страницу
    exit;
}

    if (!empty($_POST['public'])) { // Добавляем данные в базу данных
        $stmt = mysqli_prepare($link, "INSERT INTO newss VALUES (null, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $theme, $text, $photo, $date); //шифруем чтобы не взломали

        $upfile = 'image/'; //Путь к фотографиям (Можно изменить)
        $uploadfile = $upfile . basename($_FILES['newsImage']['name']); //Добавляем фотографию к новости

        if (move_uploaded_file($_FILES['newsImage']['tmp_name'], $uploadfile)) { //Проверка на успешно загруженный файл
            $msg =  "Файл корректен и был успешно загружен.\n";
        } else {
            $msg =  "Возможная атака с помощью файловой загрузки!\n";
        }

        // Данные которые мы записываем на странице
        $theme = $_POST["nTitle"]; // Описание новости
        $text = $_POST["nText"]; // Новость
        $photo = $_FILES['newsImage']['name']; // Фотография
        $date = date("Y-n-j"); // Дата когда была добавлена эта новость

        mysqli_stmt_execute($stmt); //Выполняет запрос

        header("location: news.php");
    }
    
?>


<!DOCTYPE html>
<html>
<title>Админка</title>

<body>
<div class="container">
  <form enctype="multipart/form-data" action="admin.php" method="POST">
    <div>
      <label for="nTitle">Текст темы</label>
      <input type="text" id="nTitle" name="nTitle" placeholder="Тема тут...">
    </div>
    <div>
      <label for="nText">Текст Новости</label>
      <textarea id="nText" name="nText" placeholder="Новость тут..." style="height:200px"></textarea>
    </div>
    <div>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <label for="newsImage">Добавить изображение</label> 
    <input name="newsImage" type="file" class="form-control-file" id="newsImage">
  </div>
  <div>
    <input  type="submit" name="public" value="Опубликовать">
  </div>
  </form>
</div>
</body>
<div class="container">
<?php
if ($_SESSION["admORuser"] == "AAA") // Проверка на статус "Создатель"
{
echo '<a class="btn btn-success" type="button" href="owner.php">Изменить или удалить пользователей</a>'; //Ссылка на страницу для создателя 
}
?>
</div>
</html>

