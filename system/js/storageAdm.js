
$('#dropdown').show();      // display administration meny
$('a#saveToCSV').hide();    // hide save to csv button
$(function () {
    $.ajax({
        type: 'GET',
        url: '?request=getAllStorageInfo',  // request given to controller
        dataType: 'json',
        success: function (data) {
            storageTableTemplate(data);
        }
    });
});


/**
 * Update storage table
 */
function UpdateStorageTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllStorageInfo',  // request given to controller
            dataType: 'json',
            success: function (data) {
                storageTableTemplate(data);
            }
        });
    });
}


/**
 * Display storage tabel
 * takes given data and poplate template
 */
function storageTableTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("displayStorageContainer");
    storageContainer.innerHTML = storageTableGeneratedHTML;
}



/**
 * Get information about storage to be deleted
 */
$(function POSTdeleteStorageModal() {
    //check if delete button inside displayStorageContainer is clicked
    $('#displayStorageContainer').delegate('.delete', 'click', function () {
        var givenStorageID = $(this).attr('data-id');   // get data-id from button
        var $displayUsers = $('#errorMessage'); 
        $displayUsers.empty();  // empyt error message

        $.ajax({
            type: 'POST',
            url: '?request=getStorageByID',     // request given to controller
            data: {givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {
                deleteStorageTemplate(data);    // show delete storage info
                $('#deleteStorageModal').modal('show');     // show delete storage modal
            }
        });
        return false;

    });
});


/**
 * Display info about storage to be deleted
 * takes given data and poplate template
 */
function deleteStorageTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteStorageGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("deleteStorageContainer");
    storageContainer.innerHTML = deleteStorageGeneratedHTML;
}


/**
 * Delete selected storage
 */
$(function deleteStorageByID() {
    // run if delete storage fomr is submitted
    $('#deleteStorage').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data
        var $displayUsers = $('#errorMessage'); 
        $displayUsers.empty();  // empty error message

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();   // display error massage
            },
            success: function (data) {
                successMessageDelete();     // display success message
                UpdateStorageTable();       // udapte storage tabel
                $('#deleteStorageModal').modal('hide');     // hide delete storage modal
            }
        });
        return false;
    });
});


/**
 * Display success message on delete storage
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Lageret er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display error message on delete storage
 */
function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slette dette lageret. </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


$(function stocktakingModal() {
     //check if update button inside displayStorageContainer is clicked   
    $('#displayStorageContainer').delegate('.update', 'click', function () {
        var givenStorageID = $(this).attr('data-id');   // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getStorageProduct',      // request given to controller
            data: {givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {  
                $('#stocktakingModal').modal('show');   // show stocktaking modal
                stocktakingTemplate(data);      // show products to stocktake
            }
        });
        return false;
    });
});


/**
 * Display products to be stocktaked
 * takes given data and poplate template
 */
function stocktakingTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("stocktakingTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stocktakingStorageGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("stocktakingContainer");
    storageContainer.innerHTML = stocktakingStorageGeneratedHTML;
}

/**
 * Get result from stocktacking, or save result
 */
$(function getStocktakingResult() {
    // run if stocktaking form is submitted
    $('#stocktaking').submit(function () {
        var url = $(this).attr('action');   // get form action  
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                // check if button value is "lagre"
                if (document.getElementById("saveStocktaking").value === "Lagre") {
                    var $displayUsers = $('#stocktakingResultContainer');
                    $displayUsers.empty();  // empty stocktaking result
                    // change button value to "neste"
                    document.getElementById("saveStocktaking").value = "Neste";
                    $('#stocktakingModal').modal('hide');
                    successMessageStock();  // displau success message
                } else {
                    var $displayUsers = $('#stocktakingContainer');
                    $displayUsers.empty();  // empty stocktaking container
                    $('a#saveToCSV').show();    // show save to CSV button
                    document.getElementById("saveStocktaking").value = "Lagre"; // get value to "lagre"
                    stocktakingResultTemplate(data);    // get stocktaking result
                    rowColor();     // format tabel to correct row color
                    stocktakingResultChart(data);   // create chart form stocktaking result
                }
            }
        });
        return false;
    });
});


/**
 * Display success message on stocktaking is updated
 */
