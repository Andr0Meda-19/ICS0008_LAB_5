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
    
    if (isset($_POST["download"]) && file_exists($csvFile)) {
        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="last_registration.csv"');
        
        $lastLine = [];
        $downloadFile = fopen($csvFile, "r");
        while (($buffer = fgetcsv($downloadFile, 1000 , ';', '"', '\\')) !== FALSE) {
            $lastLine = $buffer;
        }
        foreach($lastLine as $index => $word) {
            if ($index < count($lastLine) - 1) echo $word . ";";
            else echo $word;
        }
        fclose($downloadFile);
        exit();
    }

    // if (isset($_POST["downloadAll"]) && file_exists($csvFile)) {
    //     header("Content-Type: text/csv");
    //     header('Content-Disposition: attachment; filename="registrations.csv"');
    //     readfile($csvFile);
    //     exit();
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="antimo">
    <title>LAB 5 - PHP Info</title>
    <link rel="stylesheet" href="./styles/style.css">
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
    <!-- <form action="download.php" method="POST">
        <button type="submit" id="downloadLast" name="downloadLast">Download last registration</button>
    </form>
    <br> -->
    <form action="download.php" method="POST">
        <input type="submit" id="download" name="download" value="Download registration data">
    </form>
</body>
</html>