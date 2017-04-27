
$('#dropdown').show();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllMediaInfo',
        dataType: 'json',
        success: function (data) {
            mediaDisplayTemplate(data);
        }
    });
});


// Update storage information -->

function UpdateMediaTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {
                mediaDisplayTemplate(data);
            }
        });
    });
}


// Display mdia template -->

function mediaDisplayTemplate(data) {

    var rawTemplate = document.getElementById("displayMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mediaDisplayGeneratedHTML = compiledTemplate(data);

    var mediaContainer = document.getElementById("displayMediaContainer");
    mediaContainer.innerHTML = mediaDisplayGeneratedHTML;
}




$(function POSTsearchForMedia() {

    $('#searchForMedia').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForMedia")[0].reset();
                mediaDisplayTemplate(data);
            }
        });
        return false;
    });
});




function showMedia(givenMediaID) {
    $('#showMediaInformationModal').modal('show');
    $.ajax({
        type: 'POST',
        url: '?page=getMediaByID',
        data: {givenMediaID: givenMediaID},
        dataType: 'json',
        success: function (data) {

            $.each(data.mediaInfo, function (i, item) {

                var $mediaTitle = $('#mediaTitle');
                $mediaTitle.empty().append(item.mediaName);

                var mediaCategory = $('#mediaCategory');
                mediaCategory.empty().append('<p><b>Kategori: </b>' + item.categoryName + '</p>');

                var $displayMediaInformation = $('#mediaInformationContainer');
                $displayMediaInformation.empty().append('<img class="img-responsive" src="image/' + item.mediaName + '"alt="' + item.mediaName + '">');

            });
        }
    });
    return false;

}
;



// EDIT STORAGE -->


$(function POSTeditMediaModal() {

    $('#displayMediaContainer').delegate('.edit', 'click', function () {
        var givenMediaID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?page=getMediaByID',
            data: {givenMediaID: givenMediaID},
            dataType: 'json',
            success: function (data) {
                $('#editMediaModal').modal('show');
                editMediaTemplate(data);
            }
        });
        return false;

    });
});



function editMediaTemplate(data) {
    var rawTemplate = document.getElementById("editMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editMediaGeneratedHTML = compiledTemplate(data);

    var editContainer = document.getElementById("editMediaContainer");
    editContainer.innerHTML = editMediaGeneratedHTML;
}



// POST results from editing, and updating the table-->

$(function POSTeditMediaInfo() {

    $('#editMedia').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editMediaModal').modal('hide');
                successMessageEdit();
                UpdateMediaTable();

            }
        });
        return false;
    });
});




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Media er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



//   DELETE MEDIA     -->


// Display what media to delete storage modal -->

$(function POSTdeleteMediaeModal() {

    $('#displayMediaContainer').delegate('.delete', 'click', function () {
        var givenMediaID = $(this).attr('data-id');
        $('#deleteMediaModal').modal('show');
        $.ajax({
            type: 'POST',
            url: '?page=getMediaByID',
            data: {givenMediaID: givenMediaID},
            dataType: 'json',
            success: function (data) {
                deleteMediaTemplate(data);
                $('#deleteMediaModal').modal('show');
            }
        });
        return false;

    });
});



function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Media er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slette media som er i bruk. </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


// Delete media template-->         

function deleteMediaTemplate(data) {
    var rawTemplate = document.getElementById("deleteMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteMediaGeneratedHTML = compiledTemplate(data);

    var mediaContainer = document.getElementById("deleteMediaContainer");
    mediaContainer.innerHTML = deleteMediaGeneratedHTML;
}


// Delete the media that is selected-->

$(function deleteMediaByID() {

    $('#deleteMedia').submit(function () {
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

                UpdateMediaTable();
                $('#deleteMediaModal').modal('hide');
                successMessageDelete();

            }
        });
        return false;
    });
});



function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryID');
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



$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getCatWithMedia',
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);
        }
    });
});


// Display storage template -->

function chooseCategory(data) {
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}



$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?page=getMediaFromCategory',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                mediaDisplayTemplate(data);
            }
        });
        return false;
    });
});
