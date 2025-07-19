<?php

echo "=== LUXURY FURNITURE DIAGNOSTIC ===\n\n";

// Check current working directory
echo "1. Current Working Directory:\n";
echo "   " . getcwd() . "\n\n";

// Check if storage directory exists
echo "2. Storage Directory Check:\n";
$storagePath = __DIR__ . '/storage';
echo "   Storage path: " . $storagePath . "\n";
echo "   Exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "   Writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n\n";

// Check framework subdirectories
echo "3. Framework Subdirectories:\n";
$frameworkDirs = [
    'framework',
    'framework/cache',
    'framework/cache/data',
    'framework/sessions',
    'framework/views',
    'logs'
];

foreach ($frameworkDirs as $dir) {
    $fullPath = $storagePath . '/' . $dir;
    echo "   $dir: " . (is_dir($fullPath) ? 'EXISTS' : 'MISSING') . 
         " | " . (is_writable($fullPath) ? 'WRITABLE' : 'NOT WRITABLE') . "\n";
}
echo "\n";

// Check .env file
echo "4. Environment File:\n";
$envPath = __DIR__ . '/.env';
echo "   .env exists: " . (file_exists($envPath) ? 'YES' : 'NO') . "\n";
if (file_exists($envPath)) {
    echo "   .env readable: " . (is_readable($envPath) ? 'YES' : 'NO') . "\n";
}
echo "\n";

// Check Laravel key
echo "5. Laravel Application Key:\n";
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    if (preg_match('/APP_KEY=(.+)/', $envContent, $matches)) {
        $key = trim($matches[1]);
        echo "   Key set: " . ($key !== '' && $key !== 'base64:' ? 'YES' : 'NO') . "\n";
        echo "   Key value: " . substr($key, 0, 20) . "...\n";
    } else {
        echo "   Key not found in .env\n";
    }
}
echo "\n";

// Try to create a test file in sessions directory
echo "6. Write Test:\n";
$testPath = $storagePath . '/framework/sessions/test.txt';
echo "   Attempting to write to: $testPath\n";
try {
    if (is_dir(dirname($testPath))) {
        $result = file_put_contents($testPath, 'test');
        echo "   Write result: " . ($result !== false ? 'SUCCESS' : 'FAILED') . "\n";
        if ($result !== false) {
            unlink($testPath); // Clean up
            echo "   Test file cleaned up\n";
        }
    } else {
        echo "   Parent directory doesn't exist\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== DIAGNOSTIC COMPLETE ===\n"; 