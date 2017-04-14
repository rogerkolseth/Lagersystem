
function userTableTemplate(data) {

    var rawTemplate = document.getElementById("displayUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("displayUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getUserByID',
        dataType: 'json',
        success: function (data) {
            userTableTemplate(data);
        }
    });
});






$(function POSTeditUserInfo() {

    $('#editUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {

                UpdateUsersTable();
            }
        });
        return false;
    });
});




function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryEdit');
    $displayCategoryInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllCategoryInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.categoryInfo, function (i, item) {


                    $displayCategoryInformation.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');

                });


            }
        });
    });
}
