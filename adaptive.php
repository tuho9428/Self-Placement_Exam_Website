<?php
// Sample Questions Array with Difficulty, Answer Choices, and Topics
$questions = [
    [
        'question' => 'What is a variable?',
        'answers' => ['A. I strongly understand', 'B. I understand', 'C. I understand a little', 'D. I don\'t understand'],
        'difficulty' => 'easy',
        'topic' => 'Variables'
    ],
    [
        'question' => 'Explain the concept of recursion.',
        'answers' => ['A. I strongly understand', 'B. I understand', 'C. I understand a little', 'D. I don\'t understand'],
        'difficulty' => 'medium',
        'topic' => 'Recursion'
    ],
    [
        'question' => 'Implement a sorting algorithm.',
        'answers' => ['A. I strongly understand', 'B. I understand', 'C. I understand a little', 'D. I don\'t understand'],
        'difficulty' => 'hard',
        'topic' => 'Sorting Algorithms'
    ]
];

// Function to evaluate student's performance and generate course recommendations
function generateFeedback($responses, $questions) {
    $topicScores = [];
    
    foreach ($responses as $key => $response) {
        $topic = $questions[$key]['topic'];
        $score = getResponseScore($response);
        
        if (!isset($topicScores[$topic])) {
            $topicScores[$topic] = 0;
        }
        
        $topicScores[$topic] += $score;
    }
    
    // Determine course recommendations based on topic scores
    $recommendations = [];
    
    foreach ($topicScores as $topic => $score) {
        if ($score >= 6) {
            $recommendations[$topic] = "You have a strong understanding of $topic.";
        } elseif ($score >= 4) {
            $recommendations[$topic] = "Consider reviewing $topic for better understanding.";
        } else {
            $recommendations[$topic] = "You may need to focus more on $topic.";
        }
    }
    
    return $recommendations;
}

// Function to assign scores based on user responses
function getResponseScore($response) {
    switch ($response) {
        case "I strongly understand":
            return 3;
        case "I understand":
            return 2;
        case "I understand a little":
            return 1;
        default:
            return 0; // No score for "I don't understand"
    }
}

// Example of student responses
$studentResponses = ["I strongly understand", "I understand", "I don't understand"];

// Generate feedback and course recommendations based on responses
$feedback = generateFeedback($studentResponses, $questions);

// Display course recommendations for each topic
foreach ($feedback as $topic => $recommendation) {
    echo "Topic: $topic - Recommendation: $recommendation<br>";
}
?>
