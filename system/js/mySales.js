
$(function () {

    $.ajax({
        type: 'GET',
        url: '?page=getMySales',
        dataType: 'json',
        success: function (data) {
            mySalesTemplate(data);
            userSaleTemplate(data);
        }
    });
});



// Update sales information -->

function UpdateSalesTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getMySales',
            dataType: 'json',
            success: function (data) {
                mySalesTemplate(data);
                userSaleTemplate(data);
                
            }
        });
    });
}





function mySalesTemplate(data) {
    Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b) 
        return opts.fn(this);
    else
        return opts.inverse(this);
    });

    var rawTemplate = document.getElementById("mySalesTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mySalesnGeneratedHTML = compiledTemplate(data);

    var mySalesContainer = document.getElementById("mySalesContainer");
    mySalesContainer.innerHTML = mySalesnGeneratedHTML;

}



// SEARCH FOR SALES -->


$(function POSTsearchForSale() {

    $('#searchForSale').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForSale")[0].reset();
                mySalesTemplate(data);
                
            }
        });
        return false;
    });
});




$(function editMySales() {
    $('#mySalesContainer').delegate('.editSales', 'click', function () {

        var givenSalesID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getSalesFromID',
            data: {givenSalesID: givenSalesID},
            dataType: 'json',
            success: function (data) {
                editSaleTemplate(data);

                $('#editSaleModal').modal('show');

            }
        });
        return false;

    });
});

$(function showMacReturns() {
    $('#mySalesContainer').delegate('.showMac', 'click', function () {
        var givenSalesID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getSalesMacFromID',
            data: {givenSalesID: givenSalesID},
            dataType: 'json',
            success: function (data) {
                $('#macSaleModal').modal('show');
                var $macSaleTemplate = $('#macSaleContainer');
                $macSaleTemplate.empty();
                
                $.each(data.mySalesMac, function (i, item) {
                $macSaleTemplate.append('<tr><td>'+ item.macAdresse+'</td></tr>');
                });
            }
        });
        return false;
        
    });
});


// Display edit sale Template -->

function editSaleTemplate(data) {
    var rawTemplate = document.getElementById("editSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editSaleGeneratedHTML = compiledTemplate(data);

    var saleContainer = document.getElementById("editSaleContainer");
    saleContainer.innerHTML = editSaleGeneratedHTML;
}


// POST results from editing, and updating the table-->

$(function POSTeditSaleInfo() {

    $('#editSale').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editSaleModal').modal('hide');
                
                UpdateSalesTable();
            }
        });
        return false;
    });
});



function userSaleTemplate(data) {
    var $usernameTemplate = $('#chooseUserSaleContainer');
    $usernameTemplate.empty();
    $usernameTemplate.append('<tr><td id="bordernone">Alle</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="0"></td></tr>');
    $.each(data.usernames, function (i, item) {
        $usernameTemplate.append('<tr><td id="bordernone">' + item.username +'</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="'+item.userID+'"></td></tr>');
    });
    $usernameTemplate.append('<div class="pull-right"> <input class="form-control btn btn-primary" type="submit" form="showUserSale"  value="Velg"> </div>');
}

$(function POSTshowUserSale() {

    $('#showUserSale').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                mySalesTemplate(data);
                $('.dropdown.open').removeClass('open');
            }
        });
        return false;
    });
});