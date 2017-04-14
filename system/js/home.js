
$('a#saveToCSV').hide();
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


// Get productInfo from selected ID -- >

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
            }
        });
        return false;
    });
});


// remove product -- >

$(function POSTdeleteStorageModal() {

    $('#deliveryQuantityContainer').delegate('.remove', 'click', function () {
        var $tr = $(this).closest('tr');
        $tr.fadeOut(150, function () {
            $(this).remove();
        });
    });
});


// Get storage information  -- >

function getStorageInfo() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllStorageInfo',
        dataType: 'json',
        success: function (data) {
            selectStorageTemplate(data);
        }
    });
}


// Display storages in drop down meny Template -- >

function selectStorageTemplate(data) {

    var rawTemplate = document.getElementById("selectStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var selectStorageGeneratedHTML = compiledTemplate(data);
    var storageContainer = document.getElementById("selectStorageContainer");
    storageContainer.innerHTML = selectStorageGeneratedHTML;
}


// Get the selected storage, and POST this to retrive inventory-- >

var givenStorageID;
$(function POSTfromStorageModal() {

    $('#selectStorageContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');
        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?page=getStorageProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    stockTakingTemplate(data);
                }
            });
        }
        return false;
    });
});





//Create product -- >

$(function POSTuserInfo() {

    $('#createUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayError = $('#errorMessage');
                $displayError.empty().append("Brukernavn trolig i bruk");
            },
            success: function () {
                $("#createUser")[0].reset();
                $('#createUserModal').modal('hide');
                $('#errorMessage').remove();
            }
        });
        return false;
    });
});


// CREATE PRODUCT -- >

$(function POSTproductInfo() {

    $('#createProduct').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createProduct")[0].reset();
                $('#createProductModal').modal('hide');
            }
        });
        return false;
    });
});


// CREATE STORAGE -- >

$(function POSTstorageInfo() {

    $('#createStorage').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createStorage")[0].reset();
                $('#createStorageModal').modal('hide');
            }
        });
        return false;
    });
});



$(function POSTstorageInfo() {

    $('#createCategory').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createCategory")[0].reset();
                $('#createCategoryModal').modal('hide');
            }
        });
        return false;
    });
});





function createProductInfo() {
    getMediaInfo();
    getCategoryInfo();
}


function getMediaInfo() {

    var $displayMediaInformationUser = $('#selectMediaIDuser');
    $displayMediaInformationUser.empty();
    var $displayMediaInformationPro = $('#selectMediaIDpro');
    $displayMediaInformationPro.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.mediaInfo, function (i, item) {

                    $displayMediaInformationPro.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                    $displayMediaInformationUser.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                });
            }
        });
    });
}


function getCategoryInfo() {
    var $displayCategoryInformationMed = $('#selectCategoryMed');
    var $displayCategoryInformationPro = $('#selectCategoryPro');
    $displayCategoryInformationMed.empty();
    $displayCategoryInformationPro.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllCategoryInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.categoryInfo, function (i, item) {

                    $displayCategoryInformationMed.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                    $displayCategoryInformationPro.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                });
            }
        });
    });
}






$(document).ready(function ()
{
    $('#stockDeliveryModal').on('hidden.bs.modal', function (e)
    {
        $('#deliveryQuantityContainer').empty();
    });
});



// Get storage information with user restriction -- >

$(function () {

    $.ajax({
        type: 'GET',
        url: '?page=getTransferRestriction',
        dataType: 'json',
        success: function (data) {

            showHide(data);
            withdrawRestrictionTemplate(data);
            chooseStorageStocktakTemplate(data);
            singleStorageTemplate(data);
        }
    });
});


// Dersom der er kun 1 lager så vises dete-- >

function showHide(data) {
    var limit = 0;
    var storageID;
    for (var i = 0; i < data.transferRestriction.length; i++) {
        limit = limit + 1;
    }

    if (limit < 2) {
        $('#chooseStorage').hide();
        $('#singleStorageContainer').show();
        storageID = data.transferRestriction[0].storageID;
        displaySingeStorage(storageID);
    } else {
        $('#chooseStorage').show();
        $('#singleStorageContainer').hide();
    }
}


