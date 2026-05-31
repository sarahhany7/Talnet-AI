<?php

require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

function extractTextFromPDF($filePath) {
    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();
        return $text ?: "";
    } catch (Exception $e) {
        return "";
    }
}

function extractSkillsFromCV($cvText, $additionalText = '') {
    $fullText = $cvText . " " . $additionalText;
    
    return extractSkills($fullText);
}

function uploadCV($file, $userId) {
    $allowed = ['pdf', 'doc', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return ['error' => 'Only PDF, DOC, DOCX files allowed'];
    }
    
    $uploadDir = 'uploads/cv/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $newName = $userId . '_cv_' . time() . '.' . $ext;
    $target = $uploadDir . $newName;
    
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return [
            'success' => true,
            'path' => $target,
            'name' => $newName
        ];
    }
    
    return ['error' => 'Upload failed'];
}
?>
