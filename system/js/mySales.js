
/**
 * Get all sales from logged in user
 */
$(function getMySales() {

    $.ajax({
        type: 'GET',
        url: '?request=getMySales', // request given to controller
        dataType: 'json',
        success: function (data) {
            mySalesTemplate(data);  // display sales
            userSaleTemplate(data); // get usernames for advance serach
        }
    });
});



/**
 * update sale table
 */
function UpdateSalesTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getMySales', // request given to controller
            dataType: 'json',
            success: function (data) {
                mySalesTemplate(data);  // display sales
                userSaleTemplate(data); // get usernames for advance serach                
            }
        });
    });
}


/**
 * display all sales
 * takes given data and poplate template
 */
function mySalesTemplate(data) {
    // handle bar helper, check if a value equals another value
    Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b) 
        return opts.fn(this);
    else
        return opts.inverse(this);
    });
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("mySalesTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mySalesnGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var mySalesContainer = document.getElementById("mySalesContainer");
    mySalesContainer.innerHTML = mySalesnGeneratedHTML;

}


/**
 * Search for sale
 */
$(function searchForSale() {
    // run if form is submitted
    $('#searchForSale').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form    

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForSale")[0].reset(); // reset form
                mySalesTemplate(data);          // update sale tabel
            }
        });
        return false;
    });
});


/**
 * edit a given sale
 */
$(function editMySales() {
    //check if editSales button inside mySalesContainer is clicked
    $('#mySalesContainer').delegate('.editSales', 'click', function () {
        var givenSalesID = $(this).attr('data-id'); // get data-id from button
        $.ajax({
            type: 'POST',
            url: '?request=getSalesFromID',  // request given to controller
            data: {givenSalesID: givenSalesID}, // data posted to controller
            dataType: 'json',
            success: function (data) {
                editSaleTemplate(data);     // display info from choosen sale
                $('#editSaleModal').modal('show');  // show edit sale modal
            }
        });
        return false;

    });
});

/**
 * Display registered mac in a sale
 */
$(function showMacSales() {
    //check if showMac button inside mySalesContainer is clicked
    $('#mySalesContainer').delegate('.showMac', 'click', function () {
        var givenSalesID = $(this).attr('data-id');     // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getSalesMacFromID',      // request given to controller
            data: {givenSalesID: givenSalesID},     //data posted to controller
            dataType: 'json',
            success: function (data) {
                $('#macSaleModal').modal('show');       // show mac modal
                var $macSaleTemplate = $('#macSaleContainer');  // set element-id to populate
                $macSaleTemplate.empty();       // empty element-id
                $.each(data.mySalesMac, function (i, item) {    // populate selected element-id with macadresses
                $macSaleTemplate.append('<tr><td>'+ item.macAdresse+'</td></tr>');
                });
            }
        });
        return false;
        
    });
});


/**
 * display edit sale 
 * takes given data and poplate template
 */
function editSaleTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editSaleTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editSaleGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var saleContainer = document.getElementById("editSaleContainer");
    saleContainer.innerHTML = editSaleGeneratedHTML;
}


/**
 * Save edited changes
 */
$(function updateSaleInfo() {
    // run if edit sale form is submitted
    $('#editSale').submit(function () {
        var url = $(this).attr('action');    // get form action
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editSaleModal').modal('hide');  // hide edit sale modal
                UpdateSalesTable(); // update sale table
            }
        });
        return false;
    });
});


/**
 * populate advance search for sale 
 */
function userSaleTemplate(data) {
    // set element-id to be populated, and empty it
    var $usernameTemplate = $('#chooseUserSaleContainer');
    $usernameTemplate.empty();
    // populate element-id with all usernames in system, with an checkbox
    $usernameTemplate.append('<tr><td id="bordernone">Alle</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="0"></td></tr>');
    $.each(data.usernames, function (i, item) {
        $usernameTemplate.append('<tr><td id="bordernone">' + item.username +'</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="'+item.userID+'"></td></tr>');
    });
    $usernameTemplate.append('<div class="pull-right"> <input class="form-control btn btn-primary" type="submit" form="showUserSale"  value="Velg"> </div>');
}


/**
 * Get selected users sale
 */
$(function POSTshowUserSale() {
    // run if show user return form is submitted
    $('#showUserSale').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize from data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                mySalesTemplate(data);       // display result in sale table  
                $('.dropdown.open').removeClass('open');    // close search dropdown 
            }
        });
        return false;
    });
});