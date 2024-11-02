<?php
// Function to fetch the flavor of the day and its image using a ZIP code
function getFlavorOfTheDay($zipcode) {
    // Construct the API URL with the given ZIP code
    $url = "https://www.culvers.com/api/locator/getLocations?location=$zipcode&limit=1";
    
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

    // Check if the JSON contains the flavor of the day and the slug in the correct structure
    if (isset($data['data']['geofences'][0]['metadata']['flavorOfDayName']) && isset($data['data']['geofences'][0]['metadata']['flavorOfDaySlug'])) {
        // Get the flavor of the day and the slug
        $flavorOfDayName = $data['data']['geofences'][0]['metadata']['flavorOfDayName'];
        $flavorOfDaySlug = $data['data']['geofences'][0]['metadata']['flavorOfDaySlug'];

        // Construct the image URL
        $imageUrl = "https://cdn.culvers.com/menu-item-detail/" . $flavorOfDaySlug;

        // Print the flavor of the day name and image
        echo "Flavor of the Day: " . $flavorOfDayName . "<br>";
        echo "<img src='" . $imageUrl . "' alt='Image of " . $flavorOfDayName . "'>";
    } else {
        // Print an error message if the keys are not found
        echo "Flavor of the day or image not found.";
    }
}

// Check if the ZIP code is provided as a GET parameter
if (isset($_GET['zipcode'])) {
    $zipcode = $_GET['zipcode'];
    
    // Validate the ZIP code (must be numeric and 5 digits)
    if (is_numeric($zipcode) && strlen($zipcode) === 5) {
        // Call the function to get the flavor of the day and its image
        getFlavorOfTheDay($zipcode);
    } else {
        echo "Invalid ZIP code.";
    }
} else {
    // Print an error message if the ZIP code is not provided
    echo "ZIP code parameter is required.";
}
?>
