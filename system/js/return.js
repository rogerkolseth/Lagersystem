// Get storage information with user restriction -->

$('#returnButton').hide(); // hides transferbutton  
$('#commentContainer').hide();
$('#chooseCategoryContainer').show();

$(function getStorageProduct() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllProductInfo',
        dataType: 'json',
        success: function (data) {
            returnProductTemplate(data);
        }
    });
    return false;
});

function updateProductList() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllProductInfo',
        dataType: 'json',
        success: function (data) {
            returnProductTemplate(data);
        }
    });
    return false;
}

// Display products in storage Template -->

function returnProductTemplate(data) {
    var rawTemplate = document.getElementById("returnProductTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var productContainer = document.getElementById("returnProductContainer");
    productContainer.innerHTML = transferProductGeneratedHTML;
}


// Get productID from selected ID -->


$(function POSTselectedProduct() {

    $('#returnProductContainer').delegate('.product', 'click', function () {
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

                    returnQuantityTemplate(data);
                    $('#commentContainer').show();
                    $('#returnButton').show();
                }
            });
            return false;

        }
    });
});



function returnQuantityTemplate(data) {
    var rawTemplate = document.getElementById("returnQuantityTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var transferProductGeneratedHTML = compiledTemplate(data);

    var transferContainer = document.getElementById("returnQuantityContainer");
    transferContainer.innerHTML += transferProductGeneratedHTML;

}



$(function POSTtransferProducts() {

    $('#returnProducts').submit(function () {
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
                $('.selectQuantity').remove();
                $('#errorMessage').remove();
                $('#returnButton').hide(); 
                $('#commentContainer').hide();
                successMessage();
                updateProductList();
                $("#returnProducts")[0].reset();
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




Date.prototype.yyyymmdd = function () {
    var yyyy = this.getFullYear();
    var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
    var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
    return "".concat(yyyy).concat(mm).concat(dd);
};

var d = new Date();
document.getElementById("date").value = d.yyyymmdd();



// remove product modal -->

$(function POSTdeleteStorageModal() {

    $('#returnQuantityContainer').delegate('.remove', 'click', function () {
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


$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?page=getProductFromCategory',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                returnProductTemplate(data);
            }
        });
        return false;
    });
});