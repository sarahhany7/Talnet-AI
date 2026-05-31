<?php
// ai_matcher.php - المستوى الأول والثاني

/**
 * Smart AI Matching Engine
 * مع دعم الـ Weighted Skills
 */

/**
 * استخراج المهارات من النص
 */
function extractSkills($text) {
    // قائمة شاملة بالمهارات
    $allSkills = [
        // Frontend
        'html', 'css', 'javascript', 'react', 'vue', 'angular', 'jquery', 'bootstrap', 'tailwind', 'sass', 'typescript', 'next.js', 'nuxt',
        
        // Backend  
        'php', 'python', 'java', 'c++', 'c#', 'ruby', 'go', 'rust', 'node.js', 'express', 'laravel', 'django', 'flask', 'spring', 'rails', 'asp.net', 'codeigniter', 'symfony',
        
        // Database
        'mysql', 'mongodb', 'postgresql', 'redis', 'firebase', 'oracle', 'sql server', 'sqlite', 'elasticsearch',
        
        // DevOps & Tools
        'git', 'docker', 'kubernetes', 'aws', 'azure', 'gcp', 'linux', 'jenkins', 'ci/cd', 'jira', 'github', 'gitlab',
        
        // Other
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

/**
 *_weights للمهارات (مستوى أعلى)
 */
function getSkillWeights() {
    return [
        // Languages - weights
        'php' => 5, 'python' => 5, 'javascript' => 4, 'java' => 4, 'c++' => 3, 'c#' => 3, 'ruby' => 3, 'go' => 4, 'rust' => 3, 'typescript' => 4,
        
        // Frameworks
        'laravel' => 5, 'django' => 5, 'react' => 5, 'vue' => 4, 'angular' => 4, 'node.js' => 4, 'express' => 4, 'spring' => 4, 'rails' => 4, 'codeigniter' => 3, 'symfony' => 4, 'flask' => 3, 'next.js' => 4,
        
        // Database
        'mysql' => 4, 'mongodb' => 4, 'postgresql' => 4, 'redis' => 3, 'firebase' => 3, 'elasticsearch' => 3,
        
        // DevOps - higher weights
        'docker' => 5, 'kubernetes' => 5, 'aws' => 5, 'azure' => 4, 'gcp' => 4, 'linux' => 3, 'jenkins' => 3, 'ci/cd' => 4,
        
        // Basics - lower weights
        'html' => 2, 'css' => 2, 'jquery' => 2, 'bootstrap' => 2, 'tailwind' => 2, 'sass' => 2,
        
        // Tools
        'git' => 3, 'github' => 2, 'gitlab' => 2, 'jira' => 2, 'figma' => 2, 'rest api' => 4, 'graphql' => 4
    ];
}

/**
 * دالة الـ Matching الحقيقية (المستوى الأول + الثاني)
 * @param string $cvSkills - مهارات المتقدم (من الـ profile أو CV)
 * @param string $jobRequirements - متطلبات الوظيفة
 * @return array
 */
function analyzeMatch($cvSkills, $jobRequirements) {
    
    // استخراج المهارات من كلا الجانبين
    $userSkills = extractSkills($cvSkills);
    $requiredSkills = extractSkills($jobRequirements);
    
    // إذا ما فيهش مهارات مطلوبة
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
    
    // найти المهارات المتطابقة
    $matchedSkills = array_intersect($userSkills, $requiredSkills);
    $missingSkills = array_diff($requiredSkills, $userSkills);
    
    // طريقة حساب النسبة: المستوى الأول (البسيط)
    $simpleScore = round((count($matchedSkills) / count($requiredSkills)) * 100);
    
    // طريقة حساب النسبة: المستوى الثاني (الموزون)
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
    
    // استخدم الطريقة الثانية (الموزون)
    $finalScore = $weightedScore ?: $simpleScore;
    
    // إنشاء التحليل
    $matchedList = implode(", ", array_map('ucfirst', $matchedSkills));
    $missingList = implode(", ", array_map('ucfirst', $missingSkills));
    
    $analysis = "📊 Match Score: " . $finalScore . "%\n";
    $analysis .= "✅ Matched Skills (" . count($matchedSkills) . "): " . ($matchedList ?: "None") . "\n";
    $analysis .= "❌ Missing Skills (" . count($missingSkills) . "): " . ($missingList ?: "None") . "\n";
    $analysis .= "🧠 Weighted Analysis Applied";
    
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
