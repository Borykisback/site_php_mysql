<?php
Include("standart.php");
session_reset();
// Убедитесь, что пользователь уже вошел в систему, если да, перенаправьте его на страницу приветствия
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Определите переменные и инициализируйте пустыми значениями
$admORuser = "";
$username = $password = "";
$username_err = $password_err = "";
 
// Обработка данных формы при отправке формы
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Проверьте, пусто ли имя пользователя
    if(empty(trim($_POST["username"]))){
        $username_err = "Введите имя пользователя.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Проверьте, пуст ли пароль
    if(empty(trim($_POST["password"]))){
        $password_err = "Введите пароль.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Подтвердить учетные данные
    if(empty($username_err) && empty($password_err)){
        // Подготовьте оператор выбора
        $sql = "SELECT id, username, admORuser, password FROM admin WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Привяжите переменные к подготовленному оператору как параметры
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Установить параметры
            $param_username = $username;
            
            // Попытка выполнить подготовленный оператор
            if(mysqli_stmt_execute($stmt)){
                // Сохраняем результат
                mysqli_stmt_store_result($stmt);
                
                // Проверьте, существует ли имя пользователя, если да, то подтвердите пароль
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Привязать переменные результата
                    mysqli_stmt_bind_result($stmt, $id, $username, $admORuser, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Пароль правильный, поэтому начинаем новый сеанс
                            session_start();
                            
                            // Сохраняем данные в переменных сеанса
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["admORuser"] = $admORuser;
                            
                            // Перенаправляем пользователя на страницу приветствия
                            header("location: index.php");
                        } else{
                            //Отображаем сообщение об ошибке, если пароль недействителен
                            $password_err = "Указан неверный пароль";
                        }
                    }
                } else{
                    // Отображение сообщения об ошибке, если имя пользователя не существует
                    $username_err = "Нет пользователя с таким именем пользователя";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Закрыть оператор
            mysqli_stmt_close($stmt);
        }
    }
    
    // Закрываем соединение
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>
<body>
    <div class="input-group mb-3" style="margin-left: auto; margin-right: auto; width: 21em">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <legend>Авторизация</legend>
        
            <div class="mb-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="username" class="form-label">Пользователь</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Напишите имя пользователя" value="<?php echo $username; ?>">
                <span class="form-text"><?php echo $username_err; ?></span>
            </div>    
            <div class="mb-3 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="pas" class="form-label">Пароль</label>
                <input type="password" id="pas" name="password" class="form-control" placeholder="Напишите пароль">
                <span class="form-text"><?php echo $password_err; ?></span>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Войти">
            </div>
            <p>Хотите зерегистрироваться?<a href="register.php">Перейдите по ссылке</a>.</p>
        </form>
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>   
</body>

</html>