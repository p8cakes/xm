<?php
// xm web application
//
// File: setCurrency.php - set the currency for current expense, income or transfer
//
// Functions:
//    None
//
// Query Parameters:
//    term: query-string value that lets you select source (0, default) or target (1)
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

$targetField = 0;

if (isset($_GET["term"]) &&
   ($_GET["term"] == "1")) {
    $targetField = 1;
}   //  End if (isset($_GET["term"]) &&

$errorMessage = "";

if (!isset($_SESSION["xm_currencies"])) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $global_siteUrl . "getWorldCurrencies.php?term=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === FALSE) {
        $errorMessage = "<ul><li>" . curl_error($ch) . "</li></ul>";
    } else if ($response != "") {
        $_SESSION["xm_currencies"] = $response;
    } else {
        $errorMessage = "<ul><li>No data found for provided input.</li><ul>";
    }   //  End if (isset($_GET["term"]) &&

    die($errorMessage);
}   //  End if (isset($_SESSION["xm_currencies"]))

$responseData = json_decode($_SESSION["xm_currencies"], true);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>xm: Set Currency</title>
    <link rel="stylesheet" type="text/css" href="_static/main.css" />
    <script type="text/javascript" language="JavaScript" src="_static/scripts.js"></script>

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <script language="javascript">

    // Function #1: onload, disable all four buttons as nothing is selected
    $(function() {
        $("#btnLeft").attr("disabled", "disabled");
        $("#btnRight").attr("disabled", "disabled");
        $("#btnUp").attr("disabled", "disabled");
        $("#btnDown").attr("disabled", "disabled");
    });

    // Function #2: set world currency event when selected
    function setWCSource() {

        // Disable movement back from right to left, enable movement from left to right
        $("#btnRight").removeAttr("disabled");
        $("#btnLeft").attr("disabled", "disabled");

        // Use regular JavaScript as we need to obtain reference just one time
        // Sadly, $("#selYourCurrencies").prop("selectedIndex", -1); does not seem to work
        yourList = document.getElementById("selYourCurrencies");
        if (yourList.selectedIndex >= 0) {
            yourList.selectedIndex = -1;
        }
    }

    function setYCSource(selYC) {

        // Disable movement forwards from worldCurrencies to yourCurrencies
        $("#btnRight").attr("disabled", "disabled");

        // Find out how many elements are in selYourCurrencies
        optionsLength = $("#selYourCurrencies option").length;

        // We have more than one available in your currencies set
        if (optionsLength > 1) {

            // Enable movement back from your currencies to world currencies
            $("#btnLeft").removeAttr("disabled");

            selYC = document.getElementById("selYourCurrencies");
            yourListIndex = selYC.selectedIndex;

            if (yourListIndex == 0) {
                $("#btnUp").attr("disabled", "disabled");
            } else {
                $("#btnUp").removeAttr("disabled");
            }

            if (yourListIndex == selYC.options.length - 1) {
                $("#btnDown").attr("disabled", "disabled");
            } else {
                $("#btnDown").removeAttr("disabled");
            }
        }

        wcList = document.getElementById("selWorldCurrencies");
        if (wcList.selectedIndex >= 0) {
           wcList.selectedIndex = -1;
        }
    }

    function moveCurrencyToYours() {

        id = $("#selWorldCurrencies option:selected").val();
        text = $("#selWorldCurrencies option:selected").text();

        newOption = "<option value=\"" + id + "\">" + text + "</option>";

        $("#selYourCurrencies").append(newOption);
        $("#selWorldCurrencies option:selected").remove();

        var selYC = document.getElementById("selYourCurrencies");
        selYC.selectedIndex = selYC.length - 1;
        selYC.focus();

        if (selYC.options.length > 1) {
            $("#btnLeft").removeAttr("disabled");
            $("#btnUp").removeAttr("disabled");
        }

        $("#btnRight").attr("disabled", "disabled");
        $("#btnDown").attr("disabled", "disabled");

        return false;
    }

    function moveCurrencyBack() {

        // Find the total number of elements in your currency list - must have at least one
        optionsLength = $("#selYourCurrencies option").length;

        // If you have 2 or more, you can take back items left
        if (optionsLength > 1) {

            // Find item that needs to move back
            id = parseInt($("#selYourCurrencies option:selected").val());
            text = $("#selYourCurrencies option:selected").text();

            selWC = document.getElementById("selWorldCurrencies");
            lastId = 0;

            if (selWC.options.length > 0) {
                // Find the last ID that exists on the left world currencies panel
                lastId = parseInt(selWC.options[selWC.options.length - 1].value);
            }

            // lastId is less than the ID we are trying to move, append to the end
            if ((selWC.options.length == 0) || (lastId < id)) {
                $("#selWorldCurrencies").append("<option value=\"" + id + "\">" + text + "</option>");
                index = selWC.options.length;
            } else {

                // Find which element we need to count down, and insert at that position
                index = 0;

                // Iterate down, looking for the first ID that is more than our movement ID
                for (i = 0; i < selWC.length; i++) {

                     // Get the ID at this element
                     wcId = parseInt(selWC[i].value);
                     index++;

                     // Found what we need, break
                     if (wcId > id) {
                         break;
                     }
                }

                // Create element that needs to go in, and insert it right before index position
                var oldOption = "<option value=\"" + id + "\">" + text + "</option>";
                $(oldOption).insertBefore("#selWorldCurrencies option:nth-child(" + index + ")");
            }

            // Remove element from yourCurrencies that we just created back on worldCurrencies list
            $("#selYourCurrencies option:selected").remove();

            // Show element as selected
            var selWC = document.getElementById("selWorldCurrencies");
            selWC.selectedIndex = index - 1;
        }

        // Set focus on worldCurrencies select
        selWC.focus();

        // Enable right movement
        $("#btnRight").removeAttr("disabled");

        // Disable everything else
        $("#btnLeft").attr("disabled", "disabled");
        $("#btnUp").attr("disabled", "disabled");
        $("#btnDown").attr("disabled", "disabled");

        return false;
    }

    function moveCurrencyUp() {

        // Find item that needs to move back
        id = $("#selYourCurrencies option:selected").val();
        text = $("#selYourCurrencies option:selected").text();
        index = $("#selYourCurrencies option:selected").index();

        // Remove element from yourCurrencies that we just created back on worldCurrencies list
        $("#selYourCurrencies option:selected").remove();

        // Create element that needs to go in, and insert it right before index position
        var oldOption = "<option value=\"" + id + "\">" + text + "</option>";
        $(oldOption).insertBefore("#selYourCurrencies option:nth-child(" + index + ")");

        // Show element as selected
        var selYC = document.getElementById("selYourCurrencies");
        selYC.selectedIndex = index - 1;
        selYC.focus();

        if (selYC.selectedIndex == 0) {
            $("#btnUp").attr("disabled", "disabled");
        } else {
            $("#btnUp").removeAttr("disabled");
            $("#btnDown").removeAttr("disabled");
        }
    }

    function moveCurrencyDown() {

        // Find the total number of elements in your currency list - must have at least one
        optionsLength = $("#selYourCurrencies option").length;

        // Find item that needs to move back
        id = $("#selYourCurrencies option:selected").val();
        text = $("#selYourCurrencies option:selected").text();
        index = $("#selYourCurrencies option:selected").index();

        // Remove element from yourCurrencies that we just created back on worldCurrencies list
        $("#selYourCurrencies option:selected").remove();

        // Create element that needs to go in, and insert it right before index position
        var oldOption = "<option value=\"" + id + "\">" + text + "</option>";

        var selYC = document.getElementById("selYourCurrencies");

        if (index < (optionsLength - 2)) {
            $(oldOption).insertBefore("#selYourCurrencies option:nth-child(" + (index + 2) + ")");
            $("#btnUp").removeAttr("disabled");
            $("#btnDown").removeAttr("disabled");
            selYC.selectedIndex = index + 1;
        } else {
            $("#btnDown").attr("disabled", "disabled");
            $("#selYourCurrencies").append(oldOption);
            selYC.selectedIndex = selYC.options.length - 1;
        }

        // Show element as selected
        selYC.focus();
    }

    </script>
  </head>
  <body>
    <form name="currencyForm" method="POST" action="setCurrency.php">
 <table>
    <tbody>
        <tr>
          <td>
              <span class="boldLabel">Common Currencies</span>
          </td>
          <td>
              <span class="inputLabel">&nbsp;</span>
          </td>
          <td>
              <span class="boldLabel">Your Currencies</span>
          </td>
          <td>
              <span class="boldLabel">&nbsp;</span>
          </td>

      </tr>
      <tr>
          <td>
              <select size="5" style="width: 25em;" name="selWorldCurrencies" id="selWorldCurrencies" onchange="javascript:setWCSource();">

                  <?php

                  $currencyId = 0;
                  $currencyText = "";
                  $symbol = "";

                  foreach ($responseData as &$currency) {
                      foreach($currency as &$currencyItem) {

                          if ($currencyItem["name"] === "currencyId") {
                              $currencyId = $currencyItem["value"];
                          }   //  End if ($currencyItem["name"] == "currencyId")

                          if ($currencyItem["name"] == "name") {
                              $currencyText = $currencyItem["value"];
                              $currencyText .= " - ";
                          }   //  End if ($currencyItem["name"] == "name")

                          if ($currencyItem["name"] == "symbol") {
                              $symbol = $currencyItem["value"];
                          }   //  End if ($currencyItem["name"] == "symbol")

                          if ($currencyItem["name"] == "abbr") {
                              $currencyText .= $currencyItem["value"];
                              $currencyText .= (" (" . $symbol . ")");
                              break;
                          }   //  End if ($currencyItem["name"] == "abbr")
                      }   //  End foreach($curreny as &$currencyItem)

                      print("<option value=\"" . $currencyId . "\">" . $currencyText . "</option>\n");
                  }   //  End foreach ($_SESSION["xm_currencies"] as &$currency) {
                  ?>
              </select>

          </td>
          <td>
              <button type="button" id="btnRight" name="btnRight" onclick="javascript:moveCurrencyToYours();">&rarr;</button><br/>
              <button type="button" id="btnLeft" name="btnLeft" onclick="javascript:moveCurrencyBack();">&larr;</button>
          </td>
          <td>
              <select size="5" style="width: 25em;" name="selYourCurrencies" id="selYourCurrencies" onclick="javascript:setYCSource(this);">
              </select>
          </td>
          <td>
              <button type="button" id="btnUp" name="btnUp" onclick="javascript:moveCurrencyUp();">&uarr;</button><br/>
              <button type="button" id="btnDown" name="btnDown"  onclick="javascript:moveCurrencyDown();"/>&darr;</button>
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
