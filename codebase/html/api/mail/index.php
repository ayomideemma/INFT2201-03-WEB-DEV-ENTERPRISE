<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../../../autoload.php';

use Application\Mail;
use Application\Database;
use Application\Page;
use Application\Verifier;

$database = new Database('prod');
$page = new Page();
$mail = new Mail($database->getDb());
$verifier = new Verifier();

// 1. Verify the JWT Token
if (!isset($_SERVER['HTTP_AUTHORIZATION']) || !$verifier->decode($_SERVER['HTTP_AUTHORIZATION'])) {
    
    // 👉 FIX APPLIED HERE: Standard PHP 401 response instead of $page->unauthorized()
    header('Content-Type: application/json');
    http_response_code(401); 
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

// --------------------------------------------------
// POST: Creating Mail
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (array_key_exists('name', $data) && array_key_exists('message', $data)) {
        $targetUserId = $verifier->userId; 
        
        if ($verifier->role === 'admin' && isset($data['userId'])) {
            $targetUserId = $data['userId'];
        }
        
        $id = $mail->createMail($data['name'], $data['message'], $targetUserId);
        $page->item(array("id" => $id));
    } else {
        $page->badRequest();
    }

// --------------------------------------------------
// GET: Retrieving Mail
// --------------------------------------------------
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if ($verifier->role === 'admin') {
        $page->item($mail->listMail()); 
    } else {
        $page->item($mail->listMail($verifier->userId)); 
    }

} else {
    $page->badRequest();
}