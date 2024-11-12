# Flavor of the Day Fetcher

This PHP project fetches the "Flavor of the Day" from Culver's using a given ZIP code. The code demonstrates how to make API requests to retrieve the daily flavor and optionally includes an image of the flavor.

## Requirements

- PHP with cURL support
- Internet connection to access the Culver's API

## Usage

There are two PHP scripts in this project:

1. **Basic Flavor Fetcher**: This script fetches and displays only the "Flavor of the Day" name based on a given ZIP code.
2. **Extended Flavor Fetcher with Image**: This script fetches both the "Flavor of the Day" name and an associated image.

### 1. Basic Flavor Fetcher

This script fetches the "Flavor of the Day" by ZIP code and displays it.

#### Code Example

```php
<?php
// Function to fetch the flavor of the day using a ZIP code
function getFlavorOfTheDay($zipcode) {
    $url = "https://www.culvers.com/api/locator/getLocations?location=$zipcode&limit=1";
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
        curl_close($ch);
        return;
    }
    
    curl_close($ch);
    $data = json_decode($result, true);

    if (isset($data['data']['geofences'][0]['metadata']['flavorOfDayName'])) {
        echo "Flavor of the Day: " . $data['data']['geofences'][0]['metadata']['flavorOfDayName'];
    } else {
        echo "Flavor of the day not found.";
    }
}

if (isset($_GET['zipcode'])) {
    $zipcode = $_GET['zipcode'];
    if (is_numeric($zipcode) && strlen($zipcode) === 5) {
        getFlavorOfTheDay($zipcode);
    } else {
        echo "Invalid ZIP code.";
    }
} else {
    echo "ZIP code parameter is required.";
}
?>
