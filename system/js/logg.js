
/**
 * Get all log info
 */
$(function getAllLogInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllLoggInfo',     // given requset to controller
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data); // pass array from response to display logg table
        }
    });
});

/**
 * Update log table
 */
function updateLogTable() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllLoggInfo',     // given request to controller
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);     // pass array from respone to display log table
        }
    });
}


/**
 * Display logg table 
 */
function displayLoggTable(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("loggTableTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var loggContainer = document.getElementById("loggTableContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}




/**
 * Update log table with search result
 */
$(function POSTsearchForLog() {
    // run if search for log is submitted
    $('#searchForLog').submit(function () {
        var url = $(this).attr('action');   // get for action
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForLog")[0].reset();  // reset search form
                displayLoggTable(data);         // display log table with result
            }
        });
        return false;
    });
});



// save what to log
$(function POSTloggCheck() {
    // run if loggCheck form is submitted
    $('#loggCheck').submit(function () {
        var url = $(this).attr('action');   // get for action
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $('.dropdown.open').removeClass('open'); // close dropdown meny
            }
        });
        return false;
    });
});



// make it possible to download to CSV, based on example code on stackoverflow:
// https://stackoverflow.com/questions/16078544/export-to-csv-using-jquery-and-html

$(document).ready(function () {
    
    function exportTableToCSV($table, filename) {

        var $rows = $table.find('tr:has(td),tr:has(th)'),
                // Temporary delimiter characters unlikely to be typed by keyboard
                // This is to avoid accidentally splitting the actual contents
                tmpColDelim = String.fromCharCode(11), // vertical tab character
                tmpRowDelim = String.fromCharCode(0), // null character

                // actual delimiter characters for CSV format
                colDelim = '","',
                rowDelim = '"\r\n"',
                // Grab text from table into CSV formatted string
                csv = '"' + $rows.map(function (i, row) {
                    var $row = $(row), $cols = $row.find('td,th');

                    return $cols.map(function (j, col) {
                        var $col = $(col), text = $col.text();

                        return text.replace(/"/g, '""'); // escape double quotes

                    }).get().join(tmpColDelim);

                }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',
                // Data URI
                csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        console.log(csv);

        if (window.navigator.msSaveBlob) { // IE 10+
            //alert('IE' + csv);
            window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv");
        } else {
            $(this).attr({'download': filename, 'href': csvData, 'target': '_blank'});
        }
    }

    // This must be a hyperlink
    $("#loggToCSV").on('click', function (event) {

        exportTableToCSV.apply(this, [$('#loggTableContainer'), 'LagersystemLogg.csv']);

        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });

});

/**
 * Get info of what has active logging
 */
$(function getLoggCheckStatus() {
    $.ajax({
        type: 'GET',
        url: '?request=getLoggCheckStatus',     // given request to controller
        dataType: 'json',
        success: function (data) {
            updateCheckbox(data);   // update marking of checkboxes
            typeSearchTemplate(data);   // populate type advance search meny
        }
    });
});

/**
 * Check of checkboxes of types with active logging
 */
function updateCheckbox(data) {
    // check if type has "1" as checkStatus, if yes, mark off checkbox
    if (data.checkStatus[0].typeCheck > 0) {
        $("#edit").prop('checked', true);
    }
    if (data.checkStatus[1].typeCheck > 0) {
        $("#login").prop('checked', true);
    }
    if (data.checkStatus[2].typeCheck > 0) {
        $("#restriction").prop('checked', true);
    }
    if (data.checkStatus[3].typeCheck > 0) {
        $("#creation").prop('checked', true);
    }
    if (data.checkStatus[4].typeCheck > 0) {
        $("#stockdelivery").prop('checked', true);
    }
    if (data.checkStatus[5].typeCheck > 0) {
        $("#sale").prop('checked', true);
    }
    if (data.checkStatus[6].typeCheck > 0) {
        $("#return").prop('checked', true);
    }
    if (data.checkStatus[7].typeCheck > 0) {
        $("#transfer").prop('checked', true);
    }
    if (data.checkStatus[8].typeCheck > 0) {
        $("#deleting").prop('checked', true);
    }
    if (data.checkStatus[9].typeCheck > 0) {
        $("#stocktaking").prop('checked', true);
    }
}

/**
 * toogle between show / hode advance search meny
 */ 
function toggler() {
    $("#advanceSearch").toggle();
}

/**
 * Populate advance search meny
 */
$(function getAdvanceSearchDAta() {
    $.ajax({
        type: 'GET',
        url: '?request=getAdvanceSearchData',   // given request to controller
        dataType: 'json',
        success: function (data) {
            userSearchTemplate(data);   // populate user search
            storageSearchTemplate(data);    // populate storage search
            productSearchTemplate(data);    // populate product search
            groupSearchTemplate(data);  // populate group search
        }
    });
});

/**
 * populate type search dropdown meny
 */
function typeSearchTemplate(data) {
    var $typeTemplate = $('#typeContainer');    // set element-id to populate
    $typeTemplate.empty();
    // populate given element-id with rows with name and checkbox
    $.each(data.checkStatus, function (i, item) {
        $typeTemplate.append('<tr><td id="bordernone">' + item.typeName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="loggType[]" value="'+item.typeID+'"></td></tr>');

    });
}

/**
 * populate storage search dropdown meny
 */
function storageSearchTemplate(data) {
    // set element-id to populate
    var $storageTemplate = $('#storageContainer');  
    $storageTemplate.empty();
    var $toStorageTemplate = $('#toStorageContainer');
    $toStorageTemplate.empty();
    var $fromStorageTemplate = $('#fromStorageContainer');
    $fromStorageTemplate.empty();
    // populate given element-id with rows with name and checkbox
    $.each(data.storageInfo, function (i, item) {
        $storageTemplate.append('<tr><td id="bordernone">' + item.storageName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="storage[]" value="'+item.storageID+'"></td></tr>');
        $toStorageTemplate.append('<tr><td id="bordernone">' + item.storageName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="toStorage[]" value="'+item.storageID+'"></td></tr>');
        $fromStorageTemplate.append('<tr><td id="bordernone">' + item.storageName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="fromStorage[]" value="'+item.storageID+'"></td></tr>');

    });
}

/**
 * populate user search dropdown meny
 */
function userSearchTemplate(data) {
    // set element-id to populate
    var $usernameTemplate = $('#usernameContainer');
    $usernameTemplate.empty();
    var $onUserTemplate = $('#onUserContainer');
    $onUserTemplate.empty();
    // populate given element-id with rows with name and checkbox
    $.each(data.userInfo, function (i, item) {
        $usernameTemplate.append('<tr><td id="bordernone">' + item.username +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="username[]" value="'+item.userID+'"></td></tr>');
        $onUserTemplate.append('<tr><td id="bordernone">' + item.username +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="onUser[]" value="'+item.userID+'"></td></tr>');

    });
}

/**
 * populate product search dropdown meny
 */
function productSearchTemplate(data) {
    var $productTemplate = $('#productContainer');  // set element-id to populate
    $productTemplate.empty();
    // populate given element-id with rows with name and checkbox
    $.each(data.productInfo, function (i, item) {
        $productTemplate.append('<tr><td id="bordernone">' + item.productName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="product[]" value="'+item.productID+'"></td></tr>');

    });
}

/**
 * populate group search dropdown meny
 */
function groupSearchTemplate(data) {
    var $grouptTemplate = $('#groupContainer'); // set element-id to populate
    $grouptTemplate.empty();
    // populate given element-id with rows with name and checkbox
    $.each(data.groupInfo, function (i, item) {
        $grouptTemplate.append('<tr><td id="bordernone">' + item.groupName +'</td> <td id="bordernone"><input id="typeContainer" type="checkbox" name="group[]" value="'+item.groupID+'"></td></tr>');

    });
}

/**
 * Get result from advance search
 */
$(function POSTadvanceLoggSearch() {
    // run function if advance logg search is submitted
    $('#advanceLoggSearch').submit(function () {
        var url = $(this).attr('action');   // get for action
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                displayLoggTable(data);     // display result in logg table
            }
        });
        return false;
    });
});

// set start date of datepicker
$('.datepicker').datepicker({
    startDate: '-3d'
});

// configure behavior of datepicker
$(function() {
  $('input[name="datefilter"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });

  $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});