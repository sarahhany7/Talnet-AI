<?php

/**
 * دالة لتحليل التوافق
 * @param string $cvSkills - مهارات المتقدم (من الـ profile)
 * @param string $jobRequirements - متطلبات الوظيفة
 * @param string $jobDescription - وصف الوظيفة
 * @return array ['score' => int, 'analysis' => string, 'strengths' => array, 'gaps' => array]
 */
function analyzeMatch($cvSkills, $jobRequirements, $jobDescription = '') {
    
    
    $allSkills = [
        
        'php', 'python', 'javascript', 'java', 'c++', 'c#', 'ruby', 'go', 'rust', 'typescript', 'swift', 'kotlin',
        

        'html', 'css', 'react', 'vue', 'angular', 'jquery', 'bootstrap', 'tailwind', 'sass', 'less',
        
        
        'nodejs', 'express', 'laravel', 'django', 'flask', 'spring', 'rails', 'asp.net',
        
        
        'mysql', 'mongodb', 'postgresql', 'redis', 'firebase', 'oracle', 'sql server', 'sqlite',
        
        
        'git', 'docker', 'kubernetes', 'aws', 'azure', 'gcp', 'linux', 'jenkins', 'ci/cd',
        
        
        'rest api', 'graphql', 'agile', 'scrum', 'jira', 'figma', 'photoshop', 'seo'
    ];
    
    
    $cvSkills = strtolower($cvSkills . " " . $jobDescription);
    $jobRequirements = strtolower($jobRequirements);
    
    $matchedSkills = [];
    $missingSkills = [];
    

    foreach ($allSkills as $skill) {
        $skillLower = strtolower($skill);
        
        
        if (strpos($jobRequirements, $skillLower) !== false || 
            strpos($jobDescription, $skillLower) !== false) {
            
            
            if (strpos($cvSkills, $skillLower) !== false) {
                $matchedSkills[] = ucfirst($skill);
            } else {
                $missingSkills[] = ucfirst($skill);
            }
        }
    }
    
    
    $totalRequired = count($matchedSkills) + count($missingSkills);
    
    if ($totalRequired == 0) {
        $score = rand(50, 70);
        $analysis = "No specific requirements found. General profile matched.";
    } else {
        $score = round((count($matchedSkills) / $totalRequired) * 100);
        
        
        $strengthsList = implode(", ", $matchedSkills);
        $gapsList = implode(", ", $missingSkills);
        
        $analysis = "✅ Strengths: " . ($strengthsList ?: "None specific") . "\n";
        $analysis .= "❌ Gaps: " . ($gapsList ?: "None") . "\n";
        $analysis .= "📊 Match: " . $score . "%";
    }
    
    return [
        'score' => $score,
        'analysis' => $analysis,
        'strengths' => $matchedSkills,
        'gaps' => $missingSkills
    ];
}
?>