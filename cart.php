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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue_payment'])) {
    try {
        $conn->beginTransaction();

        // Kullanıcıyı al
        $userSql = "SELECT TOP 1 user_id FROM Users ORDER BY user_id DESC";
        $stmt = $conn->query($userSql);
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            throw new Exception("No valid user found.");
        }

        // Sepet toplamını hesapla
        $totalAmount = htmlspecialchars(trim($_POST['total_amount'] ?? '0'));
        $totalAmount = floatval($totalAmount);

        if ($totalAmount <= 0) {
            throw new Exception("Invalid total amount.");
        }

        // Sipariş kaydını ekle
        $sql = "INSERT INTO Orders (user_id, order_date, total_amount) 
                VALUES (:user_id, :order_date, :total_amount)";
        $stmt = $conn->prepare($sql);

        $currentDate = date("Y-m-d H:i:s");

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':order_date', $currentDate);
        $stmt->bindParam(':total_amount', $totalAmount);

        $stmt->execute();
        $conn->commit();

        // Başarılı işlem mesajı ve yönlendirme
        echo "<script>alert('Order added successfully.');</script>";
        header("Location: cardinfo.php");
        exit(); // Yönlendirme sonrası betiğin çalışmasını durdur
    } catch (Exception $e) {
        $conn->rollBack();
        die("<script>alert('Database error: " . $e->getMessage() . "');</script>");
    }
}

?>
<!DOCTYPE html>
<html lang="tr">
<!-- =================================================================================== -->
<!--        Hakkında Sayfası   -->
<!-- =================================================================================== -->

<head>
    <script src="https://kit.fontawesome.com/bcb2c05d90.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Ticaret</title>
    <!-- Font Awesome Kütüphanesi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <!-- <link rel="stylesheet" href="css/all.min.css" /> -->
    <!-- Css -->
    <link rel="stylesheet" href="css/style.css" />
    <!--  -->
</head>

<body>
    <header>
        <a href="index.html" class="logo"><img src="img/logo.svg" alt="" class="logo" /></a>
        <nav>
            <ul id="navbar">
                <li><a href="index.html">Ana Sayfa</a></li>
                <li><a href="shop.html">Mağaza</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="about.html">Hakkında</a></li>
                <li><a href="contact.php">İletişim</a></li>
                <li id="lg-bag">
                    <a class="active" href="cart.php"><i class="fa fa-bag-shopping"></i></a>
                </li>
                <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
            </ul>
        </nav>
        <div id="mobile">
            <a href="cart.php"><i class="fa fa-bag-shopping"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </header>

    <!-- ===================== -->
    <!--    P-Header Bölümü   -->
    <!-- ===================== -->
    <section id="page-header" class="about-header">
        <h2>#hadi_konuşalım</h2>

        <p> Bir mesaj bırakın, sizden haber almayı seviyoruz! </p>
    </section>

    <!-- ===================== -->
    <!---     Sepet Detayları    --->
    <!-- ===================== --> 
    <section id="cart" class="section-p1">
        <table width="100%">
            <thead>
                <tr>
                    <td>Kaldır</td>
                    <td>Görsel</td>
                    <td>Ürün</td>
                    <td>Fiyat</td>
                    <td>Adet</td>
                    <td>Ara Toplam</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="#"><i class="far fa-times-circle"></i></a></td>
                    <td><img src="img/products/f1.jpg"></td>
                    <td>Çizgi Film Astronot T-Shirt</td>
                    <td>200 TL</td>
                    <td><input type="number" value="1"></td>
                    <td>200 TL</td>
                </tr>
                <tr>
                    <td><a href="#"><i class="far fa-times-circle"></i></a></td>
                    <td><img src="img/products/f2.jpg"></td>
                    <td>Çizgi Film Astronot T-Shirt</td>
                    <td>200 TL</td>
                    <td><input type="number" value="1"></td>
                    <td>200 TL</td>
                </tr>
                <tr>
                    <td><a href="#"><i class="far fa-times-circle"></i></a></td>
                    <td><img src="img/products/f3.jpg"></td>
                    <td>Çizgi Film Astronot T-Shirt</td>
                    <td>200 TL</td>
                    <td><input type="number" value="1"></td>
                    <td>200 TL</td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- ===================== -->
    <!--     Sepet Ek Detayları    -->
    <!-- ===================== -->
    <section id="cart-add" class="section-p1">
    <div id="coupon">
        <h3>Adres Ekleyiniz</h3>
        <div>
            <input type="text" placeholder="Adresinizi Girinizi">
            <a href="adress.php" class="normal">
                <button type="button" class="normal">Ekle</button>
            </a>
        </div>
    </div>
    <div id="subtotal">
        <h3>Sepet Toplamı</h3>
        <table>
            <tr>
                <td>Sepet Ara Toplamı</td>
                <td>600 TL</td>
            </tr>
            <tr>
                <td>Kargo</td>
                <td>50 TL</td>
            </tr>
            <tr>
                <td><strong>Toplam</strong></td>
                <td><strong>650 TL</strong></td>
            </tr>
        </table>
        <form method="POST" action="">
    <input type="hidden" name="total_amount" value="650">
    <button type="submit" name="continue_payment" class="normal">Ödemeye Devam Et</button>
</form>
<a href="cardinfo.php">Go to Card Information</a>

    </div>
</section>

    <!-- ===================== -->
    <!-----    Alt Bilgi    -------->
    <!-- ===================== -->
    <footer class="section-p1">
        <div class="col">
            <img src="img/logo.svg" alt="" class="logo" />
            <h4>İletişim</h4>
            <p><strong>Adres:</strong> İsmailiye, Mısır</p>
            <p><strong>Telefon:</strong> 0101010101010</p>
            <p><strong>Çalışma Saatleri:</strong> 10:00 - 23:00, Cumartesi - Perşembe</p>
            <div class="icon follow">
                <h4>Bizi Takip Edin</h4>
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-pinterest-p"></i>
                <i class="fab fa-youtube"></i>
            </div>
        </div>
        <div class="col">
            <h4>Hakkında</h4>
            <a href="#">Hakkımızda</a>
            <a href="#">Teslimat Bilgileri</a>
            <a href="#">Gizlilik Politikası</a>
            <a href="#">Şartlar ve Koşullar</a>
            <a href="#">Bize Ulaşın</a>
        </div>
        <div class="col">
            <h4>Hesabım</h4>
            <a href="#">Giriş Yap</a>
            <a href="#">Sepetim</a>
            <a href="#">İstek Listem</a>
            <a href="#">Siparişimi Takip Et</a>
            <a href="#">Yardım</a>
        </div>
        <div class="col install">
            <h4>Uygulamayı Yükle</h4>
            <p>App Store veya Google Play'den</p>
            <div class="row">
                <img src="img/pay/app.jpg" alt="" />
                <img src="img/pay/play.jpg" alt="" />
            </div>
            <p>Güvenli Ödeme Yöntemleri</p>
            <img src="img/pay/pay.png" alt="" />
        </div>
        <div class="copyright">
            <p>&copy; 2022, Veri tabanı Geliştirme Projesi</p>
        </div>
    </footer>
    <!-- ============================================================================================================ -->
    <script src="script.js"></script>
</body>

</html>
