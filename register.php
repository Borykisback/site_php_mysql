<?php
// Включаю файл конфигурации
require_once "dbconnect.php";
session_start();

if(isset($_SESSION["loggedin"])){
    header("location: index.php");
    exit;
}

// Определяю переменные и инициализирую пустыми значениями
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Обрабатываю данные формы при отправке формы
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Подтвердить имя пользователя
    if(empty(trim($_POST["username"]))){
        $username_err = "Пожалуйста введите имя пользователя.";
    } else{
        // Подготовьте оператор выбора
        $sql = "SELECT id FROM admin WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Привязываю переменные к подготовленному оператору как параметры
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Установить параметры
            $param_username = trim($_POST["username"]);
            
            // Попытка выполнить подготовленный оператор
            if(mysqli_stmt_execute($stmt)){
                /* сохранить результат */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Это имя пользователя уже занято.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Ой! Что-то пошло не так. Пожалуйста, попробуйте позже.";
            }

            // Закрыть заявление
            mysqli_stmt_close($stmt);
        }
    }
    
    // Подтвердить пароль
    if(empty(trim($_POST["password"]))){
        $password_err = "Пожалуйста, введите пароль.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Пароль должен содержать не менее 6 символов.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Повторить пароль
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Пожалуйста, подтвердите пароль.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Пароль не совпадает.";
        }
    }
    
    // Проверяю ошибки ввода перед вставкой в базу данных
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Подготавливаю инструкцию вставки
        $sql = "INSERT INTO admin (username, password, admORuser) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Привязываю переменные к подготовленному оператору как параметры
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_aoru);
            
            // Установить параметры
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Создает хеш пароля
            $param_aoru = "A";

            // Попытка выполнить подготовленный оператор
            if(mysqli_stmt_execute($stmt)){
                // Перенаправить на страницу входа
                header("location: login.php");
            } else{
                echo "Ой! Что-то пошло не так. Пожалуйста, попробуйте позже.";
            }

            // Закрыть заявление
            mysqli_stmt_close($stmt);
        }
    }
    
    // Закрыть соединение
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 10px; margin-left:40%;margin-top:1% }
    </style>
</head>
<body>
    <div class="wrapper"> 
        <h2>Регистрация</h2> 
        <p>Пожалуйста, заполните эту форму, чтобы создать учетную запись.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Пользователь</label> 
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"> 
                <span class="invalid-feedback"><?php echo $username_err; ?></span> 
            </div>    
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"> 
                <span class="invalid-feedback"><?php echo $password_err; ?></span> 
            </div>
            <div class="form-group">
                <label>Подтвердите пароль</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"> 
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span> 
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Отправить"> 
            </div>
            <p>Уже есть аккаунт? <a href="login.php">Авторизуйтесь здесь</a>.</p> 
        </form>
    </div>    
</body>
</html>