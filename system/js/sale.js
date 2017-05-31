
/**
 * get storage restrictions of logged in users
 */
$(function getRestrictions() {
    $.ajax({
        type: 'GET',
        url: '?request=getuserAndGroupRes',     // request given to controller
        dataType: 'json',
        success: function (data) {
            showHide(data);         // show or hide storage selection
            withdrawRestrictionTemplate(data);  // show storages to select from
        }
    });
});


/**
 * display storage from restriction
 * takes given data and poplate template
 */
function withdrawRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("withdrawRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("withdrawrRestrictionContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;
}



/**
 * Get selected storage inventory
 */
var givenStorageID;
$(function POSTfromStorageModal() {
    // check if user have changed storage from dropdown
    $('#withdrawrRestrictionContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');    // get selecte storageID
        // run if storageID is larger than 0 , (0 = "select a storage") 
        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?request=getStorageProduct',  // request given to controller    
                data: {givenStorageID: givenStorageID},     // posted data to controller
                dataType: 'json',
                success: function (data) {
                    withdrawProductTemplate(data);      // show storage inventory
                    $('.selectQuantity').remove();      // hide quantity selection
                    $('#withdrawButton').hide();        // hide withdrawbutton
                    $('#commentContainer').hide();      // hide comment section
                    $('#chooseCategoryContainer').show();       // category selection
                    $('#chooseCategoryContainer').prop('selectedIndex', 0); // reset category meny
                    getUsedStorageCat(givenStorageID);  // get categories containing product within this storage
                }
            });
        } else {        // if "select a storage" is selected
            $('.product').remove();         // remove all products
            $('.selectQuantity').remove();  // remove quantity selection
            $('#commentContainer').hide();  // hide comment section
            $('#withdrawButton').hide();    // hide withdraw button
            $('#chooseCategoryContainer').hide();   // hide category selection
        }
        return false;
    });
});


/**
 * Get inventory of selected storage
 */
function displaySingleStorage(givenStorageID) {

    if (givenStorageID > 0) {
        $.ajax({
            type: 'POST',
            url: '?request=getStorageProduct',  // request given to controller
            data: {givenStorageID: givenStorageID}, // posted data to controller
            dataType: 'json',
            success: function (data) {
                withdrawProductTemplate(data);  // display products within the selected storage
            }
        });
    }
    return false;
}


/**
 * update from category selection
 */
$(function updateResultFromCategory() {
    // check if a category is selected
    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');   // get categoryID from selection

        $.ajax({
            type: 'POST',
            url: '?request=getStoProFromCat',       // request given to controller
            data: {givenCategoryID: givenCategoryID, givenStorageID: givenStorageID},   // posted data
            dataType: 'json',
            success: function (data) {
                withdrawProductTemplate(data);  // update storage inventory
            }
        });
        return false;
    });
});


/**
 * Display products within storage
 * takes given data and poplate template
 */
function withdrawProductTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("withdrawProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("withdrawProductContainer");
    transferContainer.innerHTML = transferProductGeneratedHTML;
}


/*
 * Get product info from selected product
 */
$(function POSTselectedProduct() {
    //check if product button inside withdrawProductContainer is clicked
    $('#withdrawProductContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        if ($('#' + givenProductID).length)     // check if product is allready selected
        {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: '?request=getProdQuantity',    // request given to controller
                data: {givenProductID: givenProductID, givenStorageID: givenStorageID}, // posted data
                dataType: 'json',
                success: function (data) {
                    withdrawQuantityTemplate(data);     //display quantity selection
                    $('#commentContainer').show();      // display comment section
                    $('#withdrawButton').show();        // display withdraw button
                    negativeSupportStatus(data);        // check if storage can have nagative inventory
                }
            });
            return false;
        }
    });
});


/**
 * Display selected products 
 * takes given data and poplate template
 */
function withdrawQuantityTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("withdrawQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("withdrawQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;

}



/**
 * Transfer selected products
 */
$(function POSTtransferProducts() {
    // run if form is submitted
    $('#withdrawProducts').submit(function () {
        var url = $(this).attr('action');   // Get form action
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
                if (data === "success") {   
                    $('.product').remove();     // remove products
                    $('.selectQuantity').remove();  // remove quantity selection
                    $('#errorMessage').remove();        // remove error message
                    $("#withdrawProducts")[0].reset();      // reset form
                    successMessage();       // display success message
                    updateSale();       // update sale 
                    sendEmail();        // check if email warning must be sent
                } else {
                    missingMacError(data);  // error message if given mac dont exist
                }
            }
        });
        return false;
    });
});

