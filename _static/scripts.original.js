/****************************** Module Header ******************************
 * Module Name:  XM
 * Project:      Expense-tracking
 *
 * Scripts file for all JavaScript related functions
 *
 * 1. fasttrim - Utility function, remove whitespace characters, front and back
 * 2. checkForMoney - check input field for money, maximum 10 characters + 3
 *
 * Revisions:
 *     1. Sundar Krishnamurthy          sundar@passion8cakes.com       04/14/2017       Initial file created.
 ***************************************************************************/

// Actual URL of our application - SITE_URL
var global_siteUrl = "$$SITE_URL$$";                // $$ SITE_URL $$

// 1. Utility function U1 - remove whitespace characters, front and back
function fasttrim(str) {
    var str = str.replace(/^\s\s*/, ''),
                  ws = /\s/,
                  i = str.length;

    while (ws.test(str.charAt(--i)))
        ;

    return str.slice(0, i + 1);
}

// 2. Check input field for money, maximum 8 characters
function checkForMoney(field) {

    var inputValue = fasttrim(field.value);

    if (inputValue.indexOf('.') === -1) {
        inputValue += ".00";
    } else if (inputValue.length > 1) {
        if (inputValue.charAt(0) === '.') {
            inputValue = "0" + inputValue;
        } else if (inputValue.charAt(inputValue.length - 1) === '.') {
            inputValue += "00";
        }
    }

    if ((inputValue.length > 2) && (inputValue.charAt(inputValue.length - 2) === '.')) {
        inputValue += "0";
    }

    if (inputValue != field.value) {
        field.value = inputValue;
    }

    if (!inputValue.match(/^\d{1,10}$|^\d{1,10}\.\d{1,2}$/)) {
        field.value = "";
    } else {
        field.value = parseFloat(inputValue).toFixed(2);
    }
}
