<?php
$serverName = "DESKTOP-7R11JBR\\SQLEXPRESS"; // MSSQL Server address
$databaseName = "NewDatabase"; // The name of the database

try {
    // Correct DSN for MSSQL Server using PDO
    $dsn = "sqlsrv:Server=$serverName;Database=$databaseName";
    $conn = new PDO($dsn, NULL, NULL); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("<script>alert('Connection error: " . $e->getMessage() . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen veriler
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = empty($_POST['phone']) ? NULL : $_POST['phone'];
    $registrationDate = date('Y-m-d', strtotime($_POST['registration_date']));
    $password = $_POST['password'] ?? '';

    // Şifreyi hashleme
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Veriyi Users tablosuna ekleme
        $sql = "INSERT INTO Users (username, email, phone, registration_date, password_hash) 
                OUTPUT INSERTED.user_id
                VALUES (:username, :email, :phone, :registration_date, :password_hash)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':registration_date', $registrationDate);
        $stmt->bindParam(':password_hash', $hashedPassword);

        $stmt->execute();
        $userId = $stmt->fetchColumn(); // Kaydedilen kullanıcının ID'sini al

        echo "<script>alert('Kullanıcı başarıyla kaydedildi. Kullanıcı ID: $userId');</script>";
    } catch (Exception $e) {
        die("<script>alert('Veritabanı hatası: " . $e->getMessage() . "');</script>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ===== CSS ===== -->
        <link rel="stylesheet" href="assets/css/styles.css">

        <!-- ===== BOX ICONS ===== -->
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

        <title>Login form responsive</title>  
    </head>
    <body>
        <div class="l-form">
            <div class="shape1"></div>
            <div class="shape2"></div>

            <div class="form">
                <img src="assets/img/authentication.svg" alt="" class="form__img">

                <form action="" method="POST" class="form__content">
                    <h1 class="form__title">Welcome</h1>

                    <div class="form__div form__div-one">
                        <div class="form__icon">
                            <i class='bx bx-user-circle'></i>
                        </div>

                        <div class="form__div-input">
                            <label for="username" class="form__label">Kullanıcı Adı</label>
                            <input type="text" name="username" class="form__input" required>
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='bx bx-envelope'></i>
                        </div>
                        <div class="form__div-input">
                            <label for="email" class="form__label">Email</label>
                            <input type="email" name="email" class="form__input" required>
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='bx bx-phone'></i>
                        </div>
                        <div class="form__div-input">
                            <label for="phone" class="form__label">Telefon</label>
                            <input type="text" name="phone" class="form__input">
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='bx bx-calendar'></i>
                        </div>
                        <div class="form__div-input">
                            <label for="registration_date" class="form__label">Kayıt Tarihi</label>
                            <input type="date" name="registration_date" class="form__input" required>
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='bx bx-lock'></i>
                        </div>

                        <div class="form__div-input">
                            <label for="password" class="form__label">Password</label>
                            <input type="password" name="password" class="form__input" required>
                        </div>
                    </div>
                    <a href="#" class="form__forgot">Forgot Password?</a>

                    <input type="submit" class="form__button" value="Login">

                    <div class="form__social">
                        <span class="form__social-text">Our login with</span>

                        <a href="#" class="form__social-icon"><i class='bx bxl-facebook'></i></a>
                        <a href="#" class="form__social-icon"><i class='bx bxl-google'></i></a>
                        <a href="#" class="form__social-icon"><i class='bx bxl-instagram'></i></a>
                    </div>
                </form>
            </div>

        </div>
        
        <!-- ===== MAIN JS ===== -->
        <script src="assets/js/main.js"></script>
    </body>
</html>