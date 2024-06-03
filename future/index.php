<?php
// Function to display a table of numbers
function displayTable($sets, $title) {
    echo '<h2>' . $title . '</h2>';
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    foreach ($sets as $set) {
        echo '<tr>';
        foreach ($set as $number) {
            echo '<td>' . htmlspecialchars($number) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

// Function to analyze and predict the next sets of numbers
function analyzeAndPredict($sets) {
    $frequency = array_fill(1, 48, 0);

    // Calculate frequency of each number in the 10 sets
    foreach ($sets as $set) {
        foreach ($set as $number) {
            $frequency[$number]++;
        }
    }

    // Generate 3 new sets based on frequency analysis
    $newSets = [];
    for ($i = 0; $i < 3; $i++) {
        arsort($frequency);
        $mostFrequentNumbers = array_keys(array_slice($frequency, 0, 35, true));
        shuffle($mostFrequentNumbers);
        $newSets[] = array_slice($mostFrequentNumbers, 0, 35);
    }

    return $newSets;
}

// Initialize sets array
$sets = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve entered numbers
    for ($i = 0; $i < 10; $i++) {
        $set = [];
        for ($j = 0; $j < 35; $j++) {
            $set[] = $_POST['set' . $i . 'num' . $j];
        }
        $sets[] = $set;
    }

    // Analyze the sets and generate the next 3 sets
    $newSets = analyzeAndPredict($sets);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Prediction</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>
    <main>
        <h1>Number Prediction</h1>
        <div id="form-section">
            <?php
            // Display the form if it's not a POST request
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                echo '<h2>Enter 35 numbers in 10 rows</h2>';
                echo '<form method="POST">';
                for ($i = 0; $i < 10; $i++) {
                    echo '<div>Set ' . ($i + 1) . ':</div>';
                    for ($j = 0; $j < 35; $j++) {
                        echo '<input type="number" name="set' . $i . 'num' . $j . '" min="1" max="48" required> ';
                        if (($j + 1) % 7 == 0) echo '<br>';
                    }
                    echo '<br><br>';
                }
                echo '<input type="submit" value="Enter">';
                echo '</form>';
            }
            ?>
        </div>
        <div id="result-section">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Display the entered sets
                displayTable($sets, "Entered Sets of Numbers");

                // Display the 3 predicted sets of numbers
                displayTable($newSets, "Predicted Sets of Numbers");
            }
            ?>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
