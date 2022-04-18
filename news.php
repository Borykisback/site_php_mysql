<!DOCTYPE html>
<html>
<title>Новости</title>

<?php
include "standart.php"; //Подключаем "Шапку"
?>

<?php
require_once "dbconnect.php"; // Один раз подключаю базу данных
    $query = "SELECT id, Theme, Text, Photo, Date FROM newss ORDER BY Date DESC"; //Запрос к базе данных
    $result = mysqli_query($link, $query); // результат записать в эту переменную

    if (!empty($_POST["iddelete"])) // Удаление новости из базы данных 
    {
    $stmt = mysqli_prepare($link, "DELETE FROM newss WHERE id = ?"); // Ищем по id и удаляем
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $id = $_POST["iddelete"]; // id по которой мы находим новость
    mysqli_stmt_execute($stmt);
    header("location: news.php");
    }

    if (!empty($_POST['upnew'])) {  // Изменение новости
    $stmt2 = mysqli_prepare($link, "UPDATE newss SET Theme = ?, Text = ? WHERE id = ?"); //Ищем по id и изменяем данные
    mysqli_stmt_bind_param($stmt2, 'ssi', $uptheme, $uptext, $id);
    
    $uptheme = $_POST["uptheme"]; // Новая тема
    $uptext = $_POST["uptext"]; // Новая новость
    $id = $_POST["upnew"]; // id по которой мы находим новость
    mysqli_stmt_execute($stmt2);
    header("location: news.php");
    }
?>


<?php
// Все стили которые ниже сделаны с помощью Bootstrap
error_reporting(0); //Нужно для того что-бы закрыть все предупреждения (включить при разработке)
while ($row = mysqli_fetch_assoc($result)) { //Всё что мы получили в переменую $result превращаем в ассоциативный массив, он показывает каждую новость пока(while) не закончились данные в базе данных
echo '<div class="card mb-3" style="max-width: 100%;">';
      echo '<div class="row g-0">';
            echo '<div class="col-md-3">';
            echo '<img width="200" height="211" src="image/'.$row["Photo"].'" alt="Fail">'; //Показывает фотографию новости и делает её размер 200x211
            echo '</div>';
      echo '<div class="col-md-8">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">'.$row["Theme"].'</h5>'; //Здесь записана тема
      echo '<p class="card-text">'.$row["Text"].'</p>'; //Здесь текс новости
      echo '<p class="card-text"><small class="text-muted">'.$row["Date"].'</small></p>'; // Дата добавления новости

if ($_SESSION["admORuser"] == "AAA" || $_SESSION["admORuser"] == "AA") // Проверка на статус не ниже администратора 
{
// В коде который указан ниже я создаю модальное окно в котором могу изменить или удалить новость, сделано спомощью Bootstap
echo '<form action="news.php" method="post">';
echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal' . $row["id"] . '">Изменить/Удалить</button>'; //Кнопка которая открывает модальное окно по id новости
echo '<div class="modal fade" id="exampleModal' . $row["id"] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">'; // Указываем id новости
      echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
                  echo '<div class="modal-header">';
                  echo '<h5 class="modal-title" id="exampleModalLabel" >Изменить/Удалить' . $row["id"] . '</h5>'; //Указываем id новости в модальном окне
                  echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'; //Закрыть без изменений
                  echo '<span aria-hidden="true">&times;</span>';
                  echo '</button>';
                  echo '</div>';
            echo '<div class="modal-body">';
            echo '<form>';
            echo '<div class="form-group" >';
            echo '<label for="recipient-name" class="col-form-label">Тема:</label>'; 
            echo '<input id="uptheme" name="uptheme" type="text" class="form-control" id="recipient-name" value="'.$row["Theme"].'" >';  // Меняем текущие значение темы на новые
            echo '</div>';
                  echo '<div class="form-group" >';
                  echo '<label for="message-text" class="col-form-label">Текст новости:</label>';
                  echo '<textarea id="uptext" name="uptext" class="form-control" style="height:200px" id="message-text">'.$row["Text"].'</textarea>'; // Меняем текущие значение новости на новые
                  echo '</div>';
            echo ' </form>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<input type="hidden" name="upnew" value="' . $row["id"] . '" /> 
            <input type="submit" value="Сохранить">
            </form>'; //Запускаем обработку данных которая написана сверху она сохранит всё что мы изменили 
            echo '<form action="news.php" method="post">
            <input type="hidden" name="iddelete" value="' . $row["id"] . '" />
            <input type="submit" value="Удалить">
            </form>'; //Запускаем удаление данных которое прописано сверху
            // Все данные находим по id  
            echo '</div>';
      echo '</div>';
echo '</div>';
echo '</div>';
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

}
?>
