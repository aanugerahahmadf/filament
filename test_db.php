<?php

try {
    // Connect to our specific database
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=atcs_kilang_pertamina_internasional_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if migrations table exists and show its contents
    $stmt = $pdo->query("SELECT COUNT(*) FROM migrations");
    $count = $stmt->fetchColumn();

    echo "Migrations table exists with $count records\n";

    // Show some recent migrations
    $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY batch DESC, migration DESC LIMIT 5");
    $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Recent migrations:\n";
    foreach($migrations as $migration) {
        echo "- " . $migration . "\n";
    }

    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "\nAll tables in database:\n";
    foreach($tables as $table) {
        echo "- " . $table . "\n";
    }

    echo "\nTotal tables: " . count($tables) . "\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
