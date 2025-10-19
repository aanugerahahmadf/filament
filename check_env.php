<?php

// Load the .env file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            if (strpos($key, 'DB_') === 0) {
                echo "$key=$value\n";
            }
        }
    }
} else {
    echo ".env file not found\n";
}