function successMessageStock() {
    $('<div class="alert alert-success"><strong>Oppdatert!</strong> Lagerbeholdning er oppdatert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


var resultBar;
function stocktakingResultChart(data){

    var ctx = document.getElementById('stocktakingResultChart').getContext('2d');
// create new array to contain data for chart
    var product = [];
    var antall = [];
    var farge = [];
    // populate array
    $.each(data.differanceArray, function (i, item) {
        product.push(item.productName);
        antall.push(item.differance);
    });

    var bars = antall;
    // populate color array
    for (i = 0; i < bars.length; i++) {
        // chose color of value based on quantity
        if (bars[i] >= 10 || bars[i] <= -10){
            //green
            farge.push("#d9534f");
        } else if (bars[i] < 10 && bars[i] >= 5 || bars[i] > -10 && bars[i] <= -5){
            //orange
            farge.push("#f0ad4e");
        } else if (bars[i] < 5 || bars[i] > -5){
            //red
            farge.push("#5cb85c");
        }
    }
     // generate chart from created array info
    window.resultBar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: product,
            datasets: [{
                    label: "Antall",
                    borderColor: "black",
                    backgroundColor: farge,
                    borderWidth: 1,
                    data: antall
                }
            ]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        },
        responsive: true

    });
}




/**
 * Display stocktaking result
 * takes given data and poplate template
 */
function stocktakingResultTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("stocktakingResultTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stocktakingStorageGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("stocktakingResultContainer");
    storageContainer.innerHTML = stocktakingStorageGeneratedHTML;
}

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
            window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv")
        } else {
            $(this).attr({'download': filename, 'href': csvData, 'target': '_blank'});
        }
    }

    // This must be a hyperlink
    $("#saveToCSV").on('click', function (event) {

        exportTableToCSV.apply(this, [$('#stocktakingResultContainer'), 'varetelling.csv']);

        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });

});

// run when stocktaking modal is closed
$(document).ready(function (){
    $('#stocktakingModal').on('hidden.bs.modal', function (e){
        if (resultBar){  // destroy chart
            resultBar.destroy();
        }
        $('#stocktakingResultContainer').empty();  // empty stocktaking result container 
        $('#stocktakingContainer').empty();     // empty stocktaking container
        $('#stocktakingResultChart').empty();       // empty stocktaking result chart
        document.getElementById("saveStocktaking").value = "Neste"; // change button value to next
        $('a#saveToCSV').hide();    // hide save to csv button
    });
});

/**
 * get storage information
 */
$(function getStorageInformation() {
    //check if information button inside displayStorageContainer is clicked      
    $('#displayStorageContainer').delegate('.information', 'click', function () {
        var givenStorageID = $(this).attr('data-id');   // get data-id from button
        POSTstorageRestriction(givenStorageID);     // get user with restriction to selected storage
        POSTstorageProduct(givenStorageID);         // get products within the selected storage
        chartInventory(givenStorageID);             // create chart from inventory 
        POSTgroupRestriction(givenStorageID);       // get group with restriction to selected storage

        $.ajax({
            type: 'POST',
            url: '?request=getStorageByID',     // request given to controller
            data: {givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {
                document.getElementById('macAdresser').hide;        // hide mac addresse area
                $('#showStorageInformationModal').modal('show');        // show storage information modal
                StorageInformationTemplate(data);       // display information about selected storage
                negativeSupportStatus(data.storage[0].negativeSupport); // check if storage support negative inventory stauts
            }
        });
        return false;
    });
});


/**
 * get storage invetory to create chart
 */
function chartInventory(data) {
    var givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',   
            url: '?request=chartProduct',   // request given to controller
            data: {givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {
                drawChart(data);        // create chart
            }
        });
    });
}

var myObjBar;
function drawChart(data)
{
    if (myObjBar){
        myObjBar.destroy(); // if chart already exist, destroy it
    }

    var ctx = document.getElementById('myChart').getContext('2d');
     // create new array to contain data for chart   
    var product = [];
    var antall = [];
    var farge = [];
    // populate array
    $.each(data, function (i, item) {
        product.push(item.productName);
        antall.push(item.quantity);
    });

    var bars = antall;
    // populate color array
    for (i = 0; i < bars.length; i++) {
        // chose color of value based on quantity
        if (bars[i] >= 10){
            //green
            farge.push("#5cb85c");
        } else if (bars[i] < 10 && bars[i] >= 5){
            //orange
            farge.push("#f0ad4e");
        } else if (bars[i] < 5){
            //red
            farge.push("#d9534f");
        }
    }
     // generate chart from created array info
    window.myObjBar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: product,
            datasets: [{
                    label: "Antall",
                    borderColor: "black",
                    backgroundColor: farge,
                    borderWidth: 1,
                    data: antall
                }
            ]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        },
        responsive: true

    });
}

/**
 * Display information about selected storage
 * takes given data and poplate template
 */
function StorageInformationTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageInformationGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("storageInformationContainer");
    storageContainer.innerHTML = storageInformationGeneratedHTML;
}


