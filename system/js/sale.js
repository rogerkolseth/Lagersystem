// Get storage information with user restriction -->


$('#withdrawButton').hide();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getTransferRestriction',
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
                    $('#chooseCategoryContainer').prop('selectedIndex',0);
                    
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
                $('.product').remove();
                $('.selectQuantity').remove();
                $('#errorMessage').remove();
                successMessage();
                updateSale();
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




$(function removeSelectedProductModal() {

    $('#withdrawQuantityContainer').delegate('.remove', 'click', function () {
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



function showHide(data) {
    var limit = 0;

    for (var i = 0; i < data.transferRestriction.length; i++) {
        limit = limit + 1;
    }

    if (limit < 2) {
        $('#chooseStorage').hide();
        $('#singleStorageContainer').show();
        storageID = data.transferRestriction[0].storageID;
        displaySingleStorage(storageID);
        $('#singleStorageContainer').append('<p>' + data.transferRestriction[0].storageName + '</p>');
        $('#chooseCategoryContainer').show();

    } else {
        $('#chooseStorage').show();
        $('#singleStorageContainer').hide();
        $('#chooseCategoryContainer').hide();
    }
}




