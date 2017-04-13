



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
});


function updateLogTable() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
}



function displayLoggTable(data) {
    var rawTemplate = document.getElementById("loggTableTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);

    var loggContainer = document.getElementById("loggTableContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}





$(function POSTsearchForSale() {

    $('#searchForLog').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForLog")[0].reset();
                displayLoggTable(data);
            }
        });
        return false;
    });
});




$(function POSTloggCheck() {

    $('#loggCheck').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {

            },
            success: function (data) {

            }
        });
        return false;
    });
});





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

$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLoggCheckStatus',
        dataType: 'json',
        success: function (data) {
            updateCheckbox(data);
        }
    });
});

function updateCheckbox(data) {
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

