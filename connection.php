<?php
$serverName = "DESKTOP-7R11JBR\\SQLEXPRESS"; // MSSQL Server adresi
$connectionOptions = [
    "Database" => "NewDatabase"
];



try {
    // PDO ile MSSQL bağlantısı
    $conn = new PDO("sqlsrv:server=$serverName;Database=" . $connectionOptions['Database'], NULL, NULL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Bağlantı başarılı!";
} catch (PDOException $pdoEx) {
    // PDO'ya özgü hatalar
    echo "PDOException Hata Mesajı: " . $pdoEx->getMessage() . "<br>";
    echo "Hata Kodu: " . $pdoEx->getCode() . "<br>";
    echo "Hata İzleme (Trace): " . $pdoEx->getTraceAsString() . "<br>";
} catch (Exception $ex) {
    // Genel hatalar
    echo "Genel Exception Hata Mesajı: " . $ex->getMessage() . "<br>";
    echo "Hata İzleme (Trace): " . $ex->getTraceAsString() . "<br>";
} finally {
    // Bağlantıyı temizleme (gerekirse)
    if (isset($conn)) {
        $conn = null;
    }
    echo "Bağlantı kapatıldı.";
}
?>
