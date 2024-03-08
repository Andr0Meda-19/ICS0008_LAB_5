<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="antimo">
    <title>LAB 5 - PHP Form</title>
    <link rel="stylesheet" href="./styles/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="./index.php">Index</a>
            <a href="./download.php">Download</a>
        </nav>
    </header>
    <form id="formReserve" action="index.php" method="POST">
        
        <label for="nameFirst">First name:</label>
        <input type="text" id="nameFirst" name="nameFirst" maxlength="64" pattern="^[a-zA-ZŠŽÄÜÖÕšžäüöõ ][a-zA-ZŠŽÄÜÖÕšžäüöõ\-' ]+$" required><br>
        <label for="nameMiddle">Middle name:</label>
        <input type="text" id="nameMiddle" name="nameMiddle" placeholder="optional" maxlength="64" pattern="^[a-zA-ZŠŽÄÜÖÕšžäüöõ ][a-zA-ZŠŽÄÜÖÕšžäüöõ\-' ]+$"><br>
        <label for="nameLast">Last name:</label>
        <input type="text" id="nameLast" name="nameLast" maxlength="64" pattern="^[a-zA-ZŠŽÄÜÖÕšžäüöõ ][a-zA-ZŠŽÄÜÖÕšžäüöõ\-' ]+$" required><br>
        
        <span>Salutation (optional):</span>
        <label for="saluteMr" class="salute">Mr</label>
        <input type="radio" id="saluteMr" name="salute" value="mr"><br>
        <label for="saluteMs" class="salute">Ms</label>
        <input type="radio" id="saluteMs" name="salute" value="ms"><br>
        <label for="saluteMrs" class="salute">Mrs</label>
        <input type="radio" id="saluteMrs" name="salute" value="mrs"><br>
        
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="18" max="98" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="64" required><br>
        
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" placeholder="optional" pattern="^(\+\d{7,12}|\d{7,12})$"><br>
        
        <label for="dateArrive">Date of arrival:</label>
        <input type="date" id="dateArrive" name="dateArrive" min="2023-01-01" max="2033-01-01" required><br>
        
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" placeholder="optional" maxlength="200"></textarea><br>
        
        <input type="submit" id="submitReservation" name="submitReservation">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST) {
        $nameFirst = $_POST["nameFirst"] ?? NULL;
        $nameMiddle = $_POST["nameMiddle"] ?? NULL;
        $nameLast = $_POST["nameLast"] ?? NULL;
        $salute = $_POST["salute"] ?? NULL;
        $age = $_POST["age"] ?? NULL;
        $email = $_POST["email"] ?? NULL;
        $phone = $_POST["phone"] ?? NULL;
        $dateArrive = $_POST["dateArrive"] ?? NULL;
        $comment = $_POST["comment"] ?? NULL;
        
        // $serverFormValidation = true;

        if ($nameFirst == NULL || $nameLast == NULL || $age == NULL || $email == NULL || $dateArrive == NULL) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Please fill out the form with necessary information!";
            echo "</div>";
            exit();
        }

        $namePattern = "/^[a-zA-ZŠŽÄÜÖÕšžäüöõ ][a-zA-ZŠŽÄÜÖÕšžäüöõ\-' ]+$/";
        if (!preg_match($namePattern, $nameFirst)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid first name: $nameFirst";
            echo "</div>";
            exit();
        }
        if ($nameMiddle != NULL and (!preg_match($namePattern, $nameMiddle) || strlen($nameMiddle) > 64)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "<p>Invalid middle name: $nameMiddle</p>";
            echo "</div>";
            exit();
        }
        if (!preg_match($namePattern, $nameLast)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid last name: $nameLast";
            echo "</div>";
            exit();
        }
        
        $allowedSalutations = array("mr", "ms", "mrs");
        if ($salute != NULL && !in_array($salute, $allowedSalutations)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid salutation: $salute";
            echo "</div>";
            exit();
        }

        $allowedAge = range(18, 98);
        if ($age != NULL && !in_array($age, $allowedAge)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid age: $age";
            echo "</div>";
            exit();
        }

        $allowedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        // if (!filter_var($allowedEmail, FILTER_VALIDATE_EMAIL)) {
        //     die("Invalid email: $allowedEmail");
        // }
        $emailRegex = "/^(?!.*[-._]{2})[A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:[-._][A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\\.)+[A-Za-z]{2,}$/";
        if (!preg_match($emailRegex, $allowedEmail)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid email: $allowedEmail";
            echo "</div>";
            exit();
        }

        $phonePattern = "/^(\+\d{7,12}|\d{7,12})$/";
        if ($phone != NULL && !preg_match($phonePattern, $phone)) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Invalid phone number: $phone";
            echo "</div>";
            exit();
        }

        $minDate = new DateTime("2023-01-01");
        $maxDate = new DateTime("2033-01-01");
        $date = new DateTime($dateArrive);
        if ($date < $minDate || $date > $maxDate) {
            // $serverFormValidation = false;
            echo '<div id="confirmedError">';
            echo "Date out of bound.";
            echo "</div>";
            exit();
        }

        if ($comment != NULL) {
            $comment = preg_replace("/\s+/", " ", $comment);
            $comment = trim($comment);
        }

        // if ($serverFormValidation) {
        $registrationData = array(
            array(
                $nameFirst,
                $nameMiddle,
                $nameLast,
                $salute,
                $age,
                $email,
                $phone,
                $dateArrive,
                $comment,
            )
        );

        $csvFile = "data.csv";
        if (!file_exists($csvFile)) {
            touch($csvFile);
            chmod($csvFile, 0766);
        }

        $file = fopen($csvFile, "a");
        foreach ($registrationData as $key) {
            fputcsv($file, $key, ";", '"', "\\", PHP_EOL);
        }
        fclose($file);
        // $csvRowData = implode(";", $registrationData) . PHP_EOL;
        // file_put_contents($csvFile, $csvRowData, FILE_APPEND);

        // }

        echo '<div id="confirmedText">';
        echo "Your First name is: " . $nameFirst . "<br>";
        if ($nameMiddle != NULL)
            echo "Your Middle name is: " . $nameMiddle . "<br>";   
        echo "Your Last name is: " . $nameLast . "<br>";
        if ($salute != NULL)
            echo "Your Salutation is: " . $salute . "<br>";
        echo "Your Age is: " . $age . "<br>";
        echo "Your E-mail address is: " . $email . "<br>";
        if ($phone != NULL)
            echo "Your Phone Number is: " . $phone . "<br>";
        echo "Your Date of Arrival is: " . $dateArrive . "<br>";
        if ($comment != NULL)
            echo "Your Comment is: " . htmlspecialchars($comment) . "<br>";
        echo "</div>";
    }
    ?>
</body>
</html>