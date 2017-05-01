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
            success: function () {
                $("#createGroup")[0].reset();
                $('#createGroupModal').modal('hide');
                UpdateGroupTable();
            }
        });
        return false;
    });
});

$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getGroupSearchResult',
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
            url: '?page=getGroupSearchResult',
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