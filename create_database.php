<?php

try {
    // Connect to our specific database
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=atcs_kilang_pertamina_internasional_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables in atcs_kilang_pertamina_internasional_db:\n";
    foreach($tables as $table) {
        echo "- " . $table . "\n";
    }

    echo "\nTotal tables: " . count($tables) . "\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
