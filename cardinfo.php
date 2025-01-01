<?php
$serverName = "DESKTOP-7R11JBR\\SQLEXPRESS"; // MSSQL Server address
$databaseName = "NewDatabase"; // The name of the database

try {
    $dsn = "sqlsrv:Server=$serverName;Database=$databaseName";
    $conn = new PDO($dsn, NULL, NULL); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("<script>alert('Connection error: " . $e->getMessage() . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen veriler
    $email = $_POST['email'] ?? ''; // Kullanıcı e-posta adresi
    $cardOwner = $_POST['card_owner'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $cvc = $_POST['cvc'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';

    try {
        // Kullanıcı e-postasına göre user_id al
        $sql = "SELECT user_id FROM Users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $userId = $stmt->fetchColumn();

        if (!$userId) {
            die("<script>alert('Geçerli bir kullanıcı bulunamadı.');</script>");
        }

        // Veriyi PaymentInfo tablosuna ekleme
        $sql = "INSERT INTO PaymentInfo (user_id, card_owner, card_number, cvc, expiry_date) 
                VALUES (:user_id, :card_owner, :card_number, :cvc, :expiry_date)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':card_owner', $cardOwner);
        $stmt->bindParam(':card_number', $cardNumber);
        $stmt->bindParam(':cvc', $cvc);
        $stmt->bindParam(':expiry_date', $expiryDate);

        if ($stmt->execute()) {
            echo "<script>alert('Kart bilgileri başarıyla eklendi.');</script>";
        } else {
            echo "<script>alert('Kart bilgileri eklenemedi.');</script>";
        }
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
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <title>Card Information Form</title>  
</head>
<body>
    <div class="l-form">
        <div class="shape1"></div>
        <div class="shape2"></div>

        <div class="form">
            <img src="assets/img/authentication.svg" alt="" class="form__img">

            <form action="" method="POST" class="form__content">
                <h1 class="form__title">Card Information</h1>

                <div class="form__div">
                    <div class="form__icon">
                        <i class='bx bx-envelope'></i>
                    </div>
                    <div class="form__div-input">
                        <label for="email" class="form__label">Email</label>
                        <input type="email" name="email" class="form__input" required>
                    </div>
                </div>

                <div class="form__div form__div-one">
                    <div class="form__icon">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div class="form__div-input">
                        <label for="card_owner" class="form__label">Card Owner</label>
                        <input type="text" name="card_owner" class="form__input" required>
                    </div>
                </div>

                <div class="form__div form__div-one">
                    <div class="form__icon">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div class="form__div-input">
                        <label for="card_number" class="form__label">Card Number</label>
                        <input type="text" name="card_number" class="form__input" pattern="\d{16}" title="Please enter a 16-digit card number" required>
                    </div>
                </div>

                <div class="form__div">
                    <div class="form__icon">
                        <i class='bx bx-lock'></i>
                    </div>
                    <div class="form__div-input">
                        <label for="cvc" class="form__label">CVC</label>
                        <input type="text" name="cvc" class="form__input" pattern="\d{3}" title="Please enter a 3-digit CVC" required>
                    </div>
                </div>

                <div class="form__div">
                    <div class="form__icon">
                        <i class='bx bx-calendar'></i>
                    </div>
                    <div class="form__div-input">
                        <label for="expiry_date" class="form__label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form__input" required>
                    </div>
                </div>

                <input type="submit" class="form__button" value="Save">
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
