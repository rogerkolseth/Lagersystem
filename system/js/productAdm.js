
$('#dropdown').show();  // opens administrator meny

/**
 * Get all product information
 */
$(function getAllProductInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllProductInfo',  // request given to controller
        dataType: 'json',
        success: function (data) {
            productTableTemplate(data);     // display product table
        }
    });
});


/**
 * Update product information table
 */
function UpdateProductTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllProductInfo',  // request given to controller
            dataType: 'json',
            success: function (data) {
                productTableTemplate(data); // display product table
            }
        });
    });
}


/**
 * display all products
 * takes given data and poplate template
 */
function productTableTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("displayProductContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}




/**
 * Select product to delete
 */
$(function deleteProductModal() {
    //check if delete button inside displayProductContainer is clicked
    $('#displayProductContainer').delegate('.delete', 'click', function () {
        var givenProductID = $(this).attr('data-id'); // get data-id from button
        
        $.ajax({
            type: 'POST',
            url: '?request=getProductByID', // request given to controller
            data: {givenProductID: givenProductID}, // data posted to controller
            dataType: 'json',   
            success: function (data) {
                deleteProductTemplate(data);    // display info of selected product
                $('#deleteProductModal').modal('show'); // show delete product modal
            }
        });
        return false;

    });
});


/**
 * display info of product to delete
 * takes given data and poplate template
 */
function deleteProductTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("deleteProductContainer");
    productContainer.innerHTML = deleteProductGeneratedHTML;
}


/**
 * Deletes selected product
 */
$(function deleteProductByID() {
    // run if form is submitted
    $('#deleteProduct').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                successMessageDelete(); // display success message
                UpdateProductTable();   // update product table
                $('#deleteProductModal').modal('hide');     // hide delete modal

            }
        });
        return false;
    });
});


/**
 * Display success message on product delete
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Produkt er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Get selected product information
 */
$(function getProductInformation() {
    //check if information button inside displayProductContainer is clicked
    $('#displayProductContainer').delegate('.information', 'click', function () {
        var givenProductID = $(this).attr('data-id');   // get data-id from button
        getProductLocation(givenProductID);     // get product locations
        $.ajax({
            type: 'POST',
            url: '?request=getProductByID', // request given to controller
            data: {givenProductID: givenProductID}, // data posted to controller
            dataType: 'json',
            success: function (data) {
                $('#showProductInformationModal').modal('show');    // show product info modal
                productInformationTemplate(data);   // display product info 
                supportMacStatus(data.product[0].macAdresse);   // display if product support mac
            }
        });
        return false;

    });
});


/**
 * display product info
 * takes given data and poplate template
 */
function productInformationTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("productInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productInformationGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("productInformationContainer");
    productContainer.innerHTML = productInformationGeneratedHTML;
}


/**
 * Get locations of selected product
 */
function getProductLocation(data) {
    var givenProductID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getProductLocation', // request given to controller
            data: {givenProductID: givenProductID}, // data posted to controller
            dataType: 'json',
            success: function (data) {
                productLocationTemplate(data);  // display storage containing selected product
                rowColor();     // format color of table depinging of inventorystatus
            }
        });
    });
}


/**
 * display product location
 * takes given data and poplate template
 */
function productLocationTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("productLocationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var storageContainer = document.getElementById("productLocationContainer");
    storageContainer.innerHTML = storageProductGeneratedHTML;
}



/**
 * Display product to be edited
 */
$(function editProductModal() {
    //check if edit button inside displayProductContainer is clicked
    $('#displayProductContainer').delegate('.edit', 'click', function () {
        var givenProductID = $(this).attr('data-id');   // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getProductByID', // request given to controller
            data: {givenProductID: givenProductID}, // data posted to controller
            dataType: 'json',
            success: function (data) {
                editProductTemplate(data);  // display product to be edited
                $('#editProductModal').modal('show');   // opens edit modal
            }
        });
        return false;

    });
});


/**
 * display edit product 
 * takes given data and poplate template
 */
function editProductTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("editProductContainer");
    productContainer.innerHTML = editProductGeneratedHTML;
}


/**
 * Saves changes from editing
 */
