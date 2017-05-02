// Get storage information with user restriction -->



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getuserAndGroupRes',
        dataType: 'json',
        success: function (data) {
            showHide(data);
            withdrawRestrictionTemplate(data);
        }
    });
});



// Display storages in drop down meny Template -->

function withdrawRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("withdrawRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferRestrictionGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("withdrawrRestrictionContainer");
    transferContainer.innerHTML = transferRestrictionGeneratedHTML;

}



// Get the selected storage, and POST this to retrive inventory-->

var givenStorageID;
$(function POSTfromStorageModal() {

    $('#withdrawrRestrictionContainer').on('change', function () {
        givenStorageID = $(this).find("option:selected").data('id');

        if (givenStorageID > 0) {
            $.ajax({
                type: 'POST',
                url: '?page=getStorageProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {
                    withdrawProductTemplate(data);
                    $('.selectQuantity').remove();
                    $('#withdrawButton').hide();
                    $('#commentContainer').hide();
                    $('#chooseCategoryContainer').show();
                    $('#chooseCategoryContainer').prop('selectedIndex', 0);
                    getUsedStorageCat(givenStorageID);
                }
            });
        } else {
            $('.product').remove();
            $('.selectQuantity').remove();
            $('#commentContainer').hide();
            $('#withdrawButton').hide();
            $('#chooseCategoryContainer').hide();
        }

        return false;

    });
});



function displaySingleStorage(givenStorageID) {

    if (givenStorageID > 0) {
        $.ajax({
            type: 'POST',
            url: '?page=getStorageProduct',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                withdrawProductTemplate(data);
            }
        });
    }
    return false;
}



$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?page=getStoProFromCat',
            data: {givenCategoryID: givenCategoryID, givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                withdrawProductTemplate(data);
            }
        });
        return false;
    });
});


// Display products in storage Template -->

function withdrawProductTemplate(data) {
    var rawTemplate = document.getElementById("withdrawProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("withdrawProductContainer");
    transferContainer.innerHTML = transferProductGeneratedHTML;
}


// Get productID from selected ID -->


$(function POSTselectedProduct() {

    $('#withdrawProductContainer').delegate('.product', 'click', function () {
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

                    withdrawQuantityTemplate(data);
                    $('#commentContainer').show();
                    $('#withdrawButton').show();
                    negativeSupportStatus(data);
                }
            });
            return false;

        }
    });
});




function withdrawQuantityTemplate(data) {
    var rawTemplate = document.getElementById("withdrawQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("withdrawQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;

}




$(function POSTtransferProducts() {

    $('#withdrawProducts').submit(function () {
        var toStorageID = $.trim($('#withdrawrRestrictionContainer').val());
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
                var $displayUsers = $('#errorMessage');
                $displayUsers.empty().append("Kunne ikke overføre");
            },
            success: function (data) {
                if (data == "success") {
                    $('.product').remove();
                    $('.selectQuantity').remove();
                    $('#errorMessage').remove();
                    $("#withdrawProducts")[0].reset();
                    successMessage();
                    updateSale();
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
    $('<div class="alert alert-success"><strong>Registrert!</strong> Ditt uttak er registrert </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}





function updateSale() {
    $('#withdrawButton').hide();// hides transferbutton 
    $('#commentContainer').hide();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getTransferRestriction',
            dataType: 'json',
            success: function (data) {
                withdrawRestrictionTemplate(data);
            }
        });
    });
}



Date.prototype.yyyymmdd = function () {
    var yyyy = this.getFullYear();
    var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
    var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
    return "".concat(yyyy).concat(mm).concat(dd);
};

var d = new Date();
document.getElementById("date").value = d.yyyymmdd();




$(function removeSelectedProduct() {

    $('#withdrawQuantityContainer').delegate('.remove', 'click', function () {
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

function sendEmail() {
    $.ajax({
        type: 'GET',
        url: '?page=sendInventarWarning',
        dataType: 'json',
        success: function () {

        }
    });
}

// Display storage template -->

function chooseCategory(data) {
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}



function showHide(data) {
    var limit = 0;

    for (var i = 0; i < data.transferRestriction.length; i++) {
        limit = limit + 1;
    }

    if (limit < 2) {
        $('#chooseStorage').hide();
        $('#singleStorageContainer').show();
        var storageID = data.transferRestriction[0].storageID;
        displaySingleStorage(storageID);
        getUsedStorageCat(storageID);
        $('#singleStorageContainer').append('<p>' + data.transferRestriction[0].storageName + '</p>');
        $('#singleStorageContainer').append('<input name="fromStorageID" data-id="' + storageID + '" value="' + storageID + '" type="hidden"/>');
        $('#chooseCategoryContainer').show();

    } else {
        $('#chooseStorage').show();
        $('#singleStorageContainer').hide();
        $('#chooseCategoryContainer').hide();
    }
}


function negativeSupportStatus(data) {
    if (data.negativeSupport[0].negativeSupport < 1) {
        $('.negativeSupport').attr({
            "max": data.prodInfo[0].quantity, // substitute your own
        });
    }
}

// MAC ADRESSE UTTAK

$(function getNumberOfMac() {
    $('#withdrawQuantityContainer').delegate(".negativeSupport", "keyup", function (e) {
        var quantity = $(this).val();
        var productID = $(this).attr('id');
        var macadresse = $(this).attr('data-id');
        if (macadresse > 0) {
            var $displayMacadresse = $('#product' + productID);
            $displayMacadresse.empty();


            for (i = 0; i < quantity; i++) {
                $displayMacadresse.append('<tr><td><td><td><td><input id="mac' + i + productID + '" class="form-control macadresse" maxlength="17" pattern=".{17,17}" name="withdrawMacadresse[]" form="withdrawProducts" required title="Må være 12 tegn" value="" placeholder="macadresse"/></td></td></td></td></tr>');
            }
        } else {
            return false;
        }
    });
});


$(function getMacadrInput() {
    var length = 1;
    $('#withdrawQuantityContainer').delegate(".macadresse", "keyup", function (e) {
        var id = $(this).attr('id');
        content = $(this).val();
        content1 = content.replace(/\:/g, '');
        length = content1.length;
        if (((length % 2) === 0) && length < 12 && length > 1) {
            $('#' + id).val($('#' + id).val() + ':');
        }
    });
});




