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

require_once("functions.php");

ob_start();

session_start();

// First off, check if the application is being used by someone not typing the actual server name in the header
if (strtolower($_SERVER["HTTP_HOST"]) != $global_siteCookieQualifier) {
    // Transfer user to same page, served over HTTPS and full-domain name
    header("Location: https://" . $global_siteCookieQualifier . $_SERVER["REQUEST_URI"]);
    exit();
}   //  End if (strtolower($_SERVER["HTTP_HOST"]) != $global_siteCookieQualifier)

$_SESSION["xm_userId"] = 2;
$_SESSION["xm_userStatus"] = 1;

// Bail out if user has not logged in as yet, or status is 0
if ((!isset($_SESSION["xm_userId"])) ||
	($_SESSION["xm_userStatus"] === 0)) {
    exit();
}   //  End if ((!isset($_SESSION["xm_userId"])) ||

$userId = $_SESSION["xm_userId"];
/*
if (!isset($_SESSION["xm_sourceTargets"]) {

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

}
*/
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>xm: Sample Expense Page</title>
    <link rel="stylesheet" type="text/css" href="_static/main.css" />
    <script type="text/javascript" language="JavaScript" src="_static/scripts.js"></script>

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <script language="javascript">
      // Autocomplete goes here
      $(document).ready(function() {
        $('#xmTitle').autocomplete({
          source: "getheads.php"
        });
      });

      $(function() {
        $("#xmDate").datepicker();
      });
    </script>
  </head>
  <body>
    <form name="xpenseForm" method="POST" action="index.php">  
    <table id="xpenses" style="width: 70%;">
      <tbody>
        <tr style="vertical-align: top;">
          <td class="commonLabel" style="width: 20%">
            <span class="inputLabel">ID&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
          </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <span class="inputLabel" id="idLabelSpan">*</span>
          </td>
        </tr>
        <tr style="vertical-align: middle;">
          <td class="commonLabel">
            <span class="boldLabel">&#128197;&nbsp;Date</span>
 		    <span class="requiredField">*</span>
 		    <span class="inputLabel">:&nbsp;</span>
 		  </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmDate" name="xmDate" maxlength="10"/>
		    <span class="inputLabel">&nbsp;&nbsp;&nbsp;Exclude&nbsp;&nbsp;:&nbsp;<input type="checkbox" name="cboxExclude" id="cboxExclude" />
          </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&#128221;</span>
            <span class="boldLabel">Title</span>
 		    <span class="requiredField">*</span>
 		    <span class="inputLabel">:&nbsp;</span>
 		  </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmTitle" name="xmTitle" style="width: 45%;" maxlength="64"/>
          </td>
        </tr>
        <tr style="vertical-align: middle;">
          <td class="commonLabel">
            <span class="inputLabel">&nbsp;</span>
 		  </td>
          <td class="commonInput" style="width: 40%">
            <span class="inputLabel">&nbsp;&nbsp;&nbsp;&nbsp;Source</span>
			<a class="forever" href="javascript:alert('Voila');">&#127974;</a>
 		  </td>
          <td class="commonInput" style="width: 40%">
            <span class="inputLabel">&nbsp;&nbsp;&nbsp;&nbsp;Target</span>
 		  </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&nbsp;</span>
 		  </td>
          <td class="commonInput">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmSource" name="xmSource" style="width: 80%;" maxlength="64"/>
 		  </td>
          <td class="commonInput">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmTarget" name="xmTarget" style="width: 80%;" maxlength="64"/>
 		  </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&#128176;</span>
            <span class="boldLabel">Amount</span>
 		    <span class="requiredField">*</span>
 		    <span class="inputLabel">:&nbsp;</span>
 		  </td>
          <td class="commonInput">
            <span class="inputLabel">$</span>
            <input type="text" id="xmAmount" name="xmAmount" style="width: 30%;" maxlength="13" onblur="javascript:checkForMoney(this);"/>
            <span class="inputLabel">(in USD)&nbsp;<a class="forever" href="javascript:alert('Voila');">&#128181;</a></span>
		  </td>
          <td class="commonInput">
            <span class="inputLabel">$</span>
            <input type="text" id="xmIncome" name="xmIncome" style="width: 30%;" maxlength="13" onblur="javascript:checkForMoney(this);"/>
            <span class="inputLabel">(in USD)&nbsp;<a class="forever" href="javascript:alert('Voila');">&#128181;</a></span>
		  </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&#128214;</span>
            <span class="inputLabel">Category&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
          </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
		    <input type="text" id="xmCategory" name="xmCategory" style="width: 45%;" maxlength="32"/>
            <a class="forever" href="javascript:alert('Voila');">&#128210;</a>
          </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&#128206;</span>
            <span class="inputLabel">Comment&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
          </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmComment" name="xmComment" style="width: 45%;" maxlength="64"/>
          </td>
        </tr>
        <tr style="vertical-align: top;">
          <td class="commonLabel">
            <span class="inputLabel">&#128205;</span>
            <span class="inputLabel">Tags&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
          </td>
          <td class="commonInput" colspan="2">
            <span class="inputLabel">&nbsp;&nbsp;</span>
            <input type="text" id="xmTags" name="xmTags" style="width: 45%;" maxlength="32"/>
            <a class="forever" href="javascript:alert('Voila');">&#128204;</a>
          </td>
        </tr>
        <tr style="vertical-align: top;">
          <td style="text-align: center;" colspan="3">
            <input type="Submit" value="Save" text="Save2"/><input type="button" value="Cancel" text="Cancel2"/>
          </td>
        </tr>
      </tbody>
    </table>
    </form>
  </body>
</html>
<?php
ob_end_flush();
?>