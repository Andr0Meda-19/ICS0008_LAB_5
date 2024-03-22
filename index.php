<?php
    $csvFile = "data.csv";
    if (isset($_POST["download"]) && file_exists($csvFile)) {
        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="registration.csv"');
        readfile($csvFile);
        exit();
    }
?>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitReservation"])) {
        $nameFirst = $_POST["nameFirst"] ?? NULL;
        $nameMiddle = $_POST["nameMiddle"] ?? NULL;
        $nameLast = $_POST["nameLast"] ?? NULL;
        $salute = $_POST["salute"] ?? NULL;
        $age = $_POST["age"] ?? NULL;
        $email = $_POST["email"] ?? NULL;
        $phone = $_POST["phone"] ?? NULL;
        $dateArrive = $_POST["dateArrive"] ?? NULL;
        $comment = $_POST["comment"] ?? NULL;

        $errorRequired = NULL;
        $errorNameFirst = NULL;
        $errorNameMiddle = NULL;
        $errorNameLast = NULL;
        $errorSalute = NULL;
        $errorAge = NULL;
        $errorEmail = NULL;
        $errorPhone = NULL;
        $errorDate = NULL;
        
        $containsError = false;

        if ($nameFirst == NULL || $nameLast == NULL || $age == NULL || $email == NULL || $dateArrive == NULL) {
            $containsError = true;
            $errorRequired = "Error: Required field(s) not filled.<br>";
        }

        $namePattern = "/^[a-zA-ZŠŽÄÜÖÕšžäüöõ ][a-zA-ZŠŽÄÜÖÕšžäüöõ\-' ]+$/";
        if (!preg_match($namePattern, $nameFirst) || strlen($nameFirst) > 64) {
            $containsError = true;
            $errorNameFirst = "Error: Invalid first name: $nameFirst.<br>Must be less than 64 characters and contain only Eng/Est letters, spaces, dashes or apostrophes.<br>";
        }

        if ($nameMiddle != NULL and (!preg_match($namePattern, $nameMiddle) || strlen($nameMiddle) > 64)) {
            $containsError = true;
            $errorNameMiddle = "Error: Invalid middle name: $nameMiddle.<br>Must be less than 64 characters and contain only Eng/Est letters, spaces, dashes or apostrophes.<br>";
        }

        if (!preg_match($namePattern, $nameLast) || strlen($nameLast) > 64) {
            $containsError = true;
            $errorNameLast = "Error: Invalid last name: $nameLast.<br>Must be less than 64 characters and contain only Eng/Est letters, spaces, dashes or apostrophes.<br>";
        }
        
        $allowedSalutations = array("mr", "ms", "mrs");
        if ($salute != NULL && !in_array($salute, $allowedSalutations)) {
            $containsError = true;
            $errorSalute = "Error: Invalid salutation: $salute.<br>Must be either 'Mr', 'Ms' or 'Mrs'.<br>";
        }

        $allowedAge = range(18, 98);
        if (!in_array($age, $allowedAge)) {
            $containsError = true;
            $errorAge = "Error: Invalid age: $age.<br>Must be between ages of 18 and 98 included.<br>";
        }

        $emailRegex = "/^(?!.*[-._]{2})[A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:[-._][A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\\.)+[A-Za-z]{2,}$/";
        $allowedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!preg_match($emailRegex, $allowedEmail)) {
            $containsError = true;
            $errorEmail = "Error: Invalid email: $allowedEmail.<br>Please enter a valid email address.<br>";
        }

        $phonePattern = "/^(\+\d{7,12}|\d{7,12})$/";
        if ($phone != NULL && !preg_match($phonePattern, $phone)) {
            $containsError = true;
            $errorPhone = "Error: Invalid phone number: $phone.<br>Must be between 7 and 12 digits long.<br>";
        }
        
        $emailPattern = "/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/";

        if (preg_match($emailPattern, $dateArrive)) {
            $date = DateTime::createFromFormat('Y-m-d', $dateArrive, new DateTimeZone("Europe/Amsterdam"));
            if ($date !== false && $date->format('Y-m-d') === $dateArrive) {
                $minDate = new DateTime("2023-01-01");
                $maxDate = new DateTime("2033-01-01");
                if ($date < $minDate || $date > $maxDate) {
                    $containsError = true;
                    $errorDate = "Error: Invalid date.<br>Must be an existing date set between dates 2023-01-01 and 2033-01-01.<br>";
                }
            } else {
                $containsError = true;
                $errorDate = "Error: Invalid date.<br>Must be an existing date set between dates 2023-01-01 and 2033-01-01.<br>";
            }
        } else {
            $containsError = true;
            $errorDate = "Error: Invalid date.<br>Must be an existing date set between dates 2023-01-01 and 2033-01-01.<br>";
        }

        if ($comment != NULL) {
            $comment = preg_replace("/\s+/", " ", $comment);
            $comment = trim($comment);
        }

        function isWeekend($date) {
                return $date->format('N') >= 6;
        }

        $isTodayWeekend = isWeekend($date);
        $price = "100€";
        if ($isTodayWeekend) {
            $price = "150€";
        }
    }
?>

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
    if (isset($containsError) && !$containsError) {
        
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
                $price,
            )
        );

        $csvFile = "data.csv";
        if (!file_exists($csvFile)) {
            touch($csvFile);
            chmod($csvFile, 0766);
        }
    
        $file = fopen($csvFile, "w");
        foreach ($registrationData as $key) {
            fputcsv($file, $key, ";", '"', "\\", PHP_EOL);
        }
        fclose($file);
?>
    <form id="formDownload" action="index.php" method="POST">
        <button type="submit" id="download" name="download">Download your registration data</button>
    </form>
    <div id="confirmedText">
<?php
        echo "Successful submition!<br>";
        if ($nameMiddle != NULL) {
            echo "Your name is: " . $nameFirst . " " . $nameMiddle . " " . $nameLast . "<br>";   
        } else {
            echo "Your name is: " . $nameFirst . " " . $nameLast . "<br>";
        }
        if ($salute != NULL)
            echo "Your Salutation is: " . $salute . "<br>";
        echo "Your Age is: " . $age . "<br>";
        echo "Your E-mail address is: " . $email . "<br>";
        if ($phone != NULL)
            echo "Your Phone Number is: " . $phone . "<br>";
        echo "Your Date of Arrival is: " . $dateArrive . "<br>";
        if ($comment != NULL)
            echo "Your Comment is: " . htmlspecialchars($comment) . "<br>";
        echo "The price is: " . $price . "<br>";
?>
    </div>
<?php
    } elseif (isset($containsError) && $containsError) {

        $errorData = array(
            array(
                $errorRequired,
                $errorNameFirst,
                $errorNameMiddle,
                $errorNameLast,
                $errorSalute,
                $errorAge,
                $errorEmail,
                $errorPhone,
                $errorDate,
            )
        );
?>
    <div id="confirmedError">
<?php
        foreach ($errorData as $key) {
            foreach ($key as $subKey) {
                if (isset($subKey)) echo $subKey . "<br>";
            }
        }
?>
    </div>
<?php
    }
?>

</body>
</html>