/**
 * Display error message if mac dont exist
 */
function missingMacError(data) {
    $('<div class="alert alert-danger"><strong>Error!</strong> Finner ikke mac: <strong>' + data + ' </strong> </div>').appendTo('#error')
            .delay(10000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display success message on sale
 */
function successMessage() {
    $('<div class="alert alert-success"><strong>Registrert!</strong> Ditt uttak er registrert </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Update sale section
 */
function updateSale() {
    $('#withdrawButton').hide();// hides transferbutton 
    $('#commentContainer').hide();  // hide comment section
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getTransferRestriction',     // request given to controller
            dataType: 'json',
            success: function (data) {
                withdrawRestrictionTemplate(data);  // get users restriction
            }
        });
    });
}


/**
 * Remove a selected product
 */
$(function removeSelectedProduct() {
    //check if remove button inside returnQuantityContainer is clicked
    $('#withdrawQuantityContainer').delegate('.remove', 'click', function () {
        var productID = $(this).attr('data-id');    // get data-id from product
        var $element = $('#' + productID);      // removes selected product with fadeout
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
 * Cet categories containing a product
 */
function getUsedStorageCat(givenStorageID) {
    $.ajax({
        type: 'POST',   
        url: '?request=getCatWithProdAndSto',   // request given to controller
        data: {givenStorageID: givenStorageID}, // data posted to controller
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);   // display category result
        }
    });
    return false;
}

/**
 * Check if email warning about inventory status must be sent
 */
function sendEmail() {
    $.ajax({
        type: 'GET',
        url: '?request=sendInventarWarning',    // request given to controller
        dataType: 'json',
        success: function () {

        }
    });
}

/**
 * Display categories
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
 * Show storage selection depending on user restriction
 */
function showHide(data) {
    var limit = 0;
    // check how many restriction the user have
    for (var i = 0; i < data.transferRestriction.length; i++) {
        limit = limit + 1;
    }
    // if user have less than 2 restrictions, dont show drop down meny
    if (limit < 2) {
        $('#chooseStorage').hide();
        $('#singleStorageContainer').show();
        var storageID = data.transferRestriction[0].storageID;
        displaySingleStorage(storageID);
        getUsedStorageCat(storageID);
        // display the storage user have restriction to
        $('#singleStorageContainer').append('<p>' + data.transferRestriction[0].storageName + '</p>');
        $('#singleStorageContainer').append('<input name="fromStorageID" data-id="' + storageID + '" value="' + storageID + '" type="hidden"/>');
        $('#chooseCategoryContainer').show();

    } else {
        // show storage dropdown selection
        $('#chooseStorage').show();
        $('#singleStorageContainer').hide();
        $('#chooseCategoryContainer').hide();
    }
}

/**
 * check if storage can support negative inventory status
 */
function negativeSupportStatus(data) {
    // if 0 (no negative support), set max value of quantity equals product quantity
    if (data.negativeSupport[0].negativeSupport < 1) {
        $('#'+data.prodInfo[0].productID).find('.negativeSupport').attr({
            "max": data.prodInfo[0].quantity, 
        });
    }
}

/**
 * Check if product use mac adresse
 */
$(function getNumberOfMac() {
    $('#withdrawQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();    // get value posted in quantity field
        var productID = $(this).attr('id');     // get product id from button-id
        var macadresse = $(this).attr('data-id');   // get mac adresse support from data-id
        if (macadresse > 0) {   // if number in quantity field is larger than 0, create new mac fields
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty();
            // create a mac adresse field equal to given quantity
            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><td><td><td><input id="mac' + i + productID + '" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="withdrawMacadresse[]" form="withdrawProducts" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></td></td></td></tr>');
            }
        } else {
            return false;
        }
    });
});

/**
 * Format to macadresse, 00:11:22:33:44:55
 */
// based on example code from stackoverflow:
// https://stackoverflow.com/questions/16168125/auto-insert-colon-while-entering-mac-address-after-each-2-digit
$(function getMacadrInput() {
    var length = 1;
    // check if a number is passed in
    $('#withdrawQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');    // get ID from field
        content = $(this).val();         // check value input field
        content1 = content.replace(/\:/g, '');   //insert :
        length = content1.length;   //check lengt of passed inn value
        if (((length % 2) === 0) && length < 12 && length > 1) {
            $('#' + id).val($('#' + id).val() + ':');
        }
    });
});




