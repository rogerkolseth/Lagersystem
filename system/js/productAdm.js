// DISPLAY PRODUCT MAIN TABLE -->

// GET productInformation -->

$('#dropdown').show();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllProductInfo',
        dataType: 'json',
        success: function (data) {
            productTableTemplate(data);
        }
    });
});



// Update product information -->

function UpdateProductTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllProductInfo',
            dataType: 'json',
            success: function (data) {
                productTableTemplate(data);
            }
        });
    });
}


// Display product template -->

function productTableTemplate(data) {

    var rawTemplate = document.getElementById("displayProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("displayProductContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}




// DELETE PRODUCT -->

// Delete product modal -->

$(function POSTdeleteProductModal() {

    $('#displayProductContainer').delegate('.delete', 'click', function () {
        var givenProductID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getProductByID',
            data: {givenProductID: givenProductID},
            dataType: 'json',
            success: function (data) {
                deleteProductTemplate(data);
                $('#deleteProductModal').modal('show');
            }
        });
        return false;

    });
});


// Delete product template-->         

function deleteProductTemplate(data) {
    var rawTemplate = document.getElementById("deleteProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteProductGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("deleteProductContainer");
    productContainer.innerHTML = deleteProductGeneratedHTML;
}


// Delete the product that is selected-->

$(function deleteProductByID() {

    $('#deleteProduct').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                successMessageDelete();
                UpdateProductTable();
                $('#deleteProductModal').modal('hide');

            }
        });
        return false;
    });
});



function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Produkt er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




// SHOW PRODUCT INFORMATION -->

// get information from selected product-->

$(function POSTproductInformationModal() {

    $('#displayProductContainer').delegate('.information', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        POSTproductLocation(givenProductID);
        $.ajax({
            type: 'POST',
            url: '?page=getProductByID',
            data: {givenProductID: givenProductID},
            dataType: 'json',
            success: function (data) {
                $('#showProductInformationModal').modal('show');
                productInformationTemplate(data);

            }
        });
        return false;

    });
});


// Display storageInformation Template-->

function productInformationTemplate(data) {
    var rawTemplate = document.getElementById("productInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productInformationGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("productInformationContainer");
    productContainer.innerHTML = productInformationGeneratedHTML;
}


// Get productLocation from selected storage-->

function POSTproductLocation(data) {
    var givenProductID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?page=getProductLocation',
            data: {givenProductID: givenProductID},
            dataType: 'json',
            success: function (data) {
                productLocationTemplate(data);
            }
        });
    });
}


// Display product location Template -->

function productLocationTemplate(data) {
    var rawTemplate = document.getElementById("productLocationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var storageProductGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("productLocationContainer");
    storageContainer.innerHTML = storageProductGeneratedHTML;
}



// EDIT PRODUCT -->

// Get the selected product, and opens editProduct modal-->

$(function POSTeditProductModal() {

    $('#displayProductContainer').delegate('.edit', 'click', function () {
        var givenProductID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getProductByID',
            data: {givenProductID: givenProductID},
            dataType: 'json',
            success: function (data) {
                editProductTemplate(data);
                $('#editProductModal').modal('show');
            }
        });
        return false;

    });
});


// Display edit product Template -->

function editProductTemplate(data) {
    var rawTemplate = document.getElementById("editProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editProductGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("editProductContainer");
    productContainer.innerHTML = editProductGeneratedHTML;
}


// POST results from editing, and updating the table-->

$(function POSTeditProductInfo() {

    $('#editProduct').submit(function () {
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
                $('#editProductModal').modal('hide');
                successMessageEdit();
                UpdateProductTable();
            }
        });
        return false;
    });
});




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Produkt er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt produktnavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




// CREATE PRODUCT -->


$(function POSTproductInfo() {

    $('#createProduct').submit(function () {
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
                $("#createProduct")[0].reset();
                $('#createProductModal').modal('hide');
                UpdateProductTable();
                successMessageCreate();
            }
        });
        return false;
    });
});




function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// SEARCH FOR PRODUCT -->


$(function POSTsearchForProduct() {

    $('#searchForProduct').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForProduct")[0].reset();
                productTableTemplate(data);
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
    var $displayMediaInformation = $('#selectMediaID');
    $displayMediaInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.mediaInfo, function (i, item) {


                    $displayMediaInformation.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');

                });


            }
        });
    });
}



function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryID');
    $displayCategoryInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllCategoryInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.categoryInfo, function (i, item) {


                    $displayCategoryInformation.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');

                });


            }
        });
    });
}



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getCategorySearchResult',
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);
        }
    });
});


// Display storage template -->

function chooseCategory(data) {
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}



$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?page=getProductFromCategory',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                productTableTemplate(data);
            }
        });
        return false;
    });
});



Date.prototype.yyyymmdd = function () {
    var yyyy = this.getFullYear();
    var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
    var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
    return "".concat(yyyy).concat(mm).concat(dd);
};

var d = new Date();
document.getElementById("date").value = d.yyyymmdd();

// SET WARNING LIMIT -->

// Get the selected product, and opens warningProduct modal-->

$(function POSTeditProductModal() {

    $('#displayProductContainer').delegate('.warning', 'click', function () {
        var givenProductID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getProductLocation',
            data: {givenProductID: givenProductID},
            dataType: 'json',
            success: function (data) {
                warningProductTemplate(data);
                $('#warningProductModal').modal('show');
            }
        });
        return false;

    });
});

function warningProductTemplate(data) {
    var rawTemplate = document.getElementById("warningProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editProductGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("warningProductContainer");
    productContainer.innerHTML = editProductGeneratedHTML;
}

$(function POSTsearchForProduct() {

    $('#warningProduct').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#warningProduct")[0].reset();
                $('#warningProductModal').modal('hide');
                UpdateProductTable();
            }
        });
        return false;
    });
});