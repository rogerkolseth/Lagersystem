

$('#dropdown').show();  //show administrator meny

/**
 *  Add new category
 */
$(function createCategory() {
    // run function if createCategory form is submitted
    $('#createCategory').submit(function () {
        var url = $(this).attr('action'); //gets action url from form
        var data = $(this).serialize(); //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function () {
                $("#createCategory")[0].reset();    //reset createCategory form
                $('#createCategoryModal').modal('hide');    //hide modal 
                UpdateCategoryTable();  //updates category table
            }
        });
        return false;
    });
});



/**
 * Get all categories from controller
 */ 
$(function getCategoryInfo() {
    $.ajax({
        type: 'GET',
        url: '?request=getCategorySearchResult',
        dataType: 'json',
        success: function (data) {
            categoryTableTemplate(data);    //pass array to handlebar template
        }
    });
});


/**
 * Update category information
 */ 
function UpdateCategoryTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getCategorySearchResult',
            dataType: 'json',
            success: function (data) {
                categoryTableTemplate(data);    //pass array to handlebar template
            }
        });
    });
}


/**
 * Search for category 
 */ 

$(function searchForCategory() {
    // run function if searchForCategory fomr is submitted
    $('#searchForCategory').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize(); //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#searchForCategory")[0].reset();     //resett submitted form
                categoryTableTemplate(data);    //pass array to handlebar template

            }
        });
        return false;
    });
});


/**
 * Display categoryTable template
 */ 
function categoryTableTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("displayCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var categoryTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var categoryContainer = document.getElementById("displayCategoryContainer");
    categoryContainer.innerHTML = categoryTableGeneratedHTML;
}



$(function deleteCategory() {
    //check if delete button inside displayCategoryContainer is clicked
    $('#displayCategoryContainer').delegate('.delete', 'click', function () {
        var givenCategoryID = $(this).attr('data-id');  //get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getCategoryByID',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                deleteCategoryTemplate(data);   //pass array to handlebar template
                $('#deleteCategoryModal').modal('show');    //show deleteCategory modal
            }
        });
        return false;
    });
});


/**
 * Display delete category template
 */ 
function deleteCategoryTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("deleteCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteTableGeneratedHTML = compiledTemplate(data);
    // display template in choosen ID tag
    var deleteContainer = document.getElementById("deleteCategoryContainer");
    deleteContainer.innerHTML = deleteTableGeneratedHTML;
}

/**
 * Delete category from given ID
 */
$(function deleteCategoryByID() {
    //run function if form is submitted
    $('#deleteCategory').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize(); //serialize data in form

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();   //display errormessage
            },
            success: function () {
                UpdateCategoryTable();  //update category table
                successMessageDelete(); //show success message
                $('#deleteCategoryModal').modal('hide');    // hide modal
            }
        });
        return false;
    });
});

/*
 * Shows a success message for deleting category
 */
function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Kategori er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/*
 * Shows an error message, for not being able to delete category
 */
function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slettes da kategorien er i bruk </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




/**
 * Get the selected storage, and opens editStorage modal
 */
$(function getCategoryFromID() {
    //check if edit button inside displayCategoryContainer is clicked
    $('#displayCategoryContainer').delegate('.edit', 'click', function () {
        var givenCategoryID = $(this).attr('data-id');  //get data-id from button

        $.ajax({
            type: 'POST',
            url: '?request=getCategoryByID',
            data: {givenCategoryID: givenCategoryID},   //post given ID
            dataType: 'json',
            success: function (data) {
                editCategoryTemplate(data); // pass array to handlebars template
                $('#editCategoryModal').modal('show');  //show edit category modal
            }
        });
        return false;

    });
});



/**
 * Dispaly edit category template
 */ 
function editCategoryTemplate(data) {
    //takes template and populate it with passed array
    var rawTemplate = document.getElementById("editCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editStorageGeneratedHTML = compiledTemplate(data);
     // display template in choosen ID tag
    var storageContainer = document.getElementById("editCategoryContainer");
    storageContainer.innerHTML = editStorageGeneratedHTML;
}


/**
 * edit category
 */
$(function editCategory() {
    //run function if form is submitted
    $('#editCategory').submit(function () {
        var url = $(this).attr('action');   //gets action url from form
        var data = $(this).serialize(); //serialize data in form
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit(); // display error message
            },
            success: function () {
                $('#editCategoryModal').modal('hide');  //hide edit category modal
                successMessageEdit();   // display success message
                UpdateCategoryTable();  //update category tabel
            }
        });
        return false;
    });
});



/*
 * Shows an success message, for editing category
 */
function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Kategori er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


/*
 * Shows an error message, for not being able to edit category
 */
function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

