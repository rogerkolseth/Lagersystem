
$('a#saveToCSV').hide();    // hides button to save to .CSV file

/**
 * gets all product information
 */
function getStorageProduct() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllProductInfo',  // pass request to controller
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
                url: '?request=getProductByID', // given request to controller
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


/*
 * Get passed in value, and check if product use mac adresse, 
 * If yes, create a mac adresse field 
 */
$(function getNumberOfMac() {
    //run function if a number is passed in field
    $('#deliveryQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();   // get value of field
        var productID = $(this).attr('id'); //get selected productID
        var macadresse = $(this).attr('data-id');   // check if product use mac, 0 = no, 1 = yes
        if (macadresse > 0) {   //run if product use mac
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty(); // empty selected element

            for (i = 0; i < quantity; i++) {    // create new mac input field
                $displayMacadresse.append('<tr><td><td><td><td><input id="mac'+i+productID+'" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="deliveryMacadresse[]" form="stockDelivery" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></td></td></td></tr>');
            }
        } else {return false;}
    });
});


/**
 * Format field to mac adresse format: 00:11:22:33:44:55
 */
// based on example code from stackoverflow:
// https://stackoverflow.com/questions/16168125/auto-insert-colon-while-entering-mac-address-after-each-2-digit
$(function getMacadrInput() {
    var length=1;
    // check if a number is passed in 
    $('#deliveryQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');    // get ID from field
        content=$(this).val();  // check value input field
        content1 = content.replace(/\:/g, '');  //insert :
        length=content1.length; //check lengt of passed inn value
        if(((length % 2) === 0) && length < 12 && length > 1){
            $('#'+id).val($('#'+id).val() + ':');
            }
    });
});

/**
 * Display selected products on stock delivery
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
 *  Register stock delivery
 */
$(function registerStockDelivery() {
    // run function if stockDelivery form is submitted
    $('#stockDelivery').submit(function () {
        var url = $(this).attr('action');   //get form action
        var data = $(this).serialize(); // serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayUsers = $('#errorMessage');     //select element ID to append
                $displayUsers.empty().append("Kunne ikke overføre");    //append error message
            },
            success: function (data) {
                $('#deliveryQuantityContainer').empty();    // empty field for selected products
                $('#stockDeliveryModal').modal('hide');     // hide stock delivery modal
            }
        });
        return false;
    });
});



/**
 * removes selected product for stock delivery
 */
$(function removeSelectedProduct() {
    //check if delete button inside deliveryQuantityContainer is clicked
    $('#deliveryQuantityContainer').delegate('.remove', 'click', function () {
        var productID = $(this).attr('data-id');    //gets products data-id
        var $element = $('#' + productID);  // select elementID to remove       
        $element.fadeOut(150, function () { //remove product with fadeout
            $(this).remove();
        });

        var $tr = $(this).closest('tr');    // remove closest <tr> tag (mac adresse field)
        $tr.fadeOut(150, function () {      // remove with fadeout
            $(this).remove();
        });
    });
});


/**
 * Gets all storage information
 */
function getStorageInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllStorageInfo',  // given request to controller
        dataType: 'json',
        success: function (data) {
            selectStorageTemplate(data);    //pass array to select storage template
        }
    });
}



/**
 * Display storages in drop down meny Template 
 */
function selectStorageTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("selectStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var selectStorageGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("selectStorageContainer");
    storageContainer.innerHTML = selectStorageGeneratedHTML;
}


/**
 * Get the selected storage, and POST this to retrive inventory
 * Stocktaking
 */ 

var givenStorageID;
$(function getStorageInventory() {
    // check if user have changed option in drop down meny, run code if true
    $('#selectStorageContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');    // get ID of selected storage
        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?request=getStorageProduct',  // given request to controller
                data: {givenStorageID: givenStorageID}, // data to send controller
                dataType: 'json',
                success: function (data) {
                    stockTakingTemplate(data);  // pass recived array to stockTakingTemplate
                }
            });
        }
        return false;
    });
});





/**
 * Create new user
 */
