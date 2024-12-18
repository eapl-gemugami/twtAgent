<?php

/* 

twtAgent.php - log who reads your twtxt.txt

Based on: https://gist.mills.io/prologic/223ef70ac7334b48acafcc095a7a9262 (generated by ChatGPT, 2024-09-28)

To get twtAgent.php to work you need to add this to your .htaccess file:

RewriteEngine on
Redirect /twtxt.txt /twtAgent.php

---

# TODO:
    
- [ ] Look into parsing the log: https://git.mills.io/yarnsocial/useragent

- [ ] Limit the size of the log file
    - Only save the latest requests from each user agent
    - Only keep log for one week

- [ ] Implement this in timeline in some way
    - Move into it one folder: /twtAgents/
    - Integrate it into the UI under: /followers

- Find a better name
    - twtLog
    - twtUserAgents
    - twtFollowers    
*/


// Path to the log file where User-Agents will be saved
$csvFile = 'twtAgent.csv';

// Parse the HTTP request headers to get the User-Agent
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $updatedFile = "";

    $rows = file($csvFile);



    foreach($rows as $row) {

        // IF userAgent is NOT in log, then apped it using the old code

        // ELSE Update excisting row

        if(strpos($row, $userAgent) !== false) {
            $csvRow = str_getcsv($row, "\t");
            $timestamp = str_replace("+00:00","Z",date(DATE_RFC3339));
            $updatedRow = $timestamp . "\t" . $csvRow[1] . "\t" . $csvRow[2]+1 . PHP_EOL;
            $updatedFile .= $updatedRow;
        } else {
            $updatedFile .= $row;
        }
    }

    // Open the log file for appending and write the User-Agent
    file_put_contents($csvFile, $updatedFile);
}


/*

$lookFor = "jenny/latest (+https://www.uninformativ.de/twtxt.txt; @movq)";
$updatedFile = "";

$rows = file($csvFile);

foreach($rows as $row) {
    if(strpos($row, $lookFor) !== false) {
        $csvRow = str_getcsv($row, "\t");
        $timestamp = str_replace("+00:00","Z",date(DATE_RFC3339));
        $newRow = $timestamp . "\t" . $csvRow[1] . "\t" . $csvRow[2]+1 . PHP_EOL;
        $updatedFile .= $newRow;
    } else {
        $updatedFile .= $row;
    }
}

file_put_contents($csvFile, $updatedFile);
*/

// Debug: View CVS as arrarys

$lines = file($csvFile);

foreach ($lines as $line) {

    if (strpos($line, $userAgent) !== false) {
        $row = str_getcsv($line, "\t");
        echo "<pre>"; 
        print_r($row);
        echo "</pre>";
    } 
}


