<?php
$lines = file('.env');
for ($i = 20; $i <= 25; $i++) {
    echo "Line " . ($i+1) . ": " . trim($lines[$i]) . "\n";
}
