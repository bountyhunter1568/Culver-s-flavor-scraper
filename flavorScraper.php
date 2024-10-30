<?php
// Function to fetch the flavor of the day using latitude and longitude
function getFlavorOfTheDay($latitude, $longitude) {
    // Construct the API URL with the given latitude and longitude
    $url = "https://www.culvers.com/api/locator/getLocations?lat=$latitude&long=$longitude&limit=1";
    
    // Initialize cURL to make the request to the API
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);                // Set the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // Return the result as a string
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     // Disable SSL verification (not recommended for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);     // Disable SSL host verification
    
    // Execute the cURL request
    $result = curl_exec($ch);
    
    // Check if any error occurred during the cURL request
    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
        curl_close($ch);
        return;
    }
    
    // Close the cURL session
    curl_close($ch);
    
    // Decode the returned JSON data into an associative array
    $data = json_decode($result, true);

    // Check if the JSON contains the flavor of the day in the correct structure
    if (isset($data['data']['geofences'][0]['metadata']['flavorOfDayName'])) {
        // Get the flavor of the day and print it
        echo "Flavor of the Day: " . $data['data']['geofences'][0]['metadata']['flavorOfDayName'];
    } else {
        // Print an error message if the key is not found
        echo "Flavor of the day not found.";
    }
}

// Check if latitude and longitude are provided as GET parameters
if (isset($_GET['lat']) && isset($_GET['long'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['long'];
    
    // Validate latitude and longitude
    if (is_numeric($latitude) && is_numeric($longitude)) {
        // Call the function to get the flavor of the day
        getFlavorOfTheDay($latitude, $longitude);
    } else {
        echo "Invalid latitude or longitude values.";
    }
} else {
    // Print an error message if latitude and longitude are not provided
    echo "Latitude and longitude parameters are required.";
}
?>
