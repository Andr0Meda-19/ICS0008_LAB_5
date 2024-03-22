<?php
    $csvFile = "data.csv";
    $registrations = 0;

    try {
        if (file_exists($csvFile)) {
            $file = fopen($csvFile, "r");
            $registrations = count(file($csvFile));
            fclose($file);
        } else {
            throw new Exception("File not found!");
        } 
    } catch (Exception $e) {
        echo "<p>Caught exception: " . $e->getMessage() . PHP_EOL . "</p>";
    }
    
    if (isset($_POST["download"])) {
        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="registrations.csv"');
        readfile($csvFile);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="antimo">
    <title>LAB 5 - PHP Info</title>
</head>
<body>
    <header>
        <nav>
            <a href="./index.php">Index</a>
            <a href="./download.php">Download</a>
        </nav>
    </header>
    <div id="reservedText">
        <h1>Registration stats</h1>
        <p>Number of registrations: <?php echo $registrations; ?></p>
    </div>
    <form action="download.php" method="POST">
        <button type="submit" id="download" name="download">Download registration data</button>
    </form>
</body>
</html>