<?php

header('Content-Type: application/json');

require 'config.php';
require 'ai_matcher.php';

$response = ['success' => false, 'data' => null, 'error' => null];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $cvSkills = $input['cv_skills'] ?? '';
    $jobRequirements = $input['job_requirements'] ?? '';
    
    if ($cvSkills && $jobRequirements) {
        $result = analyzeMatch($cvSkills, $jobRequirements);
        $response = [
            'success' => true,
            'data' => $result
        ];
    } else {
        $response['error'] = 'Missing required fields';
    }
}

echo json_encode($response);
?>
