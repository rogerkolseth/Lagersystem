
/**
 * Get all returns from logged in user
 */
$(function getMyReturns() {

    $.ajax({
        type: 'GET',
        url: '?request=getMyReturns',   // request given to controller
        dataType: 'json',
        success: function (data) {
            myReturnsTemplate(data);    // display returns
            userReturnTemplate(data);   // get usernames for advance serach
        }
    });
});




/**
 * update return table
 */
function UpdateReturnsTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getMyReturns',   // request given to controller
            dataType: 'json',
            success: function (data) {
                myReturnsTemplate(data);    // display returns
                userReturnTemplate(data);   // get usernames for advance serach
            }   
        });
    });
}


/**
 * display all returns
 * takes given data and poplate template
 */
function myReturnsTemplate(data) {
    // handle bar helper, check if a value equals another value
    Handlebars.registerHelper('if_eq', function (a, b, opts) {
        if (a == b)
            return opts.fn(this);
        else
            return opts.inverse(this);
    });
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("myReturnsTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mySalesnGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var myReturnsContainer = document.getElementById("myReturnsContainer");
    myReturnsContainer.innerHTML = mySalesnGeneratedHTML;

}


/**
 * Search for return
 */
$(function searchForReturn() {
    // run if form is submitted
    $('#searchForReturns').submit(function () {
        var url = $(this).attr('action');   // get action from form
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForReturns")[0].reset();  // reset form
                myReturnsTemplate(data);        // update return tabel
            }
        });
        return false;
    });
});



/**
 * edit a given return
 */
$(function editMyReturns() {
    //check if editReturns button inside myReturnsContainer is clicked
    $('#myReturnsContainer').delegate('.editReturns', 'click', function () {
        var givenReturnsID = $(this).attr('data-id');   // get data-id from button
        $.ajax({
            type: 'POST',
            url: '?request=getReturnsFromID',       // request given to controller
            data: {givenReturnsID: givenReturnsID},     // data posted to controller
            dataType: 'json',
            success: function (data) {
                editReturnsTemplate(data);      // display info from choosen return
                $('#editReturnsModal').modal('show');   // show edit return modal
            }
        });
        return false;

    });
});

/**
 * Display registered mac in a return
 */
$(function showMacReturns() {
    //check if showMac button inside myReturnsContainer is clicked
    $('#myReturnsContainer').delegate('.showMac', 'click', function () {
        var givenReturnsID = $(this).attr('data-id');   // get data-id from button
        $.ajax({
            type: 'POST',
            url: '?request=getReturnsMacFromID',    // request given to controller
            data: {givenReturnsID: givenReturnsID},     //data posted to controller
            dataType: 'json',
            success: function (data) {
                $('#macReturnsModal').modal('show');    // show mac modal
                var $macReturnTemplate = $('#macReturnsContainer'); // set element-id to populate
                $macReturnTemplate.empty();             // empty element-id
                $.each(data.myReturnsMac, function (i, item) {  // populate selected element-id with macadresses
                $macReturnTemplate.append('<tr><td>'+ item.macAdresse+'</td></tr>');
                });
            }
        });
        return false;
    });
});

/**
 * display edit return 
 * takes given data and poplate template
 */
function editReturnsTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editReturnTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editReturnGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var returnContainer = document.getElementById("editReturnContainer");
    returnContainer.innerHTML = editReturnGeneratedHTML;
}


/**
 * Save edited changes
 */
$(function updateReturnInfo() {
    // run if edit return form is submitted
    $('#editReturn').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editReturnsModal').modal('hide');   // hide edit return modal
                UpdateReturnsTable();   // update return table
            }
        });
        return false;
    });
});

/**
 * populate advance search for return 
 */
function userReturnTemplate(data) {
    // set element-id to be populated, and empty it
    var $usernameTemplate = $('#chooseUserReturnContainer');
    $usernameTemplate.empty();
    // populate element-id with all usernames in system, with an checkbox
    $usernameTemplate.append('<tr><td id="bordernone">Alle</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="0"></td></tr>');
    $.each(data.usernames, function (i, item) {
        $usernameTemplate.append('<tr><td id="bordernone">' + item.username + '</td> <td id="bordernone"><input id="chooseUserReturn" type="checkbox" name="username[]" value="' + item.userID + '"></td></tr>');
    });
    $usernameTemplate.append('<div class="pull-right"> <input class="form-control btn btn-primary" type="submit" form="showUserReturn"  value="Velg"> </div>');
}

/**
 * Get selected users return
 */
$(function getUserReturn() {
    // run if show user return form is submitted
    $('#showUserReturn').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize from data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                myReturnsTemplate(data);        // display result in return table
                $('.dropdown.open').removeClass('open');       // close search dropdown 
            }
        });
        return false;
    });
});