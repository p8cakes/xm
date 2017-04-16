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
    <table id="xpenses">
      <tbody>
        <tr>
          <td>#</th>
          <td style="width: 75px;">&#128197;</td>
          <td style="width: 120px;"><span class="boldLabel">Title</span></td>
          <td><a class="forever" href="javascript:alert('Voila');">&#128181;</a></td>
          <td style="width: 60px;"><span class="boldLabel">Spend</span></td>
          <td style="width: 60px;"><span class="boldLabel">Pay -&gt;</span></td>
          <td style="width: 120px;"><span class="boldLabel">Source</span></td>
          <td style="width: 120px;"><span class="boldLabel">Target</span></td>
          <td style="width: 60px;"><span class="boldLabel">Income</span></td>
          <td><a class="forever" href="javascript:alert('Voila');">&#128181;</a></td>
          <td style="width: 60px;"><span class="boldLabel">-&gt; Pay</span></td>
        </tr>
        <tr style="vertical-align: top;">
          <td><span class="inputLabel">[New]</span></td>
          <td><input id="xmDate" name="xmDate" style="width: 90%;" maxlength="10"/></td>
          <td><input id="xmTitle" name="xmTitle" style="width: 90%;" maxlength="64"/></td>
          <td><span class="inputLabel">$</span></td>
          <td><input id="xmSpend" name="xmSpend" style="width: 90%;" maxlength="13" onblur="javascript:checkForMoney(this);"/></td>
          <td><input id="xmPay1" name="xmPay1" style="width: 90%;" maxlength="13" onblur="javascript:checkForMoney(this);"/></td>
          <td><input id="xmSource" name="xmSource" style="width: 90%;" maxlength="64"/></td>
          <td><input id="xmTarget" name="xmTarget" style="width: 90%;" maxlength="64"/></td>
          <td><input id="xmIncome" name="xmIncome" style="width: 90%;" maxlength="13" onblur="javascript:checkForMoney(this);"/></td>
          <td><span class="inputLabel">$</span></td>
          <td><input id="xmPay2" name="xmPay2" style="width: 90%;" maxlength="13" onblur="javascript:checkForMoney(this);"/></td>
        </tr>
        <tr>
          <td style="width: 80px;" colspan="2"><span class="boldLabel">Category</span></td>
          <td style="width: 150px;" colspan="3"><span class="boldLabel">Comments</span></td>
          <td style="width: 80px;" colspan="2"><span class="boldLabel">Tag</span></td>
          <td style="width: 100px;" colspan="2"><span class="boldLabel">Tags</span></td>
          <td style="width: 40px;"><span class="boldLabel">Exclude</span></td>
          <td><span class="boldLabel">&nbsp;</span></td>
        </tr>
        <tr style="vertical-align: top;">
          <td colspan="2"><input id="xmCategory" name="xmCategory" style="width: 90%;" maxlength="16"/></td>
          <td colspan="3"><input id="xmComments" name="xmComments" style="width: 90%;" maxlength="64"/></td>
          <td colspan="2"><input id="xmTag" name="xmTag" style="width: 90%;" maxlength="20"/></td>
          <td colspan="2"><span class="inputLabel">Security<br/>Sundar</span></td>
          <td><input type="checkbox" id="cboxExclude" name="cboxExclude"></td>
          <td><span class="inputLabel"><a href="javascript:alert('Voila 2');">Save</a>&nbsp;&nbsp;<a href="javascript:alert('Voila 2');">Clear</a></span></td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
<?php
ob_end_flush();
?>