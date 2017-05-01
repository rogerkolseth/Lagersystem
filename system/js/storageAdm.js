// DISPLAY STORAGE MAIN TABLE -->

// GET storageInformation -->


$('#dropdown').show();
$('a#saveToCSV').hide();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllStorageInfo',
        dataType: 'json',
        success: function (data) {
            storageTableTemplate(data);
        }
    });
});


// Update storage information -->

function UpdateStorageTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllStorageInfo',
            dataType: 'json',
            success: function (data) {
                storageTableTemplate(data);
            }
        });
    });
}


// Display storage template -->

function storageTableTemplate(data) {

    var rawTemplate = document.getElementById("displayStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageTableGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("displayStorageContainer");
    storageContainer.innerHTML = storageTableGeneratedHTML;
}






//   DELETE STORAGE     -->


// Delete storage modal -->

$(function POSTdeleteStorageModal() {

    $('#displayStorageContainer').delegate('.delete', 'click', function () {
        var givenStorageID = $(this).attr('data-id');
        var $displayUsers = $('#errorMessage');
        $displayUsers.empty();

        $.ajax({
            type: 'POST',
            url: '?page=getStorageByID',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                deleteStorageTemplate(data);
                $('#deleteStorageModal').modal('show');
            }
        });
        return false;

    });
});


// Delete storage template-->

function deleteStorageTemplate(data) {
    var rawTemplate = document.getElementById("deleteStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteStorageGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("deleteStorageContainer");
    storageContainer.innerHTML = deleteStorageGeneratedHTML;
}



// Delete the storage that is selected-->

$(function deleteStorageByID() {

    $('#deleteStorage').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        var $displayUsers = $('#errorMessage');
        $displayUsers.empty();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();
            },
            success: function (data) {
                successMessageDelete();
                UpdateStorageTable();
                $('#deleteStorageModal').modal('hide');

            }
        });
        return false;
    });
});



function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Lageret er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slette dette lageret. </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// STOCKTAKING OF STORAGE -->

// stocktaking modal -->

$(function POSTstocktakingModal() {

    $('#displayStorageContainer').delegate('.update', 'click', function () {
        var givenStorageID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getStorageProduct',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {

                $('#stocktakingModal').modal('show');
                stocktakingTemplate(data);

            }
        });
        return false;

    });
});


// stocktaking storage template-->

function stocktakingTemplate(data) {
    var rawTemplate = document.getElementById("stocktakingTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stocktakingStorageGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("stocktakingContainer");
    storageContainer.innerHTML = stocktakingStorageGeneratedHTML;
}


// POST results from stocktaking, and updating the table-->

$(function POSTstocktakingResult() {

    $('#stocktaking').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                if (document.getElementById("saveStocktaking").value === "Lagre") {
                    var $displayUsers = $('#stocktakingResultContainer');
                    $displayUsers.empty();
                    document.getElementById("saveStocktaking").value = "Neste";
                    $('#stocktakingModal').modal('hide');
                    successMessageStock();
                } else {
                    var $displayUsers = $('#stocktakingContainer');
                    $displayUsers.empty();
                    $('a#saveToCSV').show();
                    document.getElementById("saveStocktaking").value = "Lagre";
                    stocktakingResultTemplate(data);
                    rowColor();

                    stocktakingResultChart(data);


                }

            }
        });
        return false;
    });
});




