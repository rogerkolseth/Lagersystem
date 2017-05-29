$('#dropdown').show();  //show administrator meny

/**
 * Add new group to database
 */
$(function createGroup() {
    // run function if createGroup form is submitted
    $('#createGroup').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageCreate();   // display error message
            },
            success: function () {
                $("#createGroup")[0].reset();   //reset createGroup form
                $('#createGroupModal').modal('hide');   //hide create group moal
                UpdateGroupTable(); //update group table
            }
        });
        return false;
    });
});

/**
 * Display errormessage
 */
function errorMessageCreate() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorCreate')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Gets all group info
 */
$(function getAllGroupInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getGroupSearchResult',
        dataType: 'json',
        success: function (data) {
            groupTableTemplate(data);
        }
    });
});


/**
 * update group table
 */

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

/**
 * search for group
 */
$(function searchForGroup() {
    // run function on form submit
    $('#searchForGroup').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForGroup")[0].reset();    // resett search form
                groupTableTemplate(data);   // pass array to handlebars template
            }
        });
        return false;
    });
});


/**
 * Display Group Table Template
 */
function groupTableTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var groupContainer = document.getElementById("displayGroupContainer");
    groupContainer.innerHTML = groupTableGeneratedHTML;
}

/**
 * Get group information from group ID
 */

$(function getGroupByID() {
    //check if delete button inside displayGroupContainer is clicked
    $('#displayGroupContainer').delegate('.delete', 'click', function () {
        var givenGroupID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                deleteGroupTemplate(data);  // run handlebars template
                $('#deleteGroupModal').modal('show');   //show delet group modal
            }
        });
        return false;

    });
});

/**
 * Display delete group template
 */
function deleteGroupTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var deleteContainer = document.getElementById("deleteGroupContainer");
    deleteContainer.innerHTML = deleteTableGeneratedHTML;
}


/**
 * delete group from given group ID
 */
$(function deleteGroupByID() {
    // run on form submit
    $('#deleteGroup').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();   //display errormessage
            },
            success: function (data) {
                UpdateGroupTable(); // update group table
                successMessageDelete(); //display success message
                $('#deleteGroupModal').modal('hide');   //hide delete group modal
            }
        });
        return false;
    });
});

/**
 * Display success message on deleting group
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Gruppen er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * get group info from groupID
 */
$(function getEditGroupByID() {
    //check if edit button inside displayGroupContainer is clicked
    $('#displayGroupContainer').delegate('.edit', 'click', function () {
        var givenGroupID = $(this).attr('data-id'); // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID}, //pass groupID 
            dataType: 'json',
            success: function (data) {
                editGroupTemplate(data);    // pass array to editGroup template
                $('#editGroupModal').modal('show'); //show edit group modal
            }
        });
        return false;
    });
});


/**
 * Display edit group template
 */
function editGroupTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editGroupTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editGroupGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var groupContainer = document.getElementById("editGroupContainer");
    groupContainer.innerHTML = editGroupGeneratedHTML;
}



/**
 * Update existing group info
 */
$(function editGroup() {
    // run function on form submit
    $('#editGroup').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();     // display error message
            },
            success: function () {
                $('#editGroupModal').modal('hide'); // hide edit group modal
                successMessageEdit();   // display succes message
                UpdateGroupTable();     // update group table
            }
        });
        return false;
    });
});



/**
 * Display success message on edit group
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Kategori er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

/**
 * Display error message on edit group
 */
function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Get group information from groupID
 */

$(function getGroupInformation() {
    //check if information button inside displayGroupContainer is clicked
    $('#displayGroupContainer').delegate('.information', 'click', function () {
        var givenGroupID = $(this).attr('data-id'); // get data-id of clicked button
        getGroupMember(givenGroupID);  //pass variable to get group members
        getGroupRestriction(givenGroupID);  // pass variable to get storage restriction to group 
        $.ajax({
            type: 'POST',
            url: '?request=getGroupByID',
            data: {givenGroupID: givenGroupID}, // pass given groupID
            dataType: 'json',
            success: function (data) {
                $('#showGroupInformationModal').modal('show');  // show group information modal
                groupInformationTemplate(data); // pass array to group information template

            }
        });
        return false;

    });
});
var givenGroupID;
/**
 * get group members of given groupID
 */
function getGroupMember(data) {
    givenGroupID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupMember',
            data: {givenGroupID: givenGroupID},
            dataType: 'json',
            success: function (data) {
                groupMemberTemplate(data);  // pass array to group member template
            }
        });
    });
}

/**
 * Delete selected group member
 */
$(function DeleteGroupMember() {
    //check if deleteMember button inside groupMemberContainer is clicked
    $('#groupMemberContainer').delegate('.deleteMember', 'click', function () {
        var memberID = $(this).attr('data-id'); //get data-id from button
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupMember',
            data: {memberID: memberID},
            dataType: 'json',
            success: function (data) {
                getGroupMember(givenGroupID); //update group member table
            }
        });
        return false;
    });
});

