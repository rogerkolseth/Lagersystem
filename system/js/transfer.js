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
                    $('#chooseCategoryContainer').prop('selectedIndex', 0);
                    getUsedStorageCat(givenStorageID);
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
        var toStorageID = $.trim($('#toTransferRestrictionContainer').val());
        if (toStorageID < 1) {
            errorMessage();
        } else {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function () {
                    //errorMessage();
                },
                success: function (data) {
                    if (data == "success") {
                        $('.product').remove();
                        $('.selectQuantity').remove();
                        $('#errorMessage').remove();
                        successMessage();
                        updateTransfer();
                        sendEmail();
                    } else {
                        missingMacError(data);
                    }
                }
            });
        }
        return false;
    });
});

function missingMacError(data) {
    $('<div class="alert alert-danger"><strong>Error!</strong> Finner ikke mac: <strong>' + data + ' </strong> </div>').appendTo('#error')
            .delay(10000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

function successMessage() {
    $('<div class="alert alert-success"><strong>Registrert!</strong> Overføringen er registrert </div>').appendTo('#success')
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

$(function removeSelectedProduct() {

    $('#transferQuantityContainer').delegate('.remove', 'click', function () {
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
function getUsedStorageCat(givenStorageID) {
    $.ajax({
        type: 'POST',
        url: '?page=getCatWithProdAndSto',
        data: {givenStorageID: givenStorageID},
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);
        }
    });
    return false;
}


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

function sendEmail() {
    $.ajax({
        type: 'GET',
        url: '?page=sendInventarWarning',
        dataType: 'json',
        success: function () {

        }
    });
}

// MAC ADRESSE UTTAK

$(function getNumberOfMac() {
    $('#transferQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();
        var productID = $(this).attr('id');
        var macadresse = $(this).attr('data-id');
        if (macadresse > 0) {
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty();
            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><input id="mac' + i + productID + '" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="deliveryMacadresse[]" form="transferProducts" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></tr>');
            }
        } else {
            return false;
        }
    });
});

$(function getMacadrInput() {
    var length = 1;
    $('#transferQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');
        content = $(this).val();
        content1 = content.replace(/\:/g, '');
        length = content1.length;
        if (((length % 2) === 0) && length < 12 && length > 1) {
            $('#' + id).val($('#' + id).val() + ':');
        }
    });
});