<?php

function readPDF($filePath) {
    // إذا الملف موجود
    if (!file_exists($filePath)) {
        return false;
    }
        $handle = fopen($filePath, "rb");
    $content = fread($handle, 1000);
    fclose($handle);
    
    
    return basename($filePath);
}

function readDOCX($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $zip = new ZipArchive();
    if ($zip->open($filePath) === TRUE) {
        $content = $zip->getFromName('word/document.xml');
        $zip->close();
        
       
        $content = strip_tags($content);
        $content = html_entity_decode($content);
        
        return $content;
    }
    
    return false;
}
?>
