<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Dropdown with Search</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        datalist option {
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <label for="countrySearch">Search Country:</label>
    <input list="countryList" id="countrySearch" placeholder="Type to filter countries..." oninput="showDropdown()">

    <datalist id="countryList">
        <?php
        // API URL
        $apiUrl = "https://restcountries.com/v3.1/all";

        // Initialize cURL session
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $countries = json_decode($response, true);

        if ($countries) {
            foreach ($countries as $country) {
                $countryName = $country['name']['common'];
                echo "<option value='$countryName'>$countryName</option>";
            }
        } else {
            echo "<option value=''>Unable to fetch country list</option>";
        }
        ?>
    </datalist>

    <script>
        function showDropdown() {
            const input = document.getElementById("countrySearch");
            input.focus();
        }
    </script>

</body>
</html>
