<?php
$serverName = "DESKTOP-7R11JBR\\SQLEXPRESS"; // MSSQL Server address
$databaseName = "NewDatabase"; // The name of the database

try {
    // MSSQL Server bağlantısını oluştur
    $dsn = "sqlsrv:Server=$serverName;Database=$databaseName";
    $conn = new PDO($dsn, NULL, NULL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("<script>alert('Connection error: " . $e->getMessage() . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen veriler
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    try {
        // En son kullanıcı id'sini al
        $userSql = "SELECT TOP 1 user_id FROM Users ORDER BY user_id DESC";
        $userStmt = $conn->query($userSql);
        $latestUser = $userStmt->fetch(PDO::FETCH_ASSOC);
        $userId = $latestUser['user_id'] ?? null;

        if ($userId !== null) {
            // Veriyi veritabanına ekle
            $sql = "INSERT INTO Comments (user_id, subject, message, created_at) 
                    VALUES (:user_id, :subject, :message, GETDATE())";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                echo "<script>alert('Yorum başarıyla eklendi.');</script>";
            } else {
                echo "<script>alert('Yorum eklenemedi.');</script>";
            }
        } else {
            echo "<script>alert('Kullanıcı bilgisi bulunamadı.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Veritabanı hatası: " . $e->getMessage() . "');</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
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
                <li><a class="active" href="contact.html">İletişim</a></li>
                <li id="lg-bag">
                    <a href="cart.php"><i class="fa fa-bag-shopping"></i></a>
                </li>
                <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
            </ul>
        </nav>
        <div id="mobile">
            <a href="cart.php"><i class="fa fa-bag-shopping"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </header>

    <section id="page-header" class="about-header">
        <h2>#Haydi_Konuşalım</h2>
        <p> Mesaj bırakın, sizden haber almayı seviyoruz! </p>
    </section>

    <section id="form-details" class="section-p1">
        <form action="" method="POST">
            <span> MESAJ BIRAKIN</span>
            <h2> Sizden haber almayı seviyoruz</h2>
            <input type="text" name="name" placeholder="ADINIZ" required>
            <input type="email" name="email" placeholder="E-MAIL" required>
            <input type="text" name="subject" placeholder="KONU" required>
            <textarea name="message" cols="30" rows="10" placeholder="Mesajınız" required></textarea>
            <button class="normal" type="submit">Gönder</button>
        </form>
        <div class="people">
            <div>
                <img src="img/people/1.png" alt="" />
                <p>
                    <span> John Doe</span>
                    Kıdemli Pazarlama Müdürü <br /> Telefon: 01045673344<br /> E-posta: john@gmail.com
                </p>
            </div>
            <div>
                <img src="img/people/2.png" alt="" />
                <p>
                    <span> William Sam</span>
                    Kıdemli Pazarlama Müdürü <br /> Telefon: 01099856445<br /> E-posta: william4@gmail.com
                </p>
            </div>
            <div>
                <img src="img/people/3.png" alt="" />
                <p>
                    <span> Emma George </span>
                    Kıdemli Pazarlama Müdürü <br /> Telefon: 01556782256<br /> E-posta: emmajoe@gmail.com
                </p>
            </div>
        </div>
    </section>
    <!-- ===================== -->
    <!--      Bülten          -->
    <!-- ===================== -->
    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Bültenlere Kaydolun</h4>
            <p>En son mağazamız hakkında E-posta güncellemeleri alın ve <span>özel teklifler.</span></p>
        </div>
        <div class="form">
            <input type="text" placeholder="E-posta adresiniz">
            <button class="normal">Kaydol</button>
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
            <a href="#">Bizimle İletişime Geçin</a>
        </div>
        <div class="col">
            <h4>Hesabım</h4>
            <a href="#">Giriş Yap</a>
            <a href="#">Sepetimi Görüntüle</a>
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