function singleStorageTemplate(data) {
    var rawTemplate = document.getElementById("singleStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stoktakeRestrictionGeneratedHTML = compiledTemplate(data);
    var stocktakingContainer = document.getElementById("singleStorageContainer");
    stocktakingContainer.innerHTML = stoktakeRestrictionGeneratedHTML;
}


//Display storages in drop down meny Template -- >

function chooseStorageStocktakTemplate(data) {
    var rawTemplate = document.getElementById("chooseStorageStocktakTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stoktakeRestrictionGeneratedHTML = compiledTemplate(data);
    var stocktakingContainer = document.getElementById("chooseStorageStocktakContainer");
    stocktakingContainer.innerHTML = stoktakeRestrictionGeneratedHTML;
}


//Display storages in drop down meny Template -- >

function withdrawRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("chooseStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferRestrictionGeneratedHTML = compiledTemplate(data);
    var transferContainer = document.getElementById("chooseStorageContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;
}



// Get the selected storage, and POST this to retrive inventory-- >

var givenStorageID;
$(function POSTfromStorageModals() {

    $('#chooseStorageContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');
        chartTest(givenStorageID);
        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?page=getStorageProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    chosenStorageTemplate(data);
                    rowColor();
                }
            });
        }

        return false;
    });
});


//Display inventory and chart if single storage-- >

function displaySingeStorage(givenStorageID) {

    chartTest(givenStorageID);
    if (givenStorageID > 0) {
        $.ajax({
            type: 'POST',
            url: '?page=getStorageProduct',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                chosenStorageTemplate(data);
                rowColor();
            }
        });
    }
    return false;
}



function chosenStorageTemplate(data) {
    var rawTemplate = document.getElementById("chosenStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    var transferContainer = document.getElementById("chosenStorageContainer");
    transferContainer.innerHTML = transferProductGeneratedHTML;
}


// Get storageInventory from selected storage-- >

function chartTest(data) {

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
                }]
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




$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLatestLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
});



function displayLoggTable(data) {
    var rawTemplate = document.getElementById("loggTableTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    var loggContainer = document.getElementById("loggTableContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLowInventory',
        dataType: 'json',
        success: function (data) {
            displayLowInvTable(data);
            rowColor();
        }
    });
});




function displayLowInvTable(data) {
    var rawTemplate = document.getElementById("lowInvTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    var loggContainer = document.getElementById("lowInvContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLastSaleInfo',
        dataType: 'json',
        success: function (data) {
            displayLastSaleTable(data);
        }
    });
});



function displayLastSaleTable(data) {
    var rawTemplate = document.getElementById("lastSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    var loggContainer = document.getElementById("lastSaleContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}




$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLastSaleInfo',
        dataType: 'json',
        success: function (data) {
            displayAllLastSaleTable(data);
        }
    });
});



function displayAllLastSaleTable(data) {
    var rawTemplate = document.getElementById("allLastSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    var loggContainer = document.getElementById("allLastSaleContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
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





Date.prototype.yyyymmdd = function () {
    var yyyy = this.getFullYear();
    var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
    var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
    return "".concat(yyyy).concat(mm).concat(dd);
};
var d = new Date();
document.getElementById("dateProd").value = d.yyyymmdd();
document.getElementById("date").value = d.yyyymmdd();





// STOCKTAKING OF STORAGE -- >
// stocktaking modal -- >

$(function POSTStocktakingModal() {
    $('#chooseStorageStocktakContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');
        if (givenStorageID > 0) {

            $.ajax({
                type: 'POST',
                url: '?page=getStorageProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    stocktakingTemplate(data);
                }
            });
        } else {
            $('.product').empty();
        }
        return false;
    });
});


//stocktaking storage template-- >

function stocktakingTemplate(data) {
    var rawTemplate = document.getElementById("stocktakingTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stocktakingStorageGeneratedHTML = compiledTemplate(data);
    var storageContainer = document.getElementById("stocktakingContainer");
    storageContainer.innerHTML = stocktakingStorageGeneratedHTML;
}


// POST results from stocktaking, and updating the table-- >

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
                    $('#stocktakLabel').show();
                    $('#chooseStorageStocktakContainer').show();
                } else {
                    $('#stocktakLabel').hide();
                    $('#chooseStorageStocktakContainer').hide();
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
                }]
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




// stocktaking storage template-- >

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
        $('#stocktakLabel').show();
        $('#chooseStorageStocktakContainer').show();
        $('#stocktakingResultContainer').empty();
        $('#stocktakingContainer').empty();
        $('#stocktakingResultChart').empty();
        document.getElementById("saveStocktaking").value = "Neste";
        $('a#saveToCSV').hide();
        $('#chooseStorageStocktakContainer').prop('selectedIndex', 0);
    });
});
     