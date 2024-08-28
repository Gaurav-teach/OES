<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $duration = $_POST['duration'];
    $negative_marking_enabled = isset($_POST['negative_marking_enabled']) ? 1 : 0;
    $negative_marking_criteria = $negative_marking_enabled ? $_POST['negative_marking_criteria'] : NULL;

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO exams (name, duration, negative_marking_enabled, negative_marking_criteria) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $name, $duration, $negative_marking_enabled, $negative_marking_criteria);

    // Execute the query
    if ($stmt->execute()) {
        echo "New exam created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
</head>
<body>

<h2>Create a New Exam</h2>

<form method="post" action="">
    <label for="name">Exam Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="duration">Duration (minutes):</label><br>
    <input type="number" id="duration" name="duration" required><br><br>

    <label for="negative_marking_enabled">Enable Negative Marking:</label>
    <input type="checkbox" id="negative_marking_enabled" name="negative_marking_enabled" value="1"><br><br>

    <label for="negative_marking_criteria">Negative Marking Criteria (if enabled):</label><br>
    <input type="number" step="0.01" id="negative_marking_criteria" name="negative_marking_criteria" disabled><br><br>

    <input type="submit" value="Create Exam">
</form>

<script>
    // Enable or disable negative marking criteria input based on checkbox
    document.getElementById('negative_marking_enabled').addEventListener('change', function () {
        document.getElementById('negative_marking_criteria').disabled = !this.checked;
    });
</script>

</body>
</html>