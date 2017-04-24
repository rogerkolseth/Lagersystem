
$(function () {

    $.ajax({
        type: 'GET',
        url: '?page=getMyReturns',
        dataType: 'json',
        success: function (data) {
            myReturnsTemplate(data);
            userReturnTemplate(data);
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
                userReturnTemplate(data);
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


function userReturnTemplate(data) {
    var $usernameTemplate = $('#chooseUserReturnContainer');
    $usernameTemplate.empty();
    $usernameTemplate.append('<tr><td id="bordernone">Alle</td> <td id="bordernone"><input id="chooseUserSale" type="checkbox" name="username[]" value="0"></td></tr>');
    $.each(data.usernames, function (i, item) {
        $usernameTemplate.append('<tr><td id="bordernone">' + item.username +'</td> <td id="bordernone"><input id="chooseUserReturn" type="checkbox" name="username[]" value="'+item.userID+'"></td></tr>');
    });
    $usernameTemplate.append(' <input class="form-control btn btn-primary" type="submit" form="showUserReturn"  value="Velg">');
}

$(function POSTshowUserReturn() {

    $('#showUserReturn').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                myReturnsTemplate(data);
            }
        });
        return false;
    });
});