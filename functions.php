<?php
// xm web application
//
// functions.php - define global variables and functions for xm website
//
// Functions:
// 1. postedFromSame - find out if the page referer was same as the form calling post
// 2. getCurrentPageUrl - Get the current page URL
// 3. formatMailDate - Format mail date in English: Like Dec 15th 2011 10:10:15 AM UTC
// 4. createMyGuid - Create a random GUID
//
// Cookies:
//    None
//
// Query Parameters:
//    None
//
// Session Variables:
//    None
//
// Stored Procedures:
//    None
//
// JavaScript Methods:
//    None
//
// Revisions:
//     1. Sundar Krishnamurthy          sundar@passion8cakes.com       03/29/2017      Initial file created.


// Define variables we use thro'out the web app
$global_dbServer = "10.0.0.136";                       // $$ DATABASE_SERVER $$
$global_dbName = "xpensdb";                           // $$ DATABASE_NAME $$
$global_dbUsername = "foobar";                         // $$ DB_USERNAME $$
$global_dbPassword = "2003LAS1985maa!0!";                         // $$ DB_PASSWORD $$

$global_siteUrl = "https://10.0.0.136/xm/";                               // $$ SITE_URL $$
$global_siteCookieQualifier = "10.0.0.136";           // $$ COOKIE_QUALIFIER $$
$global_useDomain = false;

// Function 1 - Check if the form has been posted from the same page
function postedFromSame($url) {

    $useUrl = "";

    $questionMarkPosition = strpos($url, "?");
    if ($questionMarkPosition === false) {
        $useUrl = $url;
    } else {
        $useUrl = substr($url, 0, $questionMarkPosition);
    }

    $sameForm = false;
    $prefixes = array("", "www.");
    $ports = array("", ':'.$_SERVER["SERVER_PORT"]);

    foreach ($prefixes as $prefix) {
        foreach ($ports as $port) {
            $pageUrl = "http";

            if ((array_key_exists("HTTPS", $_SERVER)) && ($_SERVER["HTTPS"] === "on")) {
                $pageUrl .= "s";
            }

            $pageUrl .= "://";
            $pageUrl .= $prefix.$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];

            if ($pageUrl == $useUrl) {
                $sameForm = true;
                break;
            }
        }
    }

    return $sameForm;
}


// Function 2: Get the current page URL
function getCurrentPageUrl() {

    global $global_useDomain;

    $pageUrl = "http";
    $wwwPrefix = "";

    if ($global_useDomain) {
        $wwwPrefix = "www.";
    }

    if ((array_key_exists("HTTPS", $_SERVER)) && ($_SERVER["HTTPS"] === "on")) {
        $pageUrl .= "s";
    }

    $pageUrl .= "://";
    if (($_SERVER["SERVER_PORT"] == "80") ||
        ((array_key_exists("HTTPS", $_SERVER)) && ($_SERVER["HTTPS"] === "on") && ($_SERVER["SERVER_PORT"] == 443))) {
        $pageUrl .= $wwwPrefix.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    } else {
        $pageUrl .= $wwwPrefix.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }

    return $pageUrl;
}

// Function 3: Format mail date in English: Like Dec 15th 2011 10:10:15 AM UTC
function formatMailDate($inputDate) {

    // Locate the month part that ends at the the first / after 5 spaces (to skip the month)
    $monthPos = strpos($inputDate, "-", 5);

    // Locate the space position, the first space
    $spacePos = strpos($inputDate, " ");

    // Obtain the month part, from 5th position until the month position ends
    $month = substr($inputDate, 5, $monthPos - 5);

    // Day part is the one trailing $monthPos, until $spacePos
    $dayStr = substr($inputDate, $monthPos + 1, $spacePos - $monthPos - 1);

    // Obtain the time part, whatever is trailing after the first space, until end of string
    $timeStr = substr($inputDate, $spacePos + 1, strlen($inputDate) - $spacePos - 1);

    // Find the :mm:ss part after hh for $timeRemainder
    $timeRemainder = substr($timeStr, 2, strlen($timeStr) - 2);

    // Locate hours elapsed for the day
    $hourPart = substr($timeStr, 0, 2);

    // Default qualifier is AM
    $qualifier = " AM";

    // Remove first digit from hour-part if it starts with 0
    if (substr($hourPart, 0, 1) == "0") {
        $hourPart = substr($hourPart, 1, 1);
    }

    // Cast this to int so you can check for AM/PM
    $hourPartInt = (int)$hourPart;

    // Now, check for 24-hour clock formats
    // Alter 00 to 12
    if ($hourPartInt == 0) {
        $hourPartInt = 12;
    } elseif ($hourPartInt > 12) {
        $hourPartInt -= 12;
        $qualifier = " PM";
    }

    // This will be our return value
    $returnDate = "";

    // Switch on $month to obtain 3-letter text representation
    switch ($month) {
        case '1':
        case '01':
           $returnDate = "January ";
           break;

        case '2':
        case '02':
           $returnDate = "February ";
           break;

        case '3':
        case '03':
           $returnDate = "March ";
           break;

        case '4':
        case '04':
           $returnDate = "April ";
           break;

        case '5':
        case '05':
           $returnDate = "May ";
           break;

        case '6':
        case '06':
           $returnDate = "June ";
           break;

        case '7':
        case '07':
           $returnDate = "July ";
           break;

        case '8':
        case '08':
            $returnDate = "August ";
            break;

        case '9':
        case '09':
            $returnDate = "September ";
            break;

        case '10': $returnDate = "October "; break;
        case '11': $returnDate = "November "; break;
        case '12': $returnDate = "December "; break;
    }

    // Default $day to 0, this will change in a few statements below
    $day = 0;

    // First off, if we find the first letter to be 0 and $dayStr to be 2 letters, trim the leading 0
    if (((substr($dayStr, 0, 1)) == '0') && (strlen($dayStr) == 2)) {
        // Also, cast this to int
        $day = (int)(substr($dayStr, 1, 1));
    } else {
        // Cast $dayStr as is to $day as int
        $day = (int)$dayStr;
    }

    // Tricky part, it's just 11th, 12th and 13th - use it as is
    if (($day > 10) && ($day < 14)) {
        $day .= "th ";
    } else {
        // Find the first digit by remainder with 10
        $digit = $day % 10;

        // Check the last digit to determine suffix
        switch ($digit) {
            case 0:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $day .= "th ";
                break;

            case 1: $day .= "st "; break;
            case 2: $day .= "nd "; break;
            case 3: $day .= "rd "; break;
         }
    }

    // Construct $returnDate adding in $day, year and time part
    $returnDate .= $day;
    $returnDate .= substr($inputDate, 0, 4);
    $returnDate .= " " . (($hourPartInt < 10) ? "0" . $hourPartInt : $hourPartInt) . $timeRemainder . $qualifier;

    return $returnDate;
}

// Function 4 - Create a random GUID
function createMyGuid() {

    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
?>
