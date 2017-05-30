
/**
 * Create a new user
 */
$(function createUser() {
    // run if create user is submitted
    $('#createUser').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize(); // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessage();     // display error message
            },
            success: function () {
                $("#createUser")[0].reset();    // resett form
                $('#createUserModal').modal('hide');    // hide create user modal
                $('#errorMessage').remove();    // reomve error message
                UpdateUsersTable(); // update user tabel
                successMessageCreate(); // display success message
            }
        });
        return false;
    });
});


/**
 * Display error message on create user
 */
function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display success message on create user
 */
function successMessageCreate() {
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



/**
 * Search for users
 */
$(function searchForUser() {
    // run if form is submitted
    $('#searchForUser').submit(function () {
        var url = $(this).attr('action');   // get form action  
        var data = $(this).serialize(); // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForUser")[0].reset(); // reset form
                usersTableTemplate(data);       // update user tabel with result
            }
        });
        return false;
    });
});


/**
 * Update iser tabel
 */
function UpdateUsersTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getUserInfo',    // request given to controller
            dataType: 'json',
            success: function (data) {
                usersTableTemplate(data);   // display user tabel
            }
        });
    });
}



$('#dropdown').show();  // show administrator meny

/**
 * Get all user info
 */
$(function getAllUSerInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getUserInfo',    // request given to controller
        dataType: 'json',
        success: function (data) {
            usersTableTemplate(data);   // display user tabel
        }
    });
});


/**
 * Display passed users
 * takes given data and poplate template
 */
function usersTableTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("displayUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}


/**
 * Get information about selected user
 */
$(function getUserInformation() {
    //check if information button inside displayUserContainer is clicked
    $('#displayUserContainer').delegate('.information', 'click', function () {

        var givenUserID = $(this).attr('data-id');  // get data-id from button
        getUserRestriction(givenUserID);   // get userrestriction
        getGroupMembership(givenUserID);   // get group memberships
        $.ajax({
            type: 'POST',
            url: '?request=getUserByID',    // request given to controller
            data: {givenUserID: givenUserID},   // posted data to controller
            dataType: 'json',
            success: function (data) {
                $('#showUserInformationModal').modal('show');   // show information modal
                userInformationTemplate(data);  // display info about selected user
            }
        });
        return false;
    });
});



var givenUserID;

/**
 * get selected users restrictions
 */
function getUserRestriction(data) {
    givenUserID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getUserRestriction',     // request given to controller
            data: {givenUserID: givenUserID},   // posted data to controller
            dataType: 'json',
            success: function (data) {
                userRestrictionTemplate(data);  // display restrictions
            }
        });
    });
}

/**
 * get selected users group memberships
 */
function getGroupMembership(data) {
    givenUserID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupMembershipFromUserID',   // request given to controller
            data: {givenUserID: givenUserID},   // posted data to controller
            dataType: 'json',
            success: function (data) {
                groupMembershipTemplate(data);  // display group memberships
            }
        });
    });
}

/**
 * Delete group membership
 */
$(function DeleteGroupMembership() {
    //check if deleteGroupMembership button inside groupMembershipContainer is clicked
    $('#groupMembershipContainer').delegate('.deleteGroupMembership', 'click', function () {
        var memberID = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupMember',  // request given to controller
            data: {memberID: memberID}, //posted data to controller
            dataType: 'json',
            success: function (data) {
                getGroupMembership(givenUserID);    //update group memberships
            }
        });
        return false;
    });
});

/**
 * Display users memberships
 * takes given data and poplate template
 */
function groupMembershipTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("groupMembershipTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var userRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("groupMembershipContainer");
    userContainer.innerHTML = userRestrictionGeneratedHTML;
}

/**
 * delete selected users storage restrictions
 */
$(function deleteUserRestriction() {
    //check if product button inside withdrawProductContainer is clicked
    $('#userRestrictionContainer').delegate('.deleteRestriction', 'click', function () {
        var givenStorageID = $(this).attr('data-id');   // get data-id from bottun

        $.ajax({
            type: 'POST',
            url: '?request=deleteSingleRes',    // request given to controller
            data: {givenUserID: givenUserID, givenStorageID: givenStorageID},   // posted data
            dataType: 'json',
            success: function () {
                successMessageRes();    // display success message
                getUserRestriction(givenUserID);    // update users storage restrictions

            }
        });
        return false;

    });
});


/**
 * Display success message on deletetion
 */
function successMessageRes() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Brukertilgang er slettet. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display users restrictions
 * takes given data and poplate template
 */
function userRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("userRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("userRestrictionContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}


/**
 * Display user information
 * takes given data and poplate template
 */
function userInformationTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("userInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserInformationGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("userInformationContainer");
    userContainer.innerHTML = UserInformationGeneratedHTML;
}


/**
 * Get userinfo from user to delete
 */