$(function createUser() {
    // check if form is submitted
    $('#createUser').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayError = $('#errorMessage');     // select element to append to
                $displayError.empty().append("Brukernavn trolig i bruk");   // append error message
            },
            success: function () {
                $("#createUser")[0].reset();    // reset create user fomr
                $('#createUserModal').modal('hide');    // hide create user modal
                $('#errorMessage').remove();        // removes error message
            }
        });
        return false;
    });
});


/**
 * Create a new product
 */
$(function createProduct() {
    // run if crateProduct form is submitted
    $('#createProduct').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in fomr
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createProduct")[0].reset();     // reset create product form
                $('#createProductModal').modal('hide');     // hide create product modal
            }
        });
        return false;
    });
});


/**
 * Create a new storage
 */
$(function createStorage() {

    $('#createStorage').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in fomr
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createStorage")[0].reset();     // reset create storage form
                $('#createStorageModal').modal('hide');     //hide create storage modal
            }
        });
        return false;
    });
});


$(function createCategory() {

    $('#createCategory').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in fomr
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createCategory")[0].reset();    // reset create category
                $('#createCategoryModal').modal('hide');    // hide create category modal
            }
        });
        return false;
    });
});

/**
 * Get information needed to create new product
 */
function createProductInfo() {
    getMediaInfo();     // get media information
    getCategoryInfo();      // get category information
}

/**
 * Gets media info and populate dropdown meny for creating user and product
 */
function getMediaInfo() {
    // set element-id to be populated
    var $displayMediaInformationUser = $('#selectMediaIDuser'); 
    $displayMediaInformationUser.empty();
    var $displayMediaInformationPro = $('#selectMediaIDpro');
    $displayMediaInformationPro.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllMediaInfo',    // given request to controller
            dataType: 'json',
            success: function (data) {
                // for each mediaInfo in controller append a option to selected element-id
                $.each(data.mediaInfo, function (i, item) {
                    $displayMediaInformationPro.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                    $displayMediaInformationUser.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                });
            }
        });
    });
}

/**
 * Get category info and populate dropdown meny for createing media and product
 */
function getCategoryInfo() {
    // set element-id to be populated
    var $displayCategoryInformationMed = $('#selectCategoryMed');
    var $displayCategoryInformationPro = $('#selectCategoryPro');
    $displayCategoryInformationMed.empty();
    $displayCategoryInformationPro.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllCategoryInfo', // given request to controller
            dataType: 'json',
            success: function (data) {

                $.each(data.categoryInfo, function (i, item) {
                    // for each categoryInfo in controller append a option to selected element-id
                    $displayCategoryInformationMed.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                    $displayCategoryInformationPro.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                });
            }
        });
    });
}


/**
 * Empty deliveryQuantityContainer element on closing stockdelivery modal
 */
$(document).ready(function () {
    $('#stockDeliveryModal').on('hidden.bs.modal', function (e){
        $('#deliveryQuantityContainer').empty();
    });
});


/**
 * Get logged in users restriction, included group restrictions 
 */
$(function getUserAndGroupRes() {
    $.ajax({
        type: 'GET',
        url: '?request=getuserAndGroupRes',     // given request to controller
        dataType: 'json',
        success: function (data) {
            showHide(data);     // deside if user should se dropdown selection or not
            chooseStorageTemplate(data);  // populate dropdown selection of storages
            chooseStorageStocktakTemplate(data);    // populate dropdown selection of storages for stocktaking
            singleStorageTemplate(data);    // display info about storage
        }
    });
});




/**
 * If user only have restriction for one storage, display info about this
 */ 
function showHide(data) {
    var limit = 0;
    var storageID;
    // check how many storage restrictions user have
    for (var i = 0; i < data.transferRestriction.length; i++) {
        limit = limit + 1;
    }
    // if he has less than 2 restrictions hide storage selection dropdown and show info about that storage
    if (limit < 2) {
        $('#chooseStorage').hide();
        $('#singleStorageContainer').show();
        storageID = data.transferRestriction[0].storageID;
        displaySingeStorage(storageID);
    } else {
        // display drop down to select a storage
        $('#chooseStorage').show();
        $('#singleStorageContainer').hide();
    }
}

/**
 * Display info about given storage 
 */
function singleStorageTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("singleStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stoktakeRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var stocktakingContainer = document.getElementById("singleStorageContainer");
    stocktakingContainer.innerHTML = stoktakeRestrictionGeneratedHTML;
}

/**
 * Display storages in a drop down meny for stocktaking
 */
function chooseStorageStocktakTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("chooseStorageStocktakTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var stoktakeRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var stocktakingContainer = document.getElementById("chooseStorageStocktakContainer");
    stocktakingContainer.innerHTML = stoktakeRestrictionGeneratedHTML;
}


/**
 * Display storages in a drop down meny for getting storage inventory
 */
function chooseStorageTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("chooseStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("chooseStorageContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;
}



/**
 * Get the selected storage, and POST this to retrive inventory-- >
 */

$(function getSelectedStorageInventory() {
    // run if user change option in drop down meny
    $('#chooseStorageContainer').on('change', function () {
        var givenStorageID = $(this).find("option:selected").data('id');    //gets ID of selected storage
        getChartInvetnoryInfo(givenStorageID);  // pass selected storage ID to get chart info
        if (givenStorageID > 0) {   // check if a storage is selected 0 = "select a storage" option
            $.ajax({
                type: 'POST',
                url: '?request=getStorageProduct',  // given request to controller
                data: {givenStorageID: givenStorageID}, // passed data to controller
                dataType: 'json',
                success: function (data) {
                    chosenStorageTemplate(data);    //display inventory of selected storage
                    rowColor();     // format color of inventory table
                }
            });
        }
        return false;
    });
});

/**
 * Refresh inventory table (single storage)
 */
function refreshdisplaySingeStorage(givenStorageID) {
window.setInterval (function (){
    getChartInvetnoryInfo(givenStorageID);  //pass selected storage ID to get chart info
    if (givenStorageID > 0) {   // check if a storage is selected 0 = "select a storage" option
        $.ajax({
            type: 'POST',
            url: '?request=getStorageProduct',  //  given request to controller
            data: {givenStorageID: givenStorageID}, // passed data to controller
            dataType: 'json',
            success: function (data) {
                chosenStorageTemplate(data);    //display inventory of selected storage
                rowColor(); // format color of inventory table
            }
        });
    }
    return false;
    }, 120000);
}

/**
 * Get storage inventory when user only have restriction to one
 */
function displaySingeStorage(givenStorageID) {
    refreshdisplaySingeStorage(givenStorageID); // trigger refreshing
    getChartInvetnoryInfo(givenStorageID);      //pass selected storage ID to get chart info
    if (givenStorageID > 0) {    // check if a storage is selected 0 = "select a storage" option
        $.ajax({
            type: 'POST',
            url: '?request=getStorageProduct',  //  given request to controller
            data: {givenStorageID: givenStorageID}, // passed data to controller
            dataType: 'json',
            success: function (data) {
                chosenStorageTemplate(data);    //display inventory of selected storage
                rowColor();     // format color of inventory table
            }
        });
    }
    return false;
}


/**
 * Display inventory of given storage 
 */
function chosenStorageTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("chosenStorageTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("chosenStorageContainer");
    transferContainer.innerHTML = transferProductGeneratedHTML;
}


/**
 * Get chart storageInventory from selected storage
 */ 

function getChartInvetnoryInfo(data) {
    var givenStorageID = data;
    $(function () {

        $.ajax({
            type: 'POST',
            url: '?request=chartProduct',   // given request to controller  
            data: {givenStorageID: givenStorageID},  // passed data to controller   
            dataType: 'json',
            success: function (data) {
                drawChart(data);    // generate chart
            }
        });
    });
}

var myObjBar;
function drawChart(data){
    if (myObjBar){
        myObjBar.destroy(); // if chart already exist, destroy it
    }
    
    var ctx = document.getElementById('myChart').getContext('2d');  // get element-id to be populated
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


/**
 * Get latest Logg 
 */
$(function getLastLoggInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getLatestLoggInfo',  // given request to controller  
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data); // display logg table
        }
    });
});


/**
 * Display table with latest log info
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
 * Gets storage and product with low inventory status
 */
$(function getLowInventory() {
    $.ajax({
        type: 'GET',
        url: '?request=getLowInventory',    // given request to controller  
        dataType: 'json',
        success: function (data) {
            displayLowInvTable(data);   // display table with low inventory info
            rowColor();    // format color of inventory table
        }
    });
});



