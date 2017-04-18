// Get storage information with user restriction -->

$('#transferButton').hide(); // hides transferbutton          
$('#chooseCategoryContainer').hide();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getTransferRestriction',
        dataType: 'json',
        success: function (data) {
            transferRestrictionTemplate(data);
        }
    });
});


// Display storages in drop down meny Template -->

function transferRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("transferRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferRestrictionGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("fromTransferRestrictionContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;

    var transferContainer = document.getElementById("toTransferRestrictionContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;
}



// Get the selected storage, and POST this to retrive inventory-->

var givenStorageID;
$(function POSTfromTransferModal() {

    $('#fromTransferRestrictionContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');

        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?page=getStorageProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    transferProductTemplate(data);
                    $('.selectQuantity').remove();
                    $('#transferButton').hide();
                    $('#chooseCategoryContainer').show();
                    $('#chooseCategoryContainer').prop('selectedIndex',0);

                }
            });
        } else {
            $('.product').remove();
            $('.selectQuantity').remove();
            $('#transferButton').hide();
            $('#chooseCategoryContainer').hide();
        }

        return false;

    });
});



$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?page=getStoProFromCat',
            data: {givenCategoryID: givenCategoryID, givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                transferProductTemplate(data);
            }
        });
        return false;
    });
});


// Display products in storage Template -->

function transferProductTemplate(data) {
    var rawTemplate = document.getElementById("transferProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("transferProductContainer");
    transferContainer.innerHTML = transferProductGeneratedHTML;
}


// Get productID from selected ID -->

$(function POSTeditUserModal() {

    $('#transferProductContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
        if ($('#' + givenProductID).length)
        {
            return false;
        } else {


            $.ajax({
                type: 'POST',
                url: '?page=getProdQuantity',
                data: {givenProductID: givenProductID, givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    negativeSupportStatus(data);
                    transferQuantityTemplate(data);
                    $('#transferButton').show();
                }
            });
            return false;
        }

    });
});



function transferQuantityTemplate(data) {
    var rawTemplate = document.getElementById("transferQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("transferQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;
}





$(function POSTtransferProducts() {

    $('#transferProducts').submit(function () {
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
            success: function (data) {
                $('.product').remove();
                $('.selectQuantity').remove();
                $('#errorMessage').remove();
                successMessage();
                updateTransfer();
            }
        });
        return false;
    });
});


function successMessage() {
    $('<div class="alert alert-success"><strong>Registrert!</strong> Ditt uttak er registrert </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Du må velge TIL lager </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function updateTransfer() {
    $('#transferButton').hide(); // hides transferbutton                    
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getTransferRestriction',
            dataType: 'json',
            success: function (data) {
                transferRestrictionTemplate(data);
            }
        });
    });
}


// remove product modal -->

$(function POSTdeleteStorageModal() {

    $('#transferQuantityContainer').delegate('.remove', 'click', function () {
        var $tr = $(this).closest('tr');

        $tr.fadeOut(150, function () {
            $(this).remove();
        });

    });
});



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

function negativeSupportStatus(data) {
    if (data.negativeSupport[0].negativeSupport < 1) {
        $('.negativeSupport').attr({
            "max": data.prodInfo[0].quantity, // substitute your own
        });
    }
}