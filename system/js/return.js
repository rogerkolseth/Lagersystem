

$('#returnButton').hide(); // hides return button  
$('#commentContainer').hide();  // hides comment container
$('#chooseCategoryContainer').show();   // display category drop down

/*
 *  get all products in system
 */ 
$(function getStorageProduct() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllProductInfo',  // request given to controller
        dataType: 'json',
        success: function (data) {
            returnProductTemplate(data);    // display result
        }
    });
    return false;
});

/**
 * Update product list
 */
function updateProductList() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllProductInfo',  // request given to controller
        dataType: 'json',
        success: function (data) {
            returnProductTemplate(data);    // display result
        }
    });
    return false;
}

/**
 * display product to return
 * takes given data and poplate template
 */
function returnProductTemplate(data) {
     //takes template and populate it with passed array
    var rawTemplate = document.getElementById("returnProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("returnProductContainer");
    productContainer.innerHTML = transferProductGeneratedHTML;
}


/*
 * Get product info from selected product
 */
$(function POSTselectedProduct() {
    //check if product button inside returnProductContainer is clicked
    $('#returnProductContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id'); // get data-id from button
        if ($('#' + givenProductID).length) // check if product alreade is clicked
        {
            return false;   // return fals if it is 
        } else {
            $.ajax({
                type: 'POST',
                url: '?request=getProductByID', // request given to controller
                data: {givenProductID: givenProductID},  // data posted to controller
                dataType: 'json',
                success: function (data) {
                    returnQuantityTemplate(data);   // show selected product info
                    $('#commentContainer').show();  // show comment section 
                    $('#returnButton').show();      // show transfer button
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
function returnQuantityTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("returnQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var transferContainer = document.getElementById("returnQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;

}

/**
 * Register return
 */
$(function returnProduct() {
    // run if form is submitted
    $('#returnProducts').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                var $displayUsers = $('#errorMessage'); // display error message
                $displayUsers.empty().append("Kunne ikke overføre");
            },
            success: function (data) {
                $('.selectQuantity').remove();  // remove selected products
                $('#errorMessage').remove();       // remove error message
                $('#returnButton').hide();      // hide return button
                $('#commentContainer').hide();  // hide comment section
                successMessage();               // display success message
                updateProductList();            // update product list
                $("#returnProducts")[0].reset();    // reset form
            }
        });
        return false;
    });
});


/**
 * Display success message on return
 */
function successMessage() {
    $('<div class="alert alert-success"><strong>Registrert!</strong> Ditt uttak er registrert </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Remove a selected product
 */
$(function removeSelectedProduct() {
    //check if remove button inside returnQuantityContainer is clicked
    $('#returnQuantityContainer').delegate('.remove', 'click', function () {
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
$( function getUsedStorageCat() {
    var givenStorageID = '2';   // set return storage
    $.ajax({
            type: 'POST',
            url: '?request=getCatWithProd', // request given to controller
            data: {givenStorageID: givenStorageID},  // data posted to controller
            dataType: 'json',
            success: function (data) {
                chooseCategory(data);   // display category result
            }
        });
    return false;
});


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


$(function updateResultFromCategory() {
    //check if user change option in category dropdown 
    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');   // get selected categoryID

        $.ajax({
            type: 'POST',
            url: '?request=getProductFromCategory', // request given to controller
            data: {givenCategoryID: givenCategoryID},   // data posted to controller
            dataType: 'json',
            success: function (data) {
                returnProductTemplate(data);    // update result in product list
            }
        });
        return false;
    });
});

/**
 * Check if product use mac adresse
 */
$(function getNumberOfMac() {
    $('#returnQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();       // get value posted in quantity field
        var productID = $(this).attr('id'); // get product id from button-id
        var macadresse = $(this).attr('data-id');   // get mac adresse support from data-id
        if (macadresse > 0) {   // if number in quantity field is larger than 0, create new mac fields
            var $displayMacadresse = $('#product' + productID); // set element-id to populate
            $displayMacadresse.empty();
            // create a mac adresse field equal to given quantity
            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><td><td><td><input id="mac' + i + productID + '" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="returnMacadresse[]" form="returnProducts" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></td></td></td></tr>');
            }
        } else {return false;}
    });
});

/**
 * Format to macadresse, 00:11:22:33:44:55
 */
$(function getMacadrInput() {
    var length = 1;
    // check if a number is passed in 
    $('#returnQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');     // get ID from field
        content = $(this).val();        // check value input field
        content1 = content.replace(/\:/g, '');      //insert :
        length = content1.length;           //check lengt of passed inn value
        if (((length % 2) === 0) && length < 12 && length > 1) {
            $('#' + id).val($('#' + id).val() + ':');
        }
    });
});