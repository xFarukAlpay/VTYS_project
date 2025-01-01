<?php
$serverName = "DESKTOP-7R11JBR\\SQLEXPRESS";
$databaseName = "NewDatabase";

try {
    $dsn = "sqlsrv:Server=$serverName;Database=$databaseName";
    $conn = new PDO($dsn, NULL, NULL); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("<script>alert('Connection error: " . $e->getMessage() . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressLine1 = htmlspecialchars(trim($_POST['address1'] ?? ''));
    $addressLine2 = htmlspecialchars(trim($_POST['address2'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $postalCode = htmlspecialchars(trim($_POST['postal_code'] ?? ''));
    $country = htmlspecialchars(trim($_POST['country'] ?? ''));

    try {
        $conn->beginTransaction();

        $userSql = "SELECT TOP 1 user_id FROM Users ORDER BY user_id DESC";
        $stmt = $conn->query($userSql);
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            throw new Exception("No valid user found.");
        }

        $sql = "INSERT INTO Addresses (user_id, address_line1, address_line2, city, state, postal_code, country) 
                VALUES (:user_id, :address_line1, :address_line2, :city, :state, :postal_code, :country)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':address_line1', $addressLine1);
        $stmt->bindParam(':address_line2', $addressLine2);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':postal_code', $postalCode);
        $stmt->bindParam(':country', $country);

        $stmt->execute();
        $conn->commit();
        echo "<script>alert('Address added successfully.');</script>";
    } catch (Exception $e) {
        $conn->rollBack();
        die("<script>alert('Database error: " . $e->getMessage() . "');</script>");
    }
}
?>


?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ===== CSS ===== -->
        <link rel="stylesheet" href="assets/css/styles.css">

        <!-- ===== BOX ICONS ===== -->
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

        <title>Kullanıcı ve Adres Kaydı</title>  
    </head>
    <body>
        <div class="l-form">
            <div class="shape1"></div>
            <div class="shape2"></div>

            <div class="form">
                <img src="assets/img/authentication.svg" alt="Authentication" class="form__img">

                <form action="" method="POST" class="form__content">
                        <h1 class="form__title">Adres Bilgileri</h1>

                        <div class="form__div form__div-one">
                            <div class="form__icon">
                                <i class='bx bx-home'></i>
                            </div>

                            <div class="form__div-input">
                                <label for="address1" class="form__label">Adres 1</label>
                                <input type="text" name="address1" class="form__input" required>
                            </div>
                        </div>

                        <div class="form__div">
                            <div class="form__icon">
                                <i class='bx bx-home'></i>
                            </div>
                            <div class="form__div-input">
                                <label for="address2" class="form__label">Adres 2</label>
                                <input type="text" name="address2" class="form__input">
                            </div>
                        </div>

                        <div class="form__div">
                            <div class="form__icon">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div class="form__div-input">
                                <label for="postal_code" class="form__label">Posta Kodu</label>
                                <input type="text" name="postal_code" class="form__input" required>
                            </div>
                        </div>

                        <div class="form__div">
                            <div class="form__icon">
                                <i class='bx bx-globe'></i>
                            </div>
                            <div class="form__div-input">
                                <label for="country" class="form__label">Ülke</label>
                                <input type="text" name="country" class="form__input" required>
                            </div>
                        </div>

                        <div class="form__div">
                            <div class="form__icon">
                                <i class='bx bx-building-house'></i>
                            </div>
                            <div class="form__div-input">
                                <label for="city" class="form__label">Şehir</label>
                                <input type="text" name="city" class="form__input" required>
                            </div>
                        </div>

                        <input type="submit" class="form__button" value="Kaydet">
                    </form>

                <a href="#" class="form__forgot">Şifremi Unuttum?</a>

                <div class="form__social">
                    <span class="form__social-text">Sosyal medya ile giriş yap</span>

                    <a href="#" class="form__social-icon"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="form__social-icon"><i class='bx bxl-google'></i></a>
                    <a href="#" class="form__social-icon"><i class='bx bxl-instagram'></i></a>
                </div>
            </div>

        </div>

        <!-- ===== MAIN JS ===== -->
        <script src="assets/js/main.js"></script>
    </body>
</html>
