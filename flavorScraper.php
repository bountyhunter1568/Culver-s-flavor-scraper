<?php
// Include simple_html_dom.php to parse the contents of our website
// Documentation: http://simplehtmldom.sourceforge.net/
include("simple_html_dom.php");

// The website I want to scrape for data
// This is specific to the location nearest me
if (isset($_GET['location'])) {
    // Get the value of the "location" parameter
    $location = $_GET['location'];
    $url = 'https://www.culvers.com/restaurants/' . urlencode($location);

    // Initialize simple_html_dom and curl
    $html = new simple_html_dom();
    $str = curl($url);
    $html->load($str);

    $flavorOfTheDay = [];

    // Use XPath to find the name of the flavor of the day
    $nameElement = $html->find("/html/body/div[1]/div/div/div/main/section/div[1]/div[2]/div[1]/h2");
    if ($nameElement) {
        $flavorOfTheDay['name'] = $nameElement[0]->plaintext;
    } else {
        $flavorOfTheDay['name'] = 'Flavor not found';
    }

    // Return the data in JSON format
    echo json_encode($flavorOfTheDay);
} else {
    echo "null";
}

// Use curl to grab the contents of the website
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
        CURLOPT_HTTPHEADER => ["Cookie: RoadblockSayCheeseCurds=0"]
    );
    
    $ch = curl_init();  // Initialising cURL
    curl_setopt_array($ch, $options);  // Setting cURL's options using the previously assigned array data in $options
    $data = curl_exec($ch);  // Executing the cURL request and assigning the returned data to the $data variable
    curl_close($ch);  // Closing cURL
    return $data;  // Returning the data from the function
}
?>
