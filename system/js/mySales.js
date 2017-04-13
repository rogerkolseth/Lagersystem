
$(function () {

    $.ajax({
        type: 'GET',
        url: '?page=getMySales',
        dataType: 'json',
        success: function (data) {
            mySalesTemplate(data);
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
            }
        });
    });
}





function mySalesTemplate(data) {
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


