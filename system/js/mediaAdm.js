
$('#dropdown').show();  // opens administration meny 

/**
 * Gets all media information
 */
$(function getAllMediaInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getAllMediaInfo',    // given request to controller
        dataType: 'json',
        success: function (data) {
            mediaDisplayTemplate(data);     // populate media table (display media)
        }
    });
});

/**
 * Updates media table
 */
function UpdateMediaTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllMediaInfo',    // given request to controller  
            dataType: 'json',
            success: function (data) {
                mediaDisplayTemplate(data);     // populate media table (display media)
            }
        });
    });
}


/**
 * Show media 
 * takes given data and poplate template
 */
function mediaDisplayTemplate(data) {
//takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var mediaDisplayGeneratedHTML = compiledTemplate(data);
// display template in choosen ID tag
    var mediaContainer = document.getElementById("displayMediaContainer");
    mediaContainer.innerHTML = mediaDisplayGeneratedHTML;
}



/**
 * Search for media 
 */
$(function searchForMedia() {
    // run if searchForMedia form is submitted
    $('#searchForMedia').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForMedia")[0].reset();    // reet search for media fomr
                mediaDisplayTemplate(data);         // display result in table
            }
        });
        return false;
    });
});


/**
 * Display selected media in modal
 */
function showMedia(givenMediaID) {
    $('#showMediaInformationModal').modal('show');  // opens info modal
    $.ajax({
        type: 'POST',
        url: '?request=getMediaByID',   // given request for controller
        data: {givenMediaID: givenMediaID}, // data to be posted to controller
        dataType: 'json',
        success: function (data) {
            // populate from recived data
            $.each(data.mediaInfo, function (i, item) {
                // set element-id to be populated, and populate it
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



/**
 * Display edit media options
 */
$(function editMedia() {
    //check if edit button inside displayMediaContainer is clicked
    $('#displayMediaContainer').delegate('.edit', 'click', function () {
        var givenMediaID = $(this).attr('data-id'); // get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getMediaByID',       // given request to controller  
            data: {givenMediaID: givenMediaID}, // post data to controller
            dataType: 'json',
            success: function (data) {
                $('#editMediaModal').modal('show');     // show edit media modal    
                editMediaTemplate(data);                // run edit media template do display options
            }
        });
        return false;

    });
});


/**
 * display edit media
 * takes given data and poplate template
 */
function editMediaTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editMediaGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var editContainer = document.getElementById("editMediaContainer");
    editContainer.innerHTML = editMediaGeneratedHTML;
}



/**
 * update media information, and update table
 */
$(function updateMediaInfo() {
    // run if edit media form is submitted
    $('#editMedia').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $('#editMediaModal').modal('hide'); // hide edit media modal
                successMessageEdit();       // display success message
                UpdateMediaTable();     // update media table
            }
        });
        return false;
    });
});



/**
 * Display success message on media edit
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Media er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Select media to be deleted
 */
$(function selectMediaDelete() {
    //check if delete button inside displayMediaContainer is clicked
    $('#displayMediaContainer').delegate('.delete', 'click', function () {
        var givenMediaID = $(this).attr('data-id');
        $('#deleteMediaModal').modal('show');
        $.ajax({
            type: 'POST',
            url: '?request=getMediaByID',   // given request to controller
            data: {givenMediaID: givenMediaID},     // post data to controller
            dataType: 'json',
            success: function (data) {
                deleteMediaTemplate(data);      // pass media to delete to template
                $('#deleteMediaModal').modal('show');   // show delete media modal
            }
        });
        return false;

    });
});

/**
 * Display success message on media delete
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Media er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/**
 * Display error message on media delete
 */
function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slette media som er i bruk. </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

  
/**
 * display delete media
 * takes given data and poplate template
 */
function deleteMediaTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteMediaTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteMediaGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var mediaContainer = document.getElementById("deleteMediaContainer");
    mediaContainer.innerHTML = deleteMediaGeneratedHTML;
}



/**
 * Delete selected media
 */
$(function deleteMediaByID() {
    // run if delete media form is submitted
    $('#deleteMedia').submit(function () {
        var url = $(this).attr('action');   // get form action
        var data = $(this).serialize();     // serialize form data

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();   // display error message
            },
            success: function (data) {
                UpdateMediaTable();     // update media table
                $('#deleteMediaModal').modal('hide');   // hide delete media modal
                successMessageDelete();     // display success message
            }
        });
        return false;
    });
});

/**
 * Get category information and populate dropdown meny
 */
function getCategoryInfo() {
    // set element-id to be popualte and empty it
    var $displayCategoryInformation = $('#selectCategoryID');
    $displayCategoryInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getAllCategoryInfo', // request given to controller
            dataType: 'json',
            success: function (data) {
                $.each(data.categoryInfo, function (i, item) {
                    // populate given element with option from categoryInfo array
                    $displayCategoryInformation.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');

                });


            }
        });
    });
}

/**
 * Get categories which contains media
 */
$(function getCatWithMedia() {
    $.ajax({
        type: 'GET',
        url: '?request=getCatWithMedia',    // request given to controller
        dataType: 'json',
        success: function (data) {
            chooseCategory(data);   // populate dropdown meny
        }
    });
});


/**
 * display categories to be choosen from
 * takes given data and poplate template
 */
function chooseCategory(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}


/*
 * Update media table from category search
 */
$(function updateResultFromCategory() {
    //check if user have changed category option
    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');   //get selected categoryID

        $.ajax({
            type: 'POST',
            url: '?request=getMediaFromCategory',   // request given to controller
            data: {givenCategoryID: givenCategoryID},   // data posted to controller
            dataType: 'json',
            success: function (data) {
                mediaDisplayTemplate(data); // update media table
            }
        });
        return false;
    });
});