/**
 * Display low inventoyr table
 */
function displayLowInvTable(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("lowInvTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var loggContainer = document.getElementById("lowInvContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}


/**
 * Get logged in users latest sales
 */
$(function getLastSaleInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getLastSaleInfo',    // given request to controller
        dataType: 'json',
        success: function (data) {
            displayLastSaleTable(data); // pass array to display last sales
        }
    });
});


/**
 * Display latest sales table
 */
function displayLastSaleTable(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("lastSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var loggContainer = document.getElementById("lastSaleContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}



/**
 * Gets all the latest sales
 */
$(function getAllLastSale() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllLastSaleInfo', // given request to controller  
        dataType: 'json',
        success: function (data) {
            displayAllLastSaleTable(data);  // pass array to display latest sales
        }
    });
});


/**
 * Display all latest sales
 */
function displayAllLastSaleTable(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("allLastSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var loggTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var loggContainer = document.getElementById("allLastSaleContainer");
    loggContainer.innerHTML = loggTableGeneratedHTML;
}


/**
 * Format color of rows in tables
 */
function rowColor() {
    //Color for low inventory table
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) > 6;    // if more than 6, row is orange
    }).siblings().andSelf().attr('class', 'bg-warning');
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) <= 6;   // if 6 or less, row is red
    }).siblings().andSelf().attr('class', 'bg-danger');

    // color for stocktaking table
    $('.stockResult').filter(function (index) {
        return parseInt(this.innerHTML) >= 10 || parseInt(this.innerHTML) <= -10;   // if larger than 10, or less than - 10, row is red
    }).siblings().andSelf().attr('class', 'bg-danger');
    $('.stockResult').filter(function (index) { // if between 10 and 5, og between -10 and -5, row is orange
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5 || parseInt(this.innerHTML) > -10 && parseInt(this.innerHTML) <= -5;
    }).siblings().andSelf().attr('class', 'bg-warning');
    $('.stockResult').filter(function (index) { // if between -5 and 5, row is green
        return parseInt(this.innerHTML) < 5 || parseInt(this.innerHTML) > -5;
    }).siblings().andSelf().attr('class', 'bg-success');
    
    // color for storage inventory table
    $('.inventoryColor').filter(function (index) {
        return parseInt(this.innerHTML) >= 10;      // if larger than 10, row is green
    }).siblings().andSelf().attr('class', 'bg-success');
    $('.inventoryColor').filter(function (index) {
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5;  // if between 10 and 5. row is orange
    }).siblings().andSelf().attr('class', 'bg-warning');
    $('.inventoryColor').filter(function (index) {
        return parseInt(this.innerHTML) < 5 ;   // if less than 5, row is red
    }).siblings().andSelf().attr('class', 'bg-danger');
}


/**
 * Display information of stocktaking
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
 * Display result of stocktacking
 */
var resultBar;
function stocktakingResultChart(data){
    // generate chart of stocktaking result
    var ctx = document.getElementById('stocktakingResultChart').getContext('2d');
    var product = [];
    var antall = [];
    var farge = [];
    // populate array from given data
    $.each(data.differanceArray, function (i, item) {
        product.push(item.productName); 
        antall.push(item.differance);
    });
    var bars = antall;
    for (i = 0; i < bars.length; i++) {
        // chose color of value on differance
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
    // generate chart
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


/**
 * Display result from stocktaking
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
        if (resultBar){     // destroy chart
            resultBar.destroy();
        }
        $('#stocktakLabel').show(); // show stocktaking label
        $('#chooseStorageStocktakContainer').show();    // show choose storage stocktaking container
        $('#stocktakingResultContainer').empty();   // empty stocktaking result container
        $('#stocktakingContainer').empty();     // empty stocktaking container
        $('#stocktakingResultChart').empty();   // empty stocktaking result chart
        document.getElementById("saveStocktaking").value = "Neste";     // change button value to next
        $('a#saveToCSV').hide();    // hide save to csv button
        $('#chooseStorageStocktakContainer').prop('selectedIndex', 0);  // resett dropdown meny
    });
});
     
   