/**
 * Get user restrictions of selected storage
 */
var givenStorageID;
function POSTstorageRestriction(data) {
    givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getStorageRestriction',      // request given to controller
            data: {givenStorageID: givenStorageID},     // posted data
            dataType: 'json',
            success: function (data) {
                storageRestrictionTemplate(data);      // display users with restriction
            }
        });
    });
}

/**
 * Get group with restricion to selected storage
 */
function POSTgroupRestriction(data) {
    givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupRestrictionFromSto',     // request given to controller
            data: {givenStorageID: givenStorageID},     // post data
            dataType: 'json',
            success: function (data) {
                groupRestrictionTemplate(data);     // display group with restriction
            }
        });
    });
}

/**
 * Delete selected group restriction
 */
$(function DeleteGroupRestriction() {
    //check if deleteGroupRestriction button inside groupRestrictionContainer is clicked 
    $('#groupRestrictionContainer').delegate('.deleteGroupRestriction', 'click', function () {
        var restrictionID = $(this).attr('data-id');    // get data-id from button
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupRestriction',     // request given to controller
            data: {restrictionID: restrictionID},       // post data
            dataType: 'json',
            success: function (data) {
                POSTgroupRestriction(givenStorageID);   // refresh group restriction tabel
            }
        });
        return false;
    });
});

/**
 * Display group with restriction to selected storage
 * takes given data and poplate template
 */
function groupRestrictionTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("groupRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("groupRestrictionContainer");
    storageContainer.innerHTML = storageRestrictionGeneratedHTML;
}


/**
 * delete selected user restriction 
 */
$(function deleteUserRestriction() {
    //check if deleteUserRestriction button inside storageRestrictionContainer is clicked 
    $('#storageRestrictionContainer').delegate('.deleteUserRestriction', 'click', function () {
        var givenUserID = $(this).attr('data-id');  // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=deleteSingleRes',        // request given to controller
            data: {givenUserID: givenUserID, givenStorageID: givenStorageID},   // post data
            dataType: 'json',
            success: function () {
                POSTstorageRestriction(givenStorageID); // refresh user restriction tabel
                successMessageRes();    // display success message
            }
        });
        return false;
    });
});

/**
 * Displauy success message on user restriction deletion
 */
function successMessageRes() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Brukertilgang er slettet. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display user with restriction to selected storage
 * takes given data and poplate template
 */
function storageRestrictionTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("storageRestrictionContainer");
    storageContainer.innerHTML = storageRestrictionGeneratedHTML;
}

/**
 * Get storage inventory in selected storage
 */
var givenStorageID;
function POSTstorageProduct(data) {
    givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getStorageProduct',      // request given to controller
            data: {givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {
                storageProductTemplate(data);   // display product within storage
                rowColor();     // format tabel row to correct color 

            }
        });
    });
}

/**
 * Display mac adresse registered in storage of selceted product
 */
$(function showInventoryMac() {
    //check if showMac button inside storageProductContainer is clicked
    $('#storageProductContainer').delegate('.showMac', 'click', function () {
        var givenProductID = $(this).attr('data-id');   // get data-id from button
       
        $.ajax({
            type: 'POST',
            url: '?request=getInventoryMac',        // request given to controller
            data: {givenProductID: givenProductID, givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function (data) {
                showProductMacTemplate(data);   // display mac adresses
            }
        });
        return false;
    });
});

/**
 * Remove products from storage
 */
$(function deleteStorageInventory() {
    //check if deleteStorageInventory button inside storageProductContainer is clicked
    $('#storageProductContainer').delegate('.deleteStorageInventory', 'click', function () {
        var givenProductID = $(this).attr('data-id');   // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=deleteSingleProd',       // request given to controller
            data: {givenProductID: givenProductID, givenStorageID: givenStorageID}, // post data
            dataType: 'json',
            success: function () {
                POSTstorageProduct(givenStorageID); // update product tabel
                successMessageInv();        // display success message
            }
        });
        return false;

    });
});

/**
 * display success message on inventory delete
 */
function successMessageInv() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Inventar er slettet fra lager. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Display products within selected storage
 * takes given data and poplate template
 */
function storageProductTemplate(data) {
    // handlebars helper to use equal parameter
    Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b) 
        return opts.fn(this);
    else
        return opts.inverse(this);
    });
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("storageProductContainer");
    storageContainer.innerHTML = storageProductGeneratedHTML;
}



