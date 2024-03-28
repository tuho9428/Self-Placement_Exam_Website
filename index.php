<?php
include 'questions.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Questionnaire</title>
</head>
<body>
<h1>Questionnaire</h1>
<h1 id="questionTitle">Questionnaire</h1>
<h2>Topic <span id="questionTopic">Topic</span></h2>
<h3 id="totalScore">Total Score: <span id="scoreValue">0</span></h3>
<br />
    
    <div id="questionContainer">
        <!-- Questions and answer choices will be dynamically updated here -->
    </div>

    <form id="answerForm">
        <input type="radio" name="answer" value="I strongly understand"> I strongly understand<br>
        <input type="radio" name="answer" value="I understand"> I understand<br>
        <input type="radio" name="answer" value="I understand a little"> I understand a little<br>
        <input type="radio" name="answer" value="I don't understand"> I don't understand<br>

        <br />
        <button type="submit">Submit</button>
    </form>

    <script src="script.js"></script>

    <script>
const questions = <?php echo json_encode($questions); ?>; // PHP array of questions

let difficultyLevels = ['easy', 'medium', 'hard', 'very hard']; // Array of difficulty levels
let currentDifficultyIndex = 0; // Initialize current difficulty index

let userResponses = []; // Array to store user responses for each difficulty level
let topicScores = {}; // Object to store scores for each topic
let totalScore = 0; // Initialize total score
let submitCount = 0; // Initialize submitCount


function calculateScore(response, difficulty) {
    let points = 0;
    
    switch (response) {
        case "I strongly understand":
            points = 3;
            break;
        case "I understand":
            points = 2;
            break;
        case "I understand a little":
            points = 1;
            break;
        case "I don't understand":
            points = 0;
            break;
        default:
            points = 0; // No points for unknown response
    }
    
    // Adjust points based on difficulty level
    switch (difficulty) {
        case 'easy':
            return points + 0;
        case 'medium':
            return points + 1;
        case 'hard':
            return points + 2;
        case 'very hard':
            return points + 3;
        default:
            return points; // Default to original points for unknown difficulty level
    }
}

function displayQuestionByDifficulty(difficultyLevel) {
    // Filter questions based on the specified difficulty level
    const filteredQuestions = questions.filter(question => question.difficulty === difficultyLevel);
    
    // Randomly select a question from the filtered list
    const randomQuestion = filteredQuestions[Math.floor(Math.random() * filteredQuestions.length)];
    
    // Display the selected question
    document.getElementById('questionTitle').textContent = randomQuestion.question;
    document.getElementById('questionTopic').textContent = randomQuestion.topic;

}

displayQuestionByDifficulty(difficultyLevels[currentDifficultyIndex]);

document.getElementById('answerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    
    const selectedAnswer = document.querySelector('input[name="answer"]:checked').value;

    // Clear old selection
    document.getElementById('answerForm').reset();
    
    // Update user response array for the current difficulty level
    userResponses.push(selectedAnswer);

    // Calculate score and update total score
    let questionScore = calculateScore(selectedAnswer, difficultyLevels[currentDifficultyIndex]);
    totalScore += questionScore;
    document.getElementById('scoreValue').textContent = totalScore;

    // Calculate topic score
    let currentTopic = document.getElementById('questionTopic').textContent;
    topicScores[currentTopic] = (topicScores[currentTopic] || 0) + questionScore;


    submitCount++;


    
    // Check user responses to adjust difficulty level
    if (selectedAnswer === "I strongly understand") {
        currentDifficultyIndex = Math.min(currentDifficultyIndex + 1, difficultyLevels.length - 1); // Increase difficulty
    } else if (selectedAnswer === "I don't understand") {
        currentDifficultyIndex = Math.max(currentDifficultyIndex - 1, 0); // Decrease difficulty
    } else if (userResponses.filter(response => response === "I understand").length > 1) {
        currentDifficultyIndex = Math.min(currentDifficultyIndex + 1, difficultyLevels.length - 1); // Increase difficulty
        userResponses = []; // Reset user responses for this difficulty level
    } else if (userResponses.filter(response => response === "I understand a little").length > 1) {
        currentDifficultyIndex = Math.max(currentDifficultyIndex - 1, 0); // Decrease difficulty
        userResponses = []; // Reset user responses for this difficulty level
    }
    
    // Display the next question based on the adjusted difficulty level
    displayQuestionByDifficulty(difficultyLevels[currentDifficultyIndex]);

    // Check if user has answered 10 questions
    if (submitCount === 10) {

        let feedback = "";
        if (totalScore <= 20) {
            feedback = "You might need to review the topics for better understanding.";
        } else if (totalScore <= 30) {
            feedback = "Your understanding is decent, but there is room for improvement.";
        } else if (totalScore <= 40) {
            feedback = "Great job! You have a good grasp of the topics.";
        } else {
            feedback = "Excellent work! You have mastered the topics.";
        }

        // Display total score, topic scores, and feedback on the webpage
        document.getElementById('totalScore').innerHTML = `Total Score: <span id="scoreValue">${totalScore}</span>`;
        document.getElementById('questionContainer').innerHTML = `Topic Scores: ${JSON.stringify(topicScores)} <br> Feedback: ${feedback}`;

        alert(feedback);
        // You can customize this alert message or display it on the webpage as needed.

        // Additionally, you can track performance by topic and provide specific recommendations based on the user's performance in different areas.

        // Stop further questions or redirect to a different page if needed
        return;
    }


});

    </script>
</body>
</html>