/**
 *  Display group member template
 */
function groupMemberTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("groupMemberTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("groupMemberContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}

/**
 * get storage restrictions to selected group
 */
function getGroupRestriction(data) {
     givenGroupID = data;
    $(function () {
        $.ajax({
            type: 'POST',
            url: '?request=getGroupRestriction',
            data: {givenGroupID: givenGroupID}, // pass variable to controller
            dataType: 'json',
            success: function (data) {
                groupRestrictionTemplate(data); // pass array to group restriction template
            }
        });
    });
}

/**
 * Delete a groups storage restriction
 */
$(function DeleteGroupRestriction() {
    //check if deleteStorageRestriction button inside storageGroupResContainer is clicked
    $('#storageGroupResContainer').delegate('.deleteStorageRestriction', 'click', function () {
        var restrictionID = $(this).attr('data-id');  // get data-id from button 
        $.ajax({
            type: 'POST',
            url: '?request=deleteGroupRestriction', // pass request to controller
            data: {restrictionID: restrictionID},   // pass restrictionID to controller
            dataType: 'json',
            success: function () {
                getGroupRestriction(givenGroupID); //update group restrictions
            }
        });
        return false;
    });
});

/**
 * Display group restriction template
 */
function groupRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageGroupResTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var UserRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var userContainer = document.getElementById("storageGroupResContainer");
    userContainer.innerHTML = UserRestrictionGeneratedHTML;
}


/**
 * Display group information template
 */
function groupInformationTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("groupInformationTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupInformationGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var groupContainer = document.getElementById("groupInformationContainer");
    groupContainer.innerHTML = groupInformationGeneratedHTML;
}


/**
 * Get storage names, and populate restriction modal
 */
$(function getStorageInfo() {
    //check if groupRestriction button inside displayGroupContainer is clicked
    $('#displayGroupContainer').delegate('.groupRestriction', 'click', function () {
        var givenGroupID = $(this).attr('data-id');
         $.ajax({
            type: 'GET',
            url: '?request=getAllStorageInfo',  // pass request to controller
            dataType: 'json',
            success: function (data) {
                var $displayGroupID = $('#groupID');    //set elementID to populate
                $displayGroupID.empty();    // empty elemet 
                // populate element with hidden groupID input
                $displayGroupID.append('<input id="'+ givenGroupID +'" name="givenGroupID" class="form-control"  form="editGroupRestriction"  value="'+givenGroupID+'" type="hidden"/>');
                // pass array to storage restriction template
                storageRestrictionTemplate(data);
            }
        });
    });
});


/**
 * Display storage restriction container
 */

function storageRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var groupContainer = document.getElementById("storageRestrictionContainer");
    groupContainer.innerHTML = groupRestrictionGeneratedHTML;
}


/**
 * Add new group restriction to a storage
 */
$(function addRestriction() {
    $('#editGroupRestriction').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#groupRestrictionModal').modal('hide');  //hide group restriction modal
                successMessageAddRes(); // display success message
                UpdateGroupTable(); //update group table
            }
        });
        return false;
    });
});

/**
 * Display success message on adding new restriction
 */
function successMessageAddRes() {
    $('<div class="alert alert-success"><strong>Lagret!</strong> Gruppetilgangen(e) er lagret. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



/**
 * Get user information to populate add member modal
 */
$(function getUserInformation() {
    //check if addUser button inside displayGroupContainer is clicked
    $('#displayGroupContainer').delegate('.addUser', 'click', function () {
        var givenGroupID = $(this).attr('data-id');// get data-id from button

        $.ajax({
            type: 'GET',
            url: '?request=getUserInfo',    // send request to controller
            dataType: 'json',
            success: function (data) {
                var $displayGroupID = $('#groupUserID');    // set elementID to populate
                $displayGroupID.empty();    // empty elementID
                // populate hidden input for groupID
                $displayGroupID.append('<input id="'+ givenGroupID +'" name="givenGroupID" class="form-control"  form="addGroupMember"  value="'+givenGroupID+'" type="hidden"/>');
                userRestrictionTemplate(data); // pass array to user restriction template
            }
        });


    });
});


/**
 * Display user restriction template
 */
function userRestrictionTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("userRestrictionTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var groupRestrictionGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var groupContainer = document.getElementById("userRestrictionContainer");
    groupContainer.innerHTML = groupRestrictionGeneratedHTML;
}

/**
 * add new group member
 */
$(function addGroupMember() {
    // run function on form submit
    $('#addGroupMember').submit(function () {   
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize();     //serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#userMemberModal').modal('hide');    // hide user member modal
                successMessageAddRes(); // display succes message
                UpdateGroupTable(); // update group table
            }
        });
        return false;
    });
});