$(function POSTdeleteUserModal() {
    //check if delete button inside displayUserContainer is clicked    
    $('#displayUserContainer').delegate('.delete', 'click', function () {
        var givenUserID = $(this).attr('data-id');  // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getUserByID',    // request given to controller
            data: {givenUserID: givenUserID},   // posted data
            dataType: 'json',
            success: function (data) {
                deleteUserTemplate(data);   // delete selected user
                $('#deleteUserModal').modal('show');    // show delete user modal
            }
        });
        return false;

    });
});


/**
 * Display user to be deleted
 * takes given data and poplate template
 */
function deleteUserTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("deleteUserContainer");
    userContainer.innerHTML = UserTableGeneratedHTML;
}


/**
 * Delete selected user
 */
$(function deleteUserByID() {
    // run if form is submitted
    $('#deleteUser').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize(); // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                successMessageDelete();     // display success message
                UpdateUsersTable();     // update user tabel
                $('#deleteUserModal').modal('hide');    // hide delete user modal
            }
        });
        return false;
    });
});


/**
 * Display success message on deleting
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Bruker er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * get information of user to be edited
 */
$(function editUserModal() {
        //check if edit button inside displayUserContainer is clicked
    $('#displayUserContainer').delegate('.edit', 'click', function () {
        var givenUserID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getUserByID',    // request given to controller
            data: {givenUserID: givenUserID},   //posted data
            dataType: 'json',
            success: function (data) {
                editUserTemplate(data); // display user to be edited
                $('#editUserModal').modal('show');  // show edit user modal
            }
        });
        return false;
    });
});



/**
 * Display edit user info
 * takes given data and poplate template
 */
function editUserTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editUserTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editUserGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("editUserContainer");
    userContainer.innerHTML = editUserGeneratedHTML;
}



$(function editUserInfo() {
    // run if from is submitted
    $('#editUser').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();     // display error message
            },
            success: function () {
                $('#editUserModal').modal('hide');  // hide edit user modal
                successMessageEdit();       // display success message
                UpdateUsersTable();     // update user tabel
            }
        });
        return false;
    });
});



/**
 * Display error message on user edit
 */
function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display success message on user edit
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Bruker er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Make give storage restriction, and add group member button visble on chackbox click
 */
$('#setRestriction').hide();
$('#displayUserContainer').delegate('.selectRestriction', 'click', function () {
    if ($(".selectRestriction").is(":checked") === true) {
        $('#setRestriction').show();
        $('#setGroupRestriction').show();
    } else {
        $('#setRestriction').hide();
        $('#setGroupRestriction').hide();
    }
});


/**
 * get all storage names in the system
 */
function getStorageInfo() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllStorageInfo',      // request given to controller
            dataType: 'json',
            success: function (data) {
                storageRestrictionTemplate(data);   // display storages 
            }
        });
    });
}


/**
 * Display storage to give users storage restriction
 * takes given data and poplate template
 */
function storageRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var userRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("storageRestrictionContainer");
    userContainer.innerHTML = userRestrictionGeneratedHTML;
}

/**
 * get group information
 */
function getGroupInfo() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getGroupSearchResult',   // request given to controller
            dataType: 'json',
            success: function (data) {
                groupRestrictionTemplate(data); // display all groupnames in system
            }
        });
    });
}

/**
 * Display group to give users group membership
 * takes given data and poplate template
 */
function groupRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("groupRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var userGroupRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("groupRestrictionContainer");
    userContainer.innerHTML = userGroupRestrictionGeneratedHTML;
}


/**
 * Give user restriction to selected storages
 */
$(function setRestrictionInfo() {
    $('#editRestriction').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#userRestrictionModal').modal('hide');   // hide user restriction modal
                $('#userGroupRestrictionModal').modal('hide');  // hide group restriction modal
                successMessageAddRes();     // display successmessage
                UpdateUsersTable();     // update user tabel
            }
        });
        return false;
    });
});

/**
 * Display success message on added restriction
 */
function successMessageAddRes() {
    $('<div class="alert alert-success"><strong>Lagret!</strong> Brukertilgang er lagret. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Get all media info in system
 */
function getMediaInfo() {
    
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllMediaInfo',    // request given to controller
            dataType: 'json',
            success: function (data) {
                // display all media names in a dropdown tabel
                var $displayMediaInformation = $('#selectMediaID'); 
                    $displayMediaInformation.empty();
                $.each(data.mediaInfo, function (i, item) {
                    $displayMediaInformation.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                });
            }
        });
    });
}


// remove merked checkboxes when closing user restriction modal
$(document).ready(function ()
{
    $('#userRestrictionModal').on('hidden.bs.modal', function (e)
    {
        $('input:checkbox').removeAttr('checked');
        $('#setRestriction').hide();
    });
});

// remove merked checkboxes when closing group restriction modal
$(document).ready(function ()
{
    $('#userGroupRestrictionModal').on('hidden.bs.modal', function (e)
    {
        $('input:checkbox').removeAttr('checked');
        $('#setRestriction').hide();
        $('#setGroupRestriction').hide();
    });
});
