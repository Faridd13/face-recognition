<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing...<br>";
echo "PHP Version: " . phpversion() . "<br>";

try {
    require __DIR__.'/vendor/autoload.php';
    echo "Autoload loaded<br>";
    
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "App loaded<br>";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel made<br>";
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    echo "Response handled<br>";
    
    $response->send();
    echo "Response sent<br>";
    
    $kernel->terminate($request, $response);
    echo "Terminated<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}
?>