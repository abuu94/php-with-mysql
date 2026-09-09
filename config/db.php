
<?php
try {
    $dsn = "pgsql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_NAME");
    $conn = new PDO($dsn, getenv("DB_USER"), getenv("DB_PASS"), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>




