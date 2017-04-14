// CREATE USER -->

$(function POSTuserInfo() {

    $('#createUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessage();
            },
            success: function () {
                $("#createUser")[0].reset();
                $('#createUserModal').modal('hide');
                $('#errorMessage').remove();
                UpdateUsersTable();
                successMessageCreate();
            }
        });
        return false;
    });
});




function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// SEARCH FOR USERS -->

$(function POSTsearchForUser() {

    $('#searchForUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForUser")[0].reset();
                usersTableTemplate(data);
            }
        });
        return false;
    });
});





// UPDATE USER INFOMARTION -->

function UpdateUsersTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getUserInfo',
            dataType: 'json',
            success: function (data) {
                usersTableTemplate(data);
            }
        });
    });
}




// GET USER INFOROMATION -->


$('#dropdown').show();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getUserInfo',
        dataType: 'json',
        success: function (data) {
            usersTableTemplate(data);
        }
    });
});



// DISPLAY USER TEMPLATE -->

function usersTableTemplate(data) {

    var rawTemplate = document.getElementById("displayUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("displayUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}





//    SHOW USER INFORMATION      -->



$(function POSTuserInformationModal() {

    $('#displayUserContainer').delegate('.information', 'click', function () {

        var givenUserID = $(this).attr('data-id');
        POSTuserRestriction(givenUserID);
        $.ajax({
            type: 'POST',
            url: '?page=getUserByID',
            data: {givenUserID: givenUserID},
            dataType: 'json',
            success: function (data) {
                $('#showUserInformationModal').modal('show');
                userInformationTemplate(data);

            }
        });
        return false;

    });
});



var givenUserID;
function POSTuserRestriction(data) {
    givenUserID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?page=getUserRestriction',
            data: {givenUserID: givenUserID},
            dataType: 'json',
            success: function (data) {
                userRestrictionTemplate(data);
            }
        });
    });
}



$(function deleteUserRestriction() {
    $('#userRestrictionContainer').delegate('.deleteRestriction', 'click', function () {

        var givenStorageID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=deleteSingleRes',
            data: {givenUserID: givenUserID, givenStorageID: givenStorageID},
            dataType: 'json',
            success: function () {
                successMessageRes();
                POSTuserRestriction(givenUserID);

            }
        });
        return false;

    });
});



function successMessageRes() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Brukertilgang er slettet. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




function userRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("userRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("userRestrictionContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}



function userInformationTemplate(data) {
    var rawTemplate = document.getElementById("userInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserInformationGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("userInformationContainer");
    userContainer.innerHTML = UserInformationGeneratedHTML;
}





//   DELETE USER     -->


// DELETE USER MODAL -->

$(function POSTdeleteUserModal() {

    $('#displayUserContainer').delegate('.delete', 'click', function () {
        var givenUserID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getUserByID',
            data: {givenUserID: givenUserID},
            dataType: 'json',
            success: function (data) {
                deleteUserTemplate(data);
                $('#deleteUserModal').modal('show');
            }
        });
        return false;

    });
});


// DELETE USER TEMPLATE-->         

function deleteUserTemplate(data) {
    var rawTemplate = document.getElementById("deleteUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("deleteUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}



$(function deleteUserByID() {

    $('#deleteUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                successMessageDelete();
                UpdateUsersTable();
                $('#deleteUserModal').modal('hide');

            }
        });
        return false;
    });
});




function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Bruker er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// EDIT USER -->




$(function POSTeditUserModal() {

    $('#displayUserContainer').delegate('.edit', 'click', function () {
        var givenUserID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getUserByID',
            data: {givenUserID: givenUserID},
            dataType: 'json',
            success: function (data) {
                editUserTemplate(data);
                $('#editUserModal').modal('show');
            }
        });
        return false;

    });
});



// EDIT USER TEMPLATE-->         

function editUserTemplate(data) {
    var rawTemplate = document.getElementById("editUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editUserGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("editUserContainer");
    userContainer.innerHTML = editUserGeneratedHTML;
}



$(function POSTeditUserInfo() {

    $('#editUser').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();
            },
            success: function () {
                $('#editUserModal').modal('hide');
                successMessageEdit();
                UpdateUsersTable();
            }
        });
        return false;
    });
});




function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Bruker er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



// SET RESTRICTION -->

// Make button visible when clicked-->

$('#setRestriction').hide();
$('#displayUserContainer').delegate('.selectRestriction', 'click', function () {
    if ($(".selectRestriction").is(":checked") === true) {
        $('#setRestriction').show();
    } else {
        $('#setRestriction').hide();
    }
});


// Get storage information-->

function getStorageInfo() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllStorageInfo',
            dataType: 'json',
            success: function (data) {
                storageRestrictionTemplate(data);
            }
        });
    });
}


// Genereate userRestriciton template and display it in contaioner-->

function storageRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var userRestrictionGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("storageRestrictionContainer");
    userContainer.innerHTML = userRestrictionGeneratedHTML;
}


// Post new restriction-->

$(function POSTrestrictionInfo() {
    $('#editRestriction').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#userRestrictionModal').modal('hide');
                successMessageAddRes();
                UpdateUsersTable();
            }
        });
        return false;
    });
});




function successMessageAddRes() {
    $('<div class="alert alert-success"><strong>Lagret!</strong> Brukertilgang er lagret. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}





function getMediaInfo() {
    var $displayMediaInformation = $('#selectMediaID');
    $displayMediaInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {

                $.each(data.mediaInfo, function (i, item) {


                    $displayMediaInformation.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');

                });


            }
        });
    });
}



$(document).ready(function ()
{
    $('#userRestrictionModal').on('hidden.bs.modal', function (e)
    {
        $('input:checkbox').removeAttr('checked');
        $('#setRestriction').hide();
    });
});