function successMessageStock() {
    $('<div class="alert alert-success"><strong>Oppdatert!</strong> Lagerbeholdning er oppdatert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



var resultBar;
function stocktakingResultChart(data)
{




    var ctx = document.getElementById('stocktakingResultChart').getContext('2d');

    var product = [];
    var antall = [];
    var farge = [];

    $.each(data.differanceArray, function (i, item) {
        product.push(item.productName);
        antall.push(item.differance);
    });

    var bars = antall;
    for (i = 0; i < bars.length; i++) {
        //You can check for bars[i].value and put your conditions here
        if (bars[i] >= 10 || bars[i] <= -10)
        {
            //grønn
            farge.push("#d9534f");
        } else if (bars[i] < 10 && bars[i] >= 5 || bars[i] > -10 && bars[i] <= -5)
        {
            //orange
            farge.push("#f0ad4e");
        } else if (bars[i] < 5 || bars[i] > -5)
        {
            //rød
            farge.push("#5cb85c");
        }
    }

    window.resultBar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: product,
            datasets: [
                {
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




// stocktaking storage template-->

function stocktakingResultTemplate(data) {
    var rawTemplate = document.getElementById("stocktakingResultTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stocktakingStorageGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("stocktakingResultContainer");
    storageContainer.innerHTML = stocktakingStorageGeneratedHTML;
}


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


$(document).ready(function ()
{
    $('#stocktakingModal').on('hidden.bs.modal', function (e)
    {
        if (resultBar)
        {
            resultBar.destroy();
        }
        $('#stocktakingResultContainer').empty();
        $('#stocktakingContainer').empty();
        $('#stocktakingResultChart').empty();
        document.getElementById("saveStocktaking").value = "Neste";
        $('a#saveToCSV').hide();
    });
});

// SHOW STORAGE INFORMATION -->

// get information from selected storage-->

$(function POSTstorageInformationModal() {

    $('#displayStorageContainer').delegate('.information', 'click', function () {
        var givenStorageID = $(this).attr('data-id');
        POSTstorageRestriction(givenStorageID);
        POSTstorageProduct(givenStorageID);
        chartInventory(givenStorageID);


        $.ajax({
            type: 'POST',
            url: '?page=getStorageByID',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                $('#showStorageInformationModal').modal('show');
                StorageInformationTemplate(data);
                negativeSupportStatus(data.storage[0].negativeSupport);
            }
        });
        return false;

    });
});



// Get storageInventory from selected storage-->

function chartInventory(data) {

    var givenStorageID = data;

    $(function () {

        $.ajax({
            type: 'POST',
            url: '?page=chartProduct',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {


                drawChart(data);

            }
        });
    });
}
var myObjBar;

function drawChart(data)
{
    if (myObjBar)
    {
        myObjBar.destroy();
    }
    var ctx = document.getElementById('myChart').getContext('2d');


    var ctx = document.getElementById('myChart').getContext('2d');
    var product = [];
    var antall = [];
    var farge = [];



    $.each(data, function (i, item) {
        product.push(item.productName);
        antall.push(item.quantity);
    });


    var bars = antall;
    for (i = 0; i < bars.length; i++) {
        //You can check for bars[i].value and put your conditions here
        if (bars[i] >= 10)
        {
            //grønn
            farge.push("#5cb85c");
        } else if (bars[i] < 10 && bars[i] >= 5)
        {
            //orange
            farge.push("#f0ad4e");
        } else if (bars[i] < 5)
        {
            //rød
            farge.push("#d9534f");
        }
    }
    window.myObjBar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: product,
            datasets: [
                {
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











// Display storageInformation Template-->

function StorageInformationTemplate(data) {
    var rawTemplate = document.getElementById("storageInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageInformationGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("storageInformationContainer");
    storageContainer.innerHTML = storageInformationGeneratedHTML;
}


// Get restrictions from selected storage -->

var givenStorageID;
function POSTstorageRestriction(data) {
    givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?page=getStorageRestriction',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                storageRestrictionTemplate(data);
            }
        });
    });
}



$(function deleteUserRestriction() {
    $('#storageRestrictionContainer').delegate('.deleteUserRestriction', 'click', function () {

        var givenUserID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=deleteSingleRes',
            data: {givenUserID: givenUserID, givenStorageID: givenStorageID},
            dataType: 'json',
            success: function () {
                POSTstorageRestriction(givenStorageID);
                successMessageRes();
            }
        });
        return false;

    });
});




function successMessageRes() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Brukertilgang er slettet. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// Display restrictionInformation Template-->

function storageRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageRestrictionGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("storageRestrictionContainer");
    storageContainer.innerHTML = storageRestrictionGeneratedHTML;
}

// Get storageInventory from selected storage-->

var givenStorageID;
function POSTstorageProduct(data) {
    givenStorageID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?page=getStorageProduct',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                storageProductTemplate(data);
                rowColor();

            }
        });
    });
}



$(function deleteStorageInventory() {
    $('#storageProductContainer').delegate('.deleteStorageInventory', 'click', function () {

        var givenProductID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=deleteSingleProd',
            data: {givenProductID: givenProductID, givenStorageID: givenStorageID},
            dataType: 'json',
            success: function () {
                POSTstorageProduct(givenStorageID);
                successMessageInv();
            }
        });
        return false;

    });
});



function successMessageInv() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Inventar er slettet fra lager. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// Display productInformation Template -->

function storageProductTemplate(data) {
    var rawTemplate = document.getElementById("storageProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageProductGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("storageProductContainer");
    storageContainer.innerHTML = storageProductGeneratedHTML;
}



function rowColor() {

// storageInformation
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) >= 10;
    }).siblings().andSelf().attr('class', 'bg-success');

    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5;
    }).siblings().andSelf().attr('class', 'bg-warning');

    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) < 5;
    }).siblings().andSelf().attr('class', 'bg-danger');


// stocktaking result
// ||

    $('.stockResult').filter(function (index) {
        return parseInt(this.innerHTML) >= 10 || parseInt(this.innerHTML) <= -10;
    }).siblings().andSelf().attr('class', 'bg-danger');

    $('.stockResult').filter(function (index) {
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5 || parseInt(this.innerHTML) > -10 && parseInt(this.innerHTML) <= -5;
    }).siblings().andSelf().attr('class', 'bg-warning');

    $('.stockResult').filter(function (index) {
        return parseInt(this.innerHTML) < 5 || parseInt(this.innerHTML) > -5;
    }).siblings().andSelf().attr('class', 'bg-success');

}




