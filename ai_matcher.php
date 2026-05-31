<?php

function extractSkills($text) {
    
    $allSkills = [
        'html', 'css', 'javascript', 'react', 'vue', 'angular', 'jquery', 'bootstrap', 'tailwind', 'sass', 'typescript', 'next.js', 'nuxt',
        
        'php', 'python', 'java', 'c++', 'c#', 'ruby', 'go', 'rust', 'node.js', 'express', 'laravel', 'django', 'flask', 'spring', 'rails', 'asp.net', 'codeigniter', 'symfony',
        
        'mysql', 'mongodb', 'postgresql', 'redis', 'firebase', 'oracle', 'sql server', 'sqlite', 'elasticsearch',
        
        'git', 'docker', 'kubernetes', 'aws', 'azure', 'gcp', 'linux', 'jenkins', 'ci/cd', 'jira', 'github', 'gitlab',
        
        'rest api', 'graphql', 'agile', 'scrum', 'figma', 'photoshop', 'seo', 'wordpress', 'flutter', 'react native'
    ];
    
    $found = [];
    $text = strtolower($text);
    
    foreach ($allSkills as $skill) {
        if (strpos($text, $skill) !== false) {
            $found[] = $skill;
        }
    }
    
    return array_unique($found);
}


function getSkillWeights() {
    return [
        'php' => 5, 'python' => 5, 'javascript' => 4, 'java' => 4, 'c++' => 3, 'c#' => 3, 'ruby' => 3, 'go' => 4, 'rust' => 3, 'typescript' => 4,
        
        'laravel' => 5, 'django' => 5, 'react' => 5, 'vue' => 4, 'angular' => 4, 'node.js' => 4, 'express' => 4, 'spring' => 4, 'rails' => 4, 'codeigniter' => 3, 'symfony' => 4, 'flask' => 3, 'next.js' => 4,
        
        'mysql' => 4, 'mongodb' => 4, 'postgresql' => 4, 'redis' => 3, 'firebase' => 3, 'elasticsearch' => 3,
        
        'docker' => 5, 'kubernetes' => 5, 'aws' => 5, 'azure' => 4, 'gcp' => 4, 'linux' => 3, 'jenkins' => 3, 'ci/cd' => 4,
        
        'html' => 2, 'css' => 2, 'jquery' => 2, 'bootstrap' => 2, 'tailwind' => 2, 'sass' => 2,
        
        'git' => 3, 'github' => 2, 'gitlab' => 2, 'jira' => 2, 'figma' => 2, 'rest api' => 4, 'graphql' => 4
    ];
}


function analyzeMatch($cvSkills, $jobRequirements) {
    
    $userSkills = extractSkills($cvSkills);
    $requiredSkills = extractSkills($jobRequirements);
    
    if (empty($requiredSkills)) {
        return [
            'score' => rand(40, 60),
            'analysis' => "No specific skills required for this job.",
            'matched' => [],
            'missing' => [],
            'strengths' => [],
            'gaps' => []
        ];
    }
    
    $matchedSkills = array_intersect($userSkills, $requiredSkills);
    $missingSkills = array_diff($requiredSkills, $userSkills);
    
    $simpleScore = round((count($matchedSkills) / count($requiredSkills)) * 100);
    
    $weights = getSkillWeights();
    $matchedWeight = 0;
    $totalWeight = 0;
    
    foreach ($requiredSkills as $skill) {
        $totalWeight += ($weights[$skill] ?? 1); // default weight = 1
    }
    
    foreach ($matchedSkills as $skill) {
        $matchedWeight += ($weights[$skill] ?? 1);
    }
    
    $weightedScore = ($totalWeight > 0) ? round(($matchedWeight / $totalWeight) * 100) : 0;
    
    $finalScore = $weightedScore ?: $simpleScore;
    
    $matchedList = implode(", ", array_map('ucfirst', $matchedSkills));
    $missingList = implode(", ", array_map('ucfirst', $missingSkills));
    
    $analysis = " Match Score: " . $finalScore . "%\n";
    $analysis .= " Matched Skills (" . count($matchedSkills) . "): " . ($matchedList ?: "None") . "\n";
    $analysis .= " Missing Skills (" . count($missingSkills) . "): " . ($missingList ?: "None") . "\n";
    $analysis .= " Weighted Analysis Applied";
    
    return [
        'score' => $finalScore,
        'analysis' => $analysis,
        'matched' => $matchedSkills,
        'missing' => $missingSkills,
        'strengths' => $matchedSkills,
        'gaps' => $missingSkills,
        'simple_score' => $simpleScore,
        'weighted_score' => $weightedScore
    ];
}
?>
