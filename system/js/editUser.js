
/**
 * Get user information from logged in user
 */
$(function getLoggedInUser() {
    $.ajax({
        type: 'GET',
        url: '?request=getUserByID',
        dataType: 'json',
        success: function (data) {
            userTableTemplate(data);
        }
    });
});

/**
 * update user information from logged in user
 */
function UpdateLoggedInUser() {
    $.ajax({
        type: 'GET',
        url: '?request=getUserByID',
        dataType: 'json',
        success: function (data) {
            userTableTemplate(data);
        }
    });
}

/**
 * Dispaly user form template
 */ 
function userTableTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("displayUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}


/**
 * Edit userinformation on logged in user
 */ 
$(function editUser() {
    // run function on submitting
    $('#editUser').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                UpdateLoggedInUser();
                successMessage();
            }
        });
        return false;
    });
});

/**
 * Display success message on editing user
 */
function successMessage() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Bruker er redigert. </div>').appendTo('#message')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * get Category names for uploading image
 */
function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryEdit');
    $displayCategoryInformation.empty();    //get ID of element to populate
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllCategoryInfo',
            dataType: 'json',
            success: function (data) {
                // create an option for each element in array and append it 
                $.each(data.categoryInfo, function (i, item) {
                    $displayCategoryInformation.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');

                });


            }
        });
    });
}