// EDIT STORAGE -->

// Get the selected storage, and opens editStorage modal-->

$(function POSTeditStorageModal() {

    $('#displayStorageContainer').delegate('.edit', 'click', function () {
        var givenStorageID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getStorageByID',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                editStorageTemplate(data);
                $('#editStorageModal').modal('show');
                updateCheckbox(data.storage[0].negativeSupport);
            }
        });
        return false;

    });
});


// Display edit storage Template -->

function editStorageTemplate(data) {
    var rawTemplate = document.getElementById("editStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editStorageGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("editStorageContainer");
    storageContainer.innerHTML = editStorageGeneratedHTML;
}


// POST results from editing, and updating the table-->

$(function POSTeditStorageInfo() {

    $('#editStorage').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();
            },
            success: function () {
                $('#editStorageModal').modal('hide');
                successMessageEdit();
                UpdateStorageTable();
            }
        });
        return false;
    });
});




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Lager er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt lagernavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// CREATE STORAGE -->



$(function POSTstorageInfo() {

    $('#createStorage').submit(function () {

        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessage();
            },
            success: function () {
                $("#createStorage")[0].reset();
                $('#createStorageModal').modal('hide');
                UpdateStorageTable();
                successMessageCreate();
            }
        });
        return false;
    });
});




function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt lagernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Lager er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// SEARCH FOR STORAGE -->


$(function POSTsearchForStorage() {

    $('#searchForStorage').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForStorage")[0].reset();
                storageTableTemplate(data);

            }
        });
        return false;
    });
});

function negativeSupportStatus(data) {
    if (data > 0) {
        $('.negativeSupportStatus').append('Ja');
    } else {
        $('.negativeSupportStatus').append('Nei');
    }
}

function updateCheckbox(data){
   if (data > 0) {
        $("#editNegativeSupport").prop('checked', true);
    } 
}

function getStorageProduct() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllProductInfo',
        dataType: 'json',
        success: function (data) {
            stockDeliveryTemplate(data);
        }
    });
    return false;
}

//Display products in storage Template -- >

function stockDeliveryTemplate(data) {
    var rawTemplate = document.getElementById("stockDeliveryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deliverytGeneratedHTML = compiledTemplate(data);
    var deliveryContainer = document.getElementById("stockDeliveryContainer");
    deliveryContainer.innerHTML = deliverytGeneratedHTML;
}

$(function POSTselectedProduct() {

    $('#stockDeliveryContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        if ($('#' + givenProductID).length)
        {
            return false;
        } else {


            $.ajax({
                type: 'POST',
                url: '?page=getProductByID',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {
                    deliveryQuantityTemplate(data);
                }
            });
            return false;
        }
    });
});

function deliveryQuantityTemplate(data) {
    var rawTemplate = document.getElementById("deliveryQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    var transferContainer = document.getElementById("deliveryQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;
}

$(function POSTtransferProducts() {

    $('#stockDelivery').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayUsers = $('#errorMessage');
                $displayUsers.empty().append("Kunne ikke overføre");
            },
            success: function (data) {
                $('#deliveryQuantityContainer').empty();
                $('#stockDeliveryModal').modal('hide');
                successMessagedelivery();
            }
        });
        return false;
    });
});

// remove product -- >

$(function removeSelectedProduct() {

    $('#deliveryQuantityContainer').delegate('.remove', 'click', function () {
        var productID = $(this).attr('data-id');
        var $element = $('#' + productID);
        $element.fadeOut(150, function () {
            $(this).remove();
        });

        var $tr = $(this).closest('tr');
        $tr.fadeOut(150, function () {
            $(this).remove();
        });
    });
});

function successMessagedelivery() {
    $('<div class="alert alert-success"><strong>Levert!</strong> Vareleveringen er registrert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

// MAC ADRESSE UTTAK

$(function getNumberOfMac() {
    $('#deliveryQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();
        var productID = $(this).attr('id');
        var macadresse = $(this).attr('data-id');
        if (macadresse > 0) {
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty();


            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><input id="mac'+i+productID+'" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="deliveryMacadresse[]" form="stockDelivery" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></tr>');
            }
        } else {return false;}
    });
});


$(function getMacadrInput() {
    var length=1;
    $('#deliveryQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');
        content=$(this).val();
        content1 = content.replace(/\:/g, '');
        length=content1.length;
        if(((length % 2) === 0) && length < 12 && length > 1){
            $('#'+id).val($('#'+id).val() + ':');
            }
    });
});


