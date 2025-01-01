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

try {
    // `users` tablosundan en yüksek kullanıcı id'sini al
    $userSql = "SELECT TOP 1 user_id FROM users ORDER BY user_id DESC";
    $userStmt = $conn->query($userSql);
    $latestUser = $userStmt->fetch(PDO::FETCH_ASSOC);
    $userId = $latestUser['user_id'] ?? null; // Eğer sonuç yoksa null döner

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $productName = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $size = $_POST['size'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;

        if ($userId !== null) {
            // Veritabanı işlemlerini başlat
            $conn->beginTransaction();

            try {
                // `Products` tablosuna ekleme
                $productSql = "INSERT INTO Products (product_name, price, size) 
                               OUTPUT INSERTED.product_id 
                               VALUES (:product_name, :price, :size)";
                $productStmt = $conn->prepare($productSql);
                $productStmt->bindParam(':product_name', $productName);
                $productStmt->bindParam(':price', $price);
                $productStmt->bindParam(':size', $size);
                $productStmt->execute();

                // Eklenen ürünün ID'sini al
                $productId = $productStmt->fetch(PDO::FETCH_ASSOC)['product_id'];

                // `cart` tablosuna ekleme
                $cartSql = "INSERT INTO Cart (user_id, product_id, quantity, added_date) 
                            VALUES (:user_id, :product_id, :quantity, GETDATE())";
                $cartStmt = $conn->prepare($cartSql);
                $cartStmt->bindParam(':user_id', $userId);
                $cartStmt->bindParam(':product_id', $productId);
                $cartStmt->bindParam(':quantity', $quantity);
                $cartStmt->execute();

                // İşlemleri onayla
                $conn->commit();

                echo "<script>alert('Ürün başarıyla sepete ve ürün tablosuna eklendi.');</script>";
            } catch (Exception $e) {
                // Hata durumunda işlemleri geri al
                $conn->rollBack();
                echo "<script>alert('Veritabanı hatası: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Kullanıcı bilgisi bulunamadı.');</script>";
        }
    }
} catch (Exception $e) {
    echo "<script>alert('Kullanıcı bilgisi alınamadı: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<!-- =================================================================================== -->
<!--        Ana Sayfa   -->
<!-- =================================================================================== -->

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>E-Ticaret</title>
  <!-- Font Awesome Kütüphanesi -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- <link rel="stylesheet" href="css/all.min.css" /> -->
  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <header>
    <a href="index.html" class="logo"><img src="img/logo.svg" alt="" class="logo" /></a>
    <nav>
      <ul id="navbar">
        <li><a href="index.html">Ana Sayfa</a></li>
        <li><a href="shop.html" class="active">Mağaza</a></li>
        <li><a href="blog.html">Blog</a></li>
        <li><a href="about.html">Hakkımızda</a></li>
        <li><a href="contact.php">İletişim</a></li>
        <li id="lg-bag">
          <a href="cart.html"><i class="fa fa-bag-shopping"></i></a>
        </li>
        <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
      </ul>
    </nav>
    <div id="mobile">
      <a href="cart.html"><i class="fa fa-bag-shopping"></i></a>
      <i id="bar" class="fas fa-outdent"></i>
    </div>
  </header>

  <!-- ===================== -->
  <!--   Ürün Detayları Bölümü    -->
  <!-- ===================== -->
  <section id="prodetails" class="section-p1">
    <div class="single-pro-image">
      <img src="img/products/f1.jpg" width="100%" id="MainImg" alt="" />

      <div class="small-img-group">
        <div class="small-img-col">
          <img src="img/products/f1.jpg" width="100%" class="small-img" alt="" />
        </div>
        <div class="small-img-col">
          <img src="img/products/f2.jpg" width="100%" class="small-img" alt="" />
        </div>
        <div class="small-img-col">
          <img src="img/products/f3.jpg" width="100%" class="small-img" alt="" />
        </div>
        <div class="small-img-col">
          <img src="img/products/f4.jpg" width="100%" class="small-img" alt="" />
        </div>
      </div>
    </div>

    <div class="single-pro-details">
      <h6>Ana Sayfa / Gömlek</h6>
      <h4>Erkek Moda Gömlek</h4>
      <h2>400 TL</h2>
      <form method="POST" action="">
        <select name="size">
          <option>Beden Seçin</option>
          <option value="Küçük">Küçük</option>
          <option value="Orta">Orta</option>
          <option value="Büyük">Büyük</option>
          <option value="XL">XL</option>
          <option value="XXL">XXL</option>
        </select>
        <input type="number" name="quantity" value="1" />
        <input type="hidden" name="product_name" value="Erkek Moda Gömlek" />
        <input type="hidden" name="price" value="400" />
        <button class="normal" type="submit" name="add_to_cart">Sepete Ekle</button>
      </form>
      <h4>Ürün Detayları</h4>
      <span>Gildan Ultra Cotton Gömlek, %100 pamuklu, 6.0 oz. kare yarda kumaşından üretilmiştir. Bu klasik kesim, önceden küçültülmüş jarse örgüsü ile her giyişte eşsiz bir rahatlık sunar. Taped boyun ve omuz detayları, dikişsiz çift iğne yakası ve çeşitli renk seçenekleriyle bu ürün, dikkat çekici bir görünüm sunar.</span>
    </div>
  </section>



  <section id="product1" class="section-p1">
    <h2>Öne Çıkan Ürünler</h2>
    <p class="heading">Yaz Koleksiyonu Yeni Modern Tasarım</p>
    <div class="pro-container">
      <div class="pro">
        <img src="img/products/n1.jpg" alt="" />
        <div class="des">
          <span>H&M</span>
          <h5>Normal Kesim Gömlek</h5>
          <div class="star">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
            <i class="fa-regular fa-star"></i>
          </div>
          <h4>279 TL</h4>
          <a href="#"><i class="fa-solid fa-cart-shopping cart"></i></a>
        </div>
      </div>
      <div class="pro">
        <img src="img/products/n2.jpg" alt="" />
        <div class="des">
          <span>LC WAIKIKI</span>
          <h5>Klasik Kollu Gömlek</h5>
          <div class="star">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
          </div>
          <h4>230 TL</h4>
          <a href="#"><i class="fa-solid fa-cart-shopping cart"></i></a>
        </div>
      </div>
      <div class="pro">
        <img src="img/products/n3.jpg" alt="" />
        <div class="des">
          <span>H&M</span>
          <h5>Normal Kesim Pamuklu Gömlek</h5>
          <div class="star">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
          <h4>350 TL</h4>
          <a href="#"><i class="fa-solid fa-cart-shopping cart"></i></a>
        </div>
      </div>
      <div class="pro">
        <img src="img/products/n4.jpg" alt="" />
        <div class="des">
          <span>POLO</span>
          <h5>Polo Tişört</h5>
          <div class="star">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
            <i class="fa-regular fa-star"></i>
          </div>
          <h4>285 TL</h4>
          <a href="#"><i class="fa-solid fa-cart-shopping cart"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== -->
  <!--   Bülten    -->
  <!-- ===================== -->
  <section id="newsletter" class="section-p1 section-m1">
    <div class="newstext">
      <h4>Bültenlerimize Kaydolun</h4>
      <p>
        En son mağazamız ve
        <span>özel tekliflerle ilgili</span> E-posta güncellemeleri alın.
      </p>
    </div>
    <div class="form">
      <input type="text" placeholder="E-posta adresiniz" />
      <button class="normal">Kaydol</button>
    </div>
  </section>

 <!-- ===================== -->
<!-----       Footer -------->
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
    <a href="#">Teslimat Bilgisi</a>
    <a href="#">Gizlilik Politikası</a>
    <a href="#">Şartlar ve Koşullar</a>
    <a href="#">Bize Ulaşın</a>
  </div>
  <div class="col">
    <h4>Hesabım</h4>
    <a href="#">Giriş Yap</a>
    <a href="#">Sepetimi Gör</a>
    <a href="#">Dilek Listem</a>
    <a href="#">Siparişimi Takip Et</a>
    <a href="#">Yardım</a>
  </div>
  <div class="col install">
    <h4>Uygulama Yükle</h4>
    <p>App Store veya Google Play'den</p>
    <div class="row">
      <img src="img/pay/app.jpg" alt="" />
      <img src="img/pay/play.jpg" alt="" />
    </div>
    <p>Güvenli Ödeme Yöntemleri</p>
    <img src="img/pay/pay.png" alt="" />
  </div>
  <div class="copyright">
    <p>&copy; 2022, Web Geliştirme Projesi - Grup18</p>
  </div>
</footer>

<!-- ===================== -->
<!-----     Resim Değiştirme -------->
<!-- ===================== -->

<script>
  var MainImg = document.getElementById("MainImg");
  var smallimg = document.getElementsByClassName("small-img");

  smallimg[0].onclick = function () {
    MainImg.src = smallimg[0].src;
  };
  smallimg[1].onclick = function () {
    MainImg.src = smallimg[1].src;
  };

  smallimg[2].onclick = function () {
    MainImg.src = smallimg[2].src;
  };

  smallimg[3].onclick = function () {
    MainImg.src = smallimg[3].src;
  };
</script>
<!-- ============================================================================================================ -->
<script src="script.js"></script>
</body>
</html>