function rowColor() {

// color for storage inventory table
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) >= 10;   // if larger than 10, row is green
    }).siblings().andSelf().attr('class', 'bg-success');

    $('.quantityColor').filter(function (index) {    // if between 10 and 5. row is orange
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5;
    }).siblings().andSelf().attr('class', 'bg-warning');

    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) < 5;    // if less than 5, row is red
    }).siblings().andSelf().attr('class', 'bg-danger');


// stocktaking result
// ||

    $('.stockResult').filter(function (index) { // if larger than 10, or less than - 10, row is red
        return parseInt(this.innerHTML) >= 10 || parseInt(this.innerHTML) <= -10;
    }).siblings().andSelf().attr('class', 'bg-danger');

    $('.stockResult').filter(function (index) { // if between 10 and 5, og between -10 and -5, row is orange
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5 || parseInt(this.innerHTML) > -10 && parseInt(this.innerHTML) <= -5;
    }).siblings().andSelf().attr('class', 'bg-warning');

    $('.stockResult').filter(function (index) { // if between -5 and 5, row is green
        return parseInt(this.innerHTML) < 5 || parseInt(this.innerHTML) > -5;
    }).siblings().andSelf().attr('class', 'bg-success');

}




/**
 * Get selected storag info and open edit storage modal
 */
$(function POSTeditStorageModal() {
    //check if edit button inside displayStorageContainer is clicked
    $('#displayStorageContainer').delegate('.edit', 'click', function () {
        var givenStorageID = $(this).attr('data-id');   // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getStorageByID',     // request given to controller
            data: {givenStorageID: givenStorageID},     // post data
            dataType: 'json',
            success: function (data) {
                editStorageTemplate(data);  // display edit storage info
                $('#editStorageModal').modal('show');   // open edit storage modal
                updateCheckbox(data.storage[0].negativeSupport);   
            }
        });
        return false;
    });
});


/**
 * Display edit storage information
 * takes given data and poplate template
 */
function editStorageTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editStorageGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("editStorageContainer");
    storageContainer.innerHTML = editStorageGeneratedHTML;
}


/**
 * save edited storage information
 */
$(function POSTeditStorageInfo() {
    // run if edit storage form is submitted
    $('#editStorage').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit(); // display error message
            },
            success: function () {
                $('#editStorageModal').modal('hide');   // hide edit storage modal
                successMessageEdit();       // display success message
                UpdateStorageTable();       // update storage table
            }
        });
        return false;
    });
});


/**
 * Display success message on edit
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Lager er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 *  Display error message on edit 
 */
function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt lagernavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Create a new storage
 */
$(function POSTstorageInfo() {
    // run if form is submitted
    $('#createStorage').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize(); // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessage();     // display error message
            },
            success: function () {
                $("#createStorage")[0].reset();     // reset form
                $('#createStorageModal').modal('hide'); // hide create storage modal
                UpdateStorageTable();       // update storage table
                successMessageCreate();     // display success message
            }
        });
        return false;
    });
});

/**
 * Display error message on storage creation
 */
function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt lagernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display success message on storage creation
 */
function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Lager er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Search for storage
 */
$(function POSTsearchForStorage() {
    // run if form is submitted
    $('#searchForStorage').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForStorage")[0].reset();  // reset search form
                storageTableTemplate(data);     // display search result
            }
        });
        return false;
    });
});

/**
 * Show if storage support negative inventory status. 1 = yes, 0 = no
 */
function negativeSupportStatus(data) {
    if (data > 0) {
        $('.negativeSupportStatus').append('Ja');
    } else {
        $('.negativeSupportStatus').append('Nei');
    }
}

/**
 * marke off checkbox if storage support negative inventory, on editing
 */
function updateCheckbox(data){
   if (data > 0) {
        $("#editNegativeSupport").prop('checked', true);
    } 
}

/**
 * Get all products within the system
 */
function getStorageProduct() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllProductInfo',  // request passed to controller
        dataType: 'json',
        success: function (data) {
            stockDeliveryTemplate(data);    // pass array to stock delivery template
        }
    });
    return false;
}

/**
 * Display products for stock delivery
 */
function stockDeliveryTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("stockDeliveryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deliverytGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag    
    var deliveryContainer = document.getElementById("stockDeliveryContainer");
    deliveryContainer.innerHTML = deliverytGeneratedHTML;
}

/**
 * Selects a product and make it possible to select quantity
 */
$(function POSTselectedProduct() {
    //check if product button inside stockDeliveryContainer is clicked
    $('#stockDeliveryContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        if ($('#' + givenProductID).length) // check if this product allready are selected
        {
            return false;
        } else {
                // if it is not selected, post to get info from selected product
            $.ajax({
                type: 'POST',
                url: '?request=getProductByID',     // request given to controller
                data: {givenProductID: givenProductID}, //data posted to controller
                dataType: 'json',
                success: function (data) {
                    deliveryQuantityTemplate(data); // pass array of data to delivery quantity template
                }
            });
            return false;
        }
    });
});


