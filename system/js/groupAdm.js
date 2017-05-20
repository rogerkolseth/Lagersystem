$('#dropdown').show();
$(function POSTgroupInfo() {

    $('#createGroup').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageCreate();
            },
            success: function () {
                $("#createGroup")[0].reset();
                $('#createGroupModal').modal('hide');
                UpdateGroupTable();
            }
        });
        return false;
    });
});

function errorMessageCreate() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorCreate')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

$(function () {
    $.ajax({
        type: 'GET',
        url: '?request=getGroupSearchResult',
        dataType: 'json',
        success: function (data) {
            groupTableTemplate(data);
        }
    });
});


//Update category information 

function UpdateGroupTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getGroupSearchResult',
            dataType: 'json',
            success: function (data) {
                groupTableTemplate(data);
            }
        });
    });
}

$(function POSTsearchForStorage() {

    $('#searchForGroup').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForGroup")[0].reset();
                groupTableTemplate(data);
            }
        });
        return false;
    });
});

// DISPLAY CATEGORY TEMPLATE 

function groupTableTemplate(data) {
    var rawTemplate = document.getElementById("displayGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupTableGeneratedHTML = compiledTemplate(data);

    var groupContainer = document.getElementById("displayGroupContainer");
    groupContainer.innerHTML = groupTableGeneratedHTML;
}

// DELETE GROUP TEMPLATE     
$(function POSTdeleteUserModal() {

    $('#displayGroupContainer').delegate('.delete', 'click', function () {
        var givenGroupID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                deleteGroupTemplate(data);
                $('#deleteGroupModal').modal('show');
            }
        });
        return false;

    });
});

function deleteGroupTemplate(data) {
    var rawTemplate = document.getElementById("deleteGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteTableGeneratedHTML = compiledTemplate(data);

    var deleteContainer = document.getElementById("deleteGroupContainer");
    deleteContainer.innerHTML = deleteTableGeneratedHTML;
}



$(function deleteGroupByID() {

    $('#deleteGroup').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();
            },
            success: function (data) {
                UpdateGroupTable();
                successMessageDelete();
                $('#deleteGroupModal').modal('hide');
            }
        });
        return false;
    });
});

function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Gruppen er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


//  Get the selected group, and opens editGroup modal-->

$(function POSTeditGroupModal() {

    $('#displayGroupContainer').delegate('.edit', 'click', function () {
        var givenGroupID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                editGroupTemplate(data);
                
                $('#editGroupModal').modal('show');
            }
        });
        return false;

    });
});


// Display edit group Template

function editGroupTemplate(data) {
    var rawTemplate = document.getElementById("editGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editGroupGeneratedHTML = compiledTemplate(data);

    var groupContainer = document.getElementById("editGroupContainer");
    groupContainer.innerHTML = editGroupGeneratedHTML;
}


// POST results from editing, and updating the table

$(function POSTeditGroupInfo() {

    $('#editGroup').submit(function () {
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
                $('#editGroupModal').modal('hide');
                successMessageEdit();
                UpdateGroupTable();
            }
        });
        return false;
    });
});




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Kategori er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// SHOW GROUP INFORMATION -->

// get information from selected product-->

$(function POSTgroupInformationModal() {

    $('#displayGroupContainer').delegate('.information', 'click', function () {
        var givenGroupID = $(this).attr('data-id');
        POSTgroupMember(givenGroupID);
        POSTgroupRestriction(givenGroupID);
        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                $('#showGroupInformationModal').modal('show');
                groupInformationTemplate(data);

            }
        });
        return false;

    });
});
var givenGroupID
function POSTgroupMember(data) {
    givenGroupID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupMember',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                groupMemberTemplate(data);
            }
        });
    });
}

$(function DeleteGroupMember() {
    $('#groupMemberContainer').delegate('.deleteMember', 'click', function () {
        var memberID = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupMember',
            data: {memberID: memberID},
            dataType: 'json',
            success: function (data) {
                POSTgroupMember(givenGroupID);
            }
        });
        return false;
    });
});

function groupMemberTemplate(data) {
    var rawTemplate = document.getElementById("groupMemberTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("groupMemberContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}

function POSTgroupRestriction(data) {
     givenGroupID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupRestriction',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                groupRestrictionTemplate(data);
            }
        });
    });
}

$(function DeleteGroupRestriction() {
    $('#storageGroupResContainer').delegate('.deleteStorageRestriction', 'click', function () {
        var restrictionID = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupRestriction',
            data: {restrictionID: restrictionID},
            dataType: 'json',
            success: function (data) {
                POSTgroupRestriction(givenGroupID);
            }
        });
        return false;
    });
});

function groupRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("storageGroupResTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);

    var userContainer = document.getElementById("storageGroupResContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}


// Display storageInformation Template-->

function groupInformationTemplate(data) {
    var rawTemplate = document.getElementById("groupInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupInformationGeneratedHTML = compiledTemplate(data);

    var groupContainer = document.getElementById("groupInformationContainer");
    groupContainer.innerHTML = groupInformationGeneratedHTML;
}


// RESTRICTION

// Get storage information-->
$(function POSTgroupResModal() {

    $('#displayGroupContainer').delegate('.groupRestriction', 'click', function () {
        var givenGroupID = $(this).attr('data-id');
         $.ajax({
            type: 'GET',
            url: '?request=getAllStorageInfo',
            dataType: 'json',
            success: function (data) {
                var $displayGroupID = $('#groupID');
                $displayGroupID.empty();
                $displayGroupID.append('<input id="'+ givenGroupID +'" name="givenGroupID" class="form-control"  form="editGroupRestriction"  value="'+givenGroupID+'" type="hidden"/>');

                storageRestrictionTemplate(data);
            }
        });
    });
});




// Genereate userRestriciton template and display it in contaioner-->

function storageRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupRestrictionGeneratedHTML = compiledTemplate(data);

    var groupContainer = document.getElementById("storageRestrictionContainer");
    groupContainer.innerHTML = groupRestrictionGeneratedHTML;
}


// Post new restriction-->

$(function POSTrestrictionInfo() {
    $('#editGroupRestriction').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#groupRestrictionModal').modal('hide');
                successMessageAddRes();
                UpdateGroupTable();
            }
        });
        return false;
    });
});

function successMessageAddRes() {
    $('<div class="alert alert-success"><strong>Lagret!</strong> Gruppetilgangen(e) er lagret. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// SHOW PRODUCT INFORMATION -->

// get information from selected product-->

$(function GetUserInformationModal() {
    $('#displayGroupContainer').delegate('.addUser', 'click', function () {
        var givenGroupID = $(this).attr('data-id');

        $.ajax({
            type: 'GET',
            url: '?request=getUserInfo',
            dataType: 'json',
            success: function (data) {
                var $displayGroupID = $('#groupUserID');
                $displayGroupID.empty();
                $displayGroupID.append('<input id="'+ givenGroupID +'" name="givenGroupID" class="form-control"  form="addGroupMember"  value="'+givenGroupID+'" type="hidden"/>');

                userRestrictionTemplate(data);
            }
        });


    });
});

// Genereate userRestriciton template and display it in contaioner-->

function userRestrictionTemplate(data) {
    var rawTemplate = document.getElementById("userRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupRestrictionGeneratedHTML = compiledTemplate(data);

    var groupContainer = document.getElementById("userRestrictionContainer");
    groupContainer.innerHTML = groupRestrictionGeneratedHTML;
}

$(function addGroupMember() {
    $('#addGroupMember').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#userMemberModal').modal('hide');
                successMessageAddRes();
                UpdateGroupTable();
            }
        });
        return false;
    });
});


