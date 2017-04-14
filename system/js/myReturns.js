
$(function () {

    $.ajax({
        type: 'GET',
        url: '?page=getMyReturns',
        dataType: 'json',
        success: function (data) {
            myReturnsTemplate(data);
        }
    });
});



// Update return information -->

function UpdateReturnsTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getMyReturns',
            dataType: 'json',
            success: function (data) {
                myReturnsTemplate(data);
            }
        });
    });
}



function myReturnsTemplate(data) {
    var rawTemplate = document.getElementById("myReturnsTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mySalesnGeneratedHTML = compiledTemplate(data);

    var myReturnsContainer = document.getElementById("myReturnsContainer");
    myReturnsContainer.innerHTML = mySalesnGeneratedHTML;

}


// SEARCH FOR RETURNS -->


$(function POSTsearchForReturn() {

    $('#searchForReturns').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForReturns")[0].reset();
                myReturnsTemplate(data);
            }
        });
        return false;
    });
});




$(function editMyReturns() {
    $('#myReturnsContainer').delegate('.editReturns', 'click', function () {

        var givenReturnsID = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '?page=getReturnsFromID',
            data: {givenReturnsID: givenReturnsID},
            dataType: 'json',
            success: function (data) {
                editReturnsTemplate(data);
                $('#editReturnsModal').modal('show');
            }
        });
        return false;

    });
});


// Display edit sale Template -->

function editReturnsTemplate(data) {
    var rawTemplate = document.getElementById("editReturnTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editReturnGeneratedHTML = compiledTemplate(data);

    var returnContainer = document.getElementById("editReturnContainer");
    returnContainer.innerHTML = editReturnGeneratedHTML;
}


// POST results from editing, and updating the table-->

$(function POSTeditReturnsInfo() {

    $('#editReturn').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editReturnsModal').modal('hide');
                UpdateReturnsTable();
            }
        });
        return false;
    });
});


