<?php
// Function to fetch the flavor of the day and its image using a ZIP code
function getFlavorOfTheDay($zipcode, $limit) {
    // Construct the API URL with the given ZIP code
    $url = "https://www.culvers.com/api/locator/getLocations?location=$zipcode&limit=$limit";
    
    // Initialize cURL to make the request to the API
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);                // Set the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // Return the result as a string
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     // Disable SSL verification (not recommended for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);     // Disable SSL host verification
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    
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

    $flavorOfDayName = [];
    $locationSlug = [];

    // Ensure $limit is a positive integer
    $limit = max(1, (int)$limit); 

    // Loop through the geofences, up to the specified limit
    for ($i = 0; $i < $limit; $i++) {
        // Check if the flavor of the day and slug keys exist for this geofence
        if (isset($data['data']['geofences'][$i]['metadata']['flavorOfDayName']) && 
            isset($data['data']['geofences'][$i]['metadata']['flavorOfDaySlug'])) {

            // Store the flavor name and slug
            $flavorOfDayName[$i] = $data['data']['geofences'][$i]['metadata']['flavorOfDayName'];
            $locationSlug[$i] = $data['data']['geofences'][$i]['metadata']['slug'];

            // Capitalize each word and remove dashes from locationSlug
            $locationSlug[$i] = str_replace('-', ' ', $locationSlug[$i]);
            $locationSlug[$i] = ucwords($locationSlug[$i]); 

            // Construct the image URL
            //$imageUrl = "https://cdn.culvers.com/menu-item-detail/" . $data['data']['geofences'][$i]['metadata']['flavorOfDaySlug'];

            // Output the flavor name, and modified locationSlug
            echo $flavorOfDayName[$i] . "<br>" . $locationSlug[$i] . "<br>";            
        } else {
            // Handle missing keys (e.g., skip or display an error)
            // echo "Flavor of day or image not found for geofence $i.<br>";
        }
    }
}

// Check if the ZIP code is provided as a GET parameter
if (isset($_GET['zipcode'])) {
    $zipcode = $_GET['zipcode'];
    
    // Validate the ZIP code (must be numeric and 5 digits)
    if (is_numeric($zipcode) && strlen($zipcode) === 5) {
        // Check if the ZIP code is provided as a GET parameter
        
        $limit = $_GET['limit'];
        if (!isset($limit)) { 
            getFlavorOfTheDay($zipcode, 1); 
        } else {
            getFlavorOfTheDay($zipcode, $limit);
        }
    } else {
        // Print an error message if the ZIP code is invalid
        echo "Invalid ZIP code. Please provide a 5-digit numeric ZIP code.";
    }
} else {
    // Print an error message if the ZIP code is not provided
    echo "ZIP code parameter is required.";
    
}
?>