$(function editProductInfo() {
    // run if form is submitted
    $('#editProduct').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();     // display errormessage
            },
            success: function () {
                $('#editProductModal').modal('hide');   // hide edit product modal
                successMessageEdit();       // display success message
                UpdateProductTable();       // update product table
            }
        });
        return false;
    });
});



/**
 * Display success message on editing
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Produkt er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Display error message on editing
 */
function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt produktnavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Create new product
 */
$(function POSTproductInfo() {
    // run if form is submitted
    $('#createProduct').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {    // display error message
                errorMessage();
            },
            success: function () {
                $("#createProduct")[0].reset();     // reset create product form
                $('#createProductModal').modal('hide');     // hide create product modal
                UpdateProductTable();       // update product table
                successMessageCreate();     // display success message
            }
        });
        return false;
    });
});


/**
 * Display error message on creation
 */
function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Display success message on creating
 */
function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Search for a given product
 */
$(function searchForProduct() {
    // run if form is submitted
    $('#searchForProduct').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForProduct")[0].reset();  // resett search form 
                productTableTemplate(data);     // display search result
            }
        });
        return false;
    });
});


/**
 * get needed media and category info to create product
 */
function createProductInfo() {
    getMediaInfo();
    getCategoryInfo();
}

/**
 * display media names in dropdown meny
 */
function getMediaInfo() {
    var $displayMediaInformation = $('#selectMediaID'); // set element-id to be populated
    $displayMediaInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllMediaInfo',    // request given to controller
            dataType: 'json',
            success: function (data) {
                //populate a value on dropdown meny for each media in system
                $.each(data.mediaInfo, function (i, item) {
                    $displayMediaInformation.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                });
            }
        });
    });
}


/**
 * display category names in dropdown meny
 */
function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryID');   // set element-id to be populated
    $displayCategoryInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllCategoryInfo',     // request given to controller
            dataType: 'json',
            success: function (data) {
                //populate a value on dropdown meny for each media in system
                $.each(data.categoryInfo, function (i, item) {
                    $displayCategoryInformation.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                });
            }
        });
    });
}

/**
 * Get categories containing a product
 */
$(function getCatWithProd() {
    $.ajax({
        type: 'GET',
        url: '?request=getCatWithProd', // request given to controller
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);   // display categories to choose from
        }
    });
});


/**
 * display categories containing product
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
 * update product result from category search
 */
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
                productTableTemplate(data); // update result in product table
            }
        });
        return false;
    });
});


/**
 * Display information of warning limit
 */
$(function editWarningLimit() {
    //check if warning button inside displayProductContainer is clicked
    $('#displayProductContainer').delegate('.warning', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '?request=getProductLocation', // request given to controller
            data: {givenProductID: givenProductID}, // data posted to controller
            dataType: 'json',
            success: function (data) {
                warningProductTemplate(data);   // display info of warning limit on selected product
                $('#warningProductModal').modal('show');    // show warning limit modal
            }
        });
        return false;
    });
});

/**
 * display warning limit
 * takes given data and poplate template
 */
function warningProductTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("warningProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editProductGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("warningProductContainer");
    productContainer.innerHTML = editProductGeneratedHTML;
}

/**
 * Saves changes on warning limit
 */
$(function setWarningLimit() {
    // run if form is submitted
    $('#warningProduct').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#warningProduct")[0].reset();    // resett warning limit form
                $('#warningProductModal').modal('hide');    // hide warning limit modal
                UpdateProductTable();   // update product tabel
            }
        });
        return false;
    });
});

/**
 * Format color on product location tabel based on inventory status
 */
function rowColor() {
    // if more than 10, row is green
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) >= 10;
    }).siblings().andSelf().attr('class', 'bg-success');

    // if between 5 and 10, row is orange
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5;
    }).siblings().andSelf().attr('class', 'bg-warning');

    // if lower than 5, row is red
    $('.quantityColor').filter(function (index) {
        return parseInt(this.innerHTML) < 5;
    }).siblings().andSelf().attr('class', 'bg-danger');
    }
    
    // display "ja" or "no" depending if product use mac adrese. If 1 = yes, 0 = no
    function supportMacStatus(data) {
    if (data > 0) {
        $('.supportMacStatus').append('Ja');
    } else {
        $('.supportMacStatus').append('Nei');
    }
}