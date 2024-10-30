<?php 
// Set the time zone to the correct time zone (e.g., US Central Time for Culver's)
date_default_timezone_set('America/Chicago');

// Function to scrape the Culver's website and extract the "Flavor of the Day"
function getFlavorOfTheDay($location) {
    // Initialize cURL to get HTML from the URL
    $url = 'https://www.culvers.com/restaurants/' . urlencode($location);
    $htmlContent = curl($url);

    // Check if the HTML content is empty or false
    if (!$htmlContent) {
        echo "Failed to retrieve HTML content from the URL: $url";
        exit; // Stop execution if no content is retrieved
    }

    // Create a new DOMDocument instance
    $dom = new DOMDocument();

    // Load HTML into the DOMDocument object (suppress errors with @)
    @$dom->loadHTML($htmlContent);

    // Create a new DOMXPath instance for querying
    $xpath = new DOMXPath($dom);

    // Perform an XPath query to find today's flavor of the day
    // Replace this with the actual class or tag found for today's flavor
    $nodes = $xpath->query("//*[contains(@class, 'RestaurantDetails_containerRestaurantFlavorContentHeading')]");

    // Check if any nodes were found
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            // Print today's flavor of the day (node's text content)
            echo "Today's Flavor of the Day: " . htmlentities($node->nodeValue) . "<br>";
        }
    } else {
        echo "No flavor of the day found for today.";
    }
}

// Use curl to grab the contents of the website, with SSL verification turned off
function curl($url) {
    // Assigning cURL options to an array
    $options = Array(
        CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
        CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
        CURLOPT_AUTOREFERER => TRUE,     // Automatically set the referer where following 'location' HTTP headers
        CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
        CURLOPT_TIMEOUT => 120,          // Setting the maximum amount of time for cURL to execute queries
        CURLOPT_MAXREDIRS => 10,         // Setting the maximum number of redirections to follow
        CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
        CURLOPT_URL => $url,             // Setting cURL's URL option with the $url variable passed into the function
        CURLOPT_HTTPHEADER => ["Cookie: RoadblockSayCheeseCurds=0"],
        CURLOPT_SSL_VERIFYPEER => FALSE,  // Disable SSL certificate validation
        CURLOPT_SSL_VERIFYHOST => FALSE   // Disable host validation
    );
    
    $ch = curl_init();  // Initialising cURL
    curl_setopt_array($ch, $options);  // Setting cURL's options using the previously assigned array data in $options
    $data = curl_exec($ch);  // Executing the cURL request and assigning the returned data to the $data variable
    
    // Check if cURL encountered an error
    if ($data === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo "cURL error: " . $error;
        exit;  // Stop execution if there was a cURL error
    }

    curl_close($ch);  // Closing cURL
    return $data;  // Returning the data from the function
}

// Main logic to handle GET requests
if (isset($_GET['location'])) {
    // Get the value of the "location" parameter
    $location = $_GET['location'];
    // Extract and print today's flavor of the day
    getFlavorOfTheDay($location);
} else {
    // Output an error if no location is provided
    echo "Location not provided";
}
?>
