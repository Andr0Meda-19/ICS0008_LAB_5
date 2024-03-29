<?php
    $csvFile = "data.csv";
    $registrations = 0;

    try {
        if (file_exists($csvFile)) {
            $handle = fopen($csvFile, "r");
            $file = file($csvFile, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
            $registrations = count($file);
            fclose($handle);
        } else {
            throw new Exception("File not found!");
        } 
    } catch (Exception $e) {
        $noFile = "<p>Caught exception: " . $e->getMessage() . PHP_EOL . "</p>";
    }
    
    // if (isset($_POST["download"]) && file_exists($csvFile)) {
    //     header("Content-Type: text/csv");
    //     header('Content-Disposition: attachment; filename="last_registration.csv"');
        
    //     $lastLine = [];
    //     $downloadFile = fopen($csvFile, "r");
    //     while (($buffer = fgetcsv($downloadFile, 1000 , ';', '"', '\\')) !== FALSE) {
    //         $lastLine = $buffer;
    //     }
    //     foreach($lastLine as $index => $word) {
    //         if ($index < count($lastLine) - 1) echo $word . ";";
    //         else echo $word;
    //     }
    //     fclose($downloadFile);
    //     exit();
    // }

    if (isset($_POST["download"]) && file_exists($csvFile)) {
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
<?php
        if (file_exists($csvFile)) {
?>
        <p>Number of registrations: <?php echo $registrations; ?></p>
<?php
        } else {
            echo $noFile;
        }
?>
    </div>
    <!-- <form action="download.php" method="POST">
        <button type="submit" id="downloadLast" name="downloadLast">Download last registration</button>
    </form>
    <br> -->
<?php
    if (file_exists($csvFile)) {
?>
    <form action="download.php" method="POST">
        <input type="submit" id="download" name="download" value="Download registration data">
    </form>
<?php
    }
?>
</body>
</html>