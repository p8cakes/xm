<?php
// xm web application
//
// File: getheads.php - get all the heads you find, out as json
//
// Functions:
//    None
//
// Query Parameters:
//    term: query-string value furnished from jQuery Autocomplete framework for the text-box
//
// Session Variables:
//    xm_userId: Logged-in user's ID (should be set to confirm user's selections)
//    xm_userStatus: Used to check if user is still valid on the xm system
//
// Stored Procedures:
//    getHeads - get top 10 heads for the criteria specified in the head field
//
// JavaScript functions:
//    None
//
// Revisions:
//     1. Sundar Krishnamurthy          sundar@passion8cakes.com       03/29/2017      Initial file created.


// Include functions.php that contains all our functions
require_once("functions.php");

// Start output buffering on
ob_start();

// Start the initial session
session_start();

// First off, check if the application is being used by someone not typing the actual server name in the header
if (strtolower($_SERVER["HTTP_HOST"]) != $global_siteCookieQualifier) {
    // Transfer user to same page, served over HTTPS and full-domain name
    header("Location: https://" . $global_siteCookieQualifier . $_SERVER["REQUEST_URI"]);
    exit();
}   //  End if (strtolower($_SERVER["HTTP_HOST"]) != $global_siteCookieQualifier)

$_SESSION["xm_userId"] = 2;
$_SESSION["xm_userStatus"] = 3;

// Bail out if user has not logged in as yet, or status is 0
if ((!isset($_SESSION["xm_userId"])) ||
	($_SESSION["xm_userStatus"] === 0)) {
    exit();
}   //  End if ((!isset($_SESSION["xm_userId"])) ||

$userId = $_SESSION["xm_userId"];
$head = "";

// All user-inputs for an autocomplete field come via a query-string parameter called "term"
if (isset($_GET["term"])) {
    $head = trim($_GET["term"]);
}   //  End if (isset($_GET["term"]))

// All expense heads stored in this array
$heads = array();

// Next, if this form has fetched over get and head furnished is not blank
if (($_SERVER["REQUEST_METHOD"] === "GET") &&
    ($head != "")) {

    // Query heads from DB
    // Connect to DB
    $con = mysqli_connect($global_dbServer, $global_dbUsername, $global_dbPassword);

    // Unable to connect, display error message
    if (!$con) {
        $heads[] = array("label" => "ERROR: Could not connect to database server, code: 001");
    } else {

        // DB selected will be selected Database on server
        $db_selected = mysqli_select_db($con, $global_dbName);

        // Unable to use DB, display error message
        if (!$db_selected) {		
            $heads[] = array("label" => "ERROR: Could not connect to the database, code: 002");
		} else {
            $useHead = mysqli_real_escape_string($con, $head);

            // This is the query we will run to get possible heads for this user.
            $query = "call getHeads($userId,'$useHead',10);";
			
            // Result of query
            $result = mysqli_query($con, $query);
	
            // Unable to fetch result, display error message
            if (!$result) {

                $heads[] = array("label" => "ERROR: Invalid query furnished, code: 003");

//              $message = "Invalid query: " . mysqli_error($con) . "\n";
//              $message = $message . "Whole query: " . $query;
//              die($message);
            } else {

                while ($row = mysqli_fetch_assoc($result)) {
                    $heads[] = array("label" => $row["head"]);
                }   //  End while ($row = mysqli_fetch_assoc($result))

               // Free result
               mysqli_free_result($result);
            }   //  End if (!$result)
        }   //  End if (!$db_selected)
		
        // Close connection
        mysqli_close($con);
	}   //  End if (!$con)
	
    // Send result back
    print(json_encode($heads));
}   //  End if (($_SERVER["REQUEST_METHOD"] === "GET") &&

ob_end_flush();
?>
