<?php
require 'ai_matcher.php';

echo "<h2>🧪 AI Matching Test</h2><hr>";

// 测试用例 1: 完全不匹配
$cv1 = "I am a graphic designer using Photoshop";
$req1 = "PHP JavaScript MySQL React";
$result1 = analyzeMatch($cv1, $req1);
echo "❌ Case 1 (无匹配): " . $result1['score'] . "%<br>";
echo "Analysis: " . $result1['analysis'] . "<br><br>";

// 测试用例 2: 部分匹配
$cv2 = "I know PHP and JavaScript";
$req2 = "PHP JavaScript MySQL React";
$result2 = analyzeMatch($cv2, $req2);
echo "⚠️ Case 2 (部分): " . $result2['score'] . "%<br>";
echo "Analysis: " . $result2['analysis'] . "<br><br>";

// 测试用例 3: 完全匹配
$cv3 = "I am a full-stack developer with PHP, JavaScript, MySQL, React, Docker, AWS";
$req3 = "PHP JavaScript MySQL React Docker AWS";
$result3 = analyzeMatch($cv3, $req3);
echo "✅ Case 3 (完全): " . $result3['score'] . "%<br>";
echo "Analysis: " . $result3['analysis'] . "<br><br>";

// 测试用例 4: 测试中文
$cv4 = "我会 PHP, Python, JavaScript";
$req4 = "PHP JavaScript MySQL";
$result4 = analyzeMatch($cv4, $req4);
echo "🌍 Case 4 (中文): " . $result4['score'] . "%<br>";