/**
 * Display categories in use
 */
$( function getUsedStorageCat() {
    var givenStorageID = '2';
    $.ajax({
            type: 'POST',
            url: '?request=getCatWithProd',     // request given to controller
            data: {givenStorageID: givenStorageID}, // posted data
            dataType: 'json',
            success: function (data) {
                chooseCategory(data);   // display 
            }
        });
    return false;
});

/**
 * Display category to choose from
 * takes given data and poplate template
 */
function chooseCategory(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
        // display template in choosen ID tag
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}

/**
 * display product based on selected category
 */
$(function updateResultFromCategory() {
    // check if user change option in drop down meny
    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');   // get ID from selected option

        $.ajax({
            type: 'POST',
            url: '?request=getProductFromCategory',     // request passed to controller
            data: {givenCategoryID: givenCategoryID},   // posted data to controler
            dataType: 'json',
            success: function (data) {
                stockDeliveryTemplate(data);    // update result based on category selection
            }
        });
        return false;
    });
});

/**
 * Display selected product to deliver
 * takes given data and poplate template
 */
function deliveryQuantityTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deliveryQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
        // display template in choosen ID tag
    var transferContainer = document.getElementById("deliveryQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;
}

/**
 * register stockdelivery
 */
$(function registerStockDelivery() {
    // run if stockdelivery form is submitted
    $('#stockDelivery').submit(function () {
        var url = $(this).attr('action');    // get form action 
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayUsers = $('#errorMessage');     // display error message
                $displayUsers.empty().append("Kunne ikke overføre");
            },
            success: function (data) {
                $('#deliveryQuantityContainer').empty();    // empty selected products
                $('#stockDeliveryModal').modal('hide');     // hide stockdelivery modal
                successMessagedelivery();           // display success message
            }
        });
        return false;
    });
});

/**
 * Remove a selected product
 */
$(function removeSelectedProduct() {
    //check if remove button inside deliveryQuantityContainer is clicked
    $('#deliveryQuantityContainer').delegate('.remove', 'click', function () {
        var productID = $(this).attr('data-id');    // get data-id from product
        var $element = $('#' + productID);  // removes selected product with fadeout
        $element.fadeOut(150, function () {
            $(this).remove();
        });
        // remove field for regiser macadresse
        var $tr = $(this).closest('tr');
        $tr.fadeOut(150, function () {
            $(this).remove();
        });
    });
});

/**
 * Display success message on stock delivery
 */
function successMessagedelivery() {
    $('<div class="alert alert-success"><strong>Levert!</strong> Vareleveringen er registrert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Check if product use mac adresse
 */
$(function getNumberOfMac() {
    $('#deliveryQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();   // get value posted in quantity field
        var productID = $(this).attr('id'); // get product id from button-id
        var macadresse = $(this).attr('data-id');    // get mac adresse support from data-id
        if (macadresse > 0) {   // if number in quantity field is larger than 0, create new mac fields
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty();
            // create a mac adresse field equal to given quantity
            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><td><td><td><input id="mac'+i+productID+'" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="deliveryMacadresse[]" form="stockDelivery" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></td></td></td></tr>');
            }
        } else {return false;}
    });
});

/**
 * Format to macadresse, 00:11:22:33:44:55
 */
// based on example code from stackoverflow:
// https://stackoverflow.com/questions/16168125/auto-insert-colon-while-entering-mac-address-after-each-2-digit
$(function getMacadrInput() {
    var length=1;
    // check if a number is passed in
    $('#deliveryQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');    // get ID from field
        content=$(this).val();      // check value input field
        content1 = content.replace(/\:/g, '');      //insert :
        length=content1.length;     //check lengt of passed inn value
        if(((length % 2) === 0) && length < 12 && length > 1){
            $('#'+id).val($('#'+id).val() + ':');
            }
    });
});

/**
 * Remove chart to display mac adress instead
 */
function showMac(){
    document.getElementById('myChart').style.display = 'none';
    document.getElementById('macAdresser').style.display = 'block';
    
}


/**
 * Display mac adresses of selected product
 * takes given data and poplate template
 */
function showProductMacTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("showProductMacTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("showProductMacContainer");
    storageContainer.innerHTML = storageRestrictionGeneratedHTML;
}

// hide mac adress field when closing modal
$(document).ready(function (){
    $('#showStorageInformationModal').on('hidden.bs.modal', function (e){
        $('#macAdresser').hide();
    });
});