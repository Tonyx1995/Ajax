/*
Library written for various functions needed for this example of how ajax works with PHP/jQuery.
Author: Tony Reed
*/

$(document).ready(function(){
    //Declaring new object and specifying parameters to be used in various functions.
    //Makes it easier for future changes being made in one spot, versus all over.
    //JSON Syntax
    var parameterObject = {
        //loadingGifPath:"lib/Styles/loading.gif"
        loadingGifPath:"lib/Styles/ajax-loader-trans.gif",
        ModalBodyPath:"lib/modals/",
        imagePath:"lib/Styles/pics/",
        foodID:0,
        lastInsertID:0
    };

    /*
    For logging in, registering, and logging out.
    */

    //Register action
    $("#registerForm").submit(function(e){
        //Stop form from refreshing page.
        e.preventDefault();

        var username = $("#regUsername").val();
        var password = $("#regPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        var valid = true;

        if(password != confirmPassword) {
            valid = false;
        }

        if(!password || !confirmPassword || !username){
            valid = false;
        }

        if(valid){
            register(parameterObject);
        }else{
            $(".passwordAlert").show();
        }
    });

    //Login action
    $("#loginForm").submit(function(e){
        //Stop form from refreshing page.
        e.preventDefault();

        var username = $("#username").val();
        var password = $("#password").val();
        var valid = true;

        if(!password || !username){
            valid = false;
        }

        if(valid){
            login(parameterObject);
        }else{
            $(".passwordAlert").show();
        }
    });

    //Logout action
    $("#logoutForm").submit(function(e){
        //Stop form from refreshing page.
        e.preventDefault();

        logout(parameterObject);
    });

    /*
    -------------------------------------------------------------------------------------------------------------------------------------------------------
    */

    /*
    Admin options handler
    */
    $('#editFoodModal').on('show.bs.modal', function (e) {

        var invoker = $(e.relatedTarget).data('id');

        /*
        This if-else is for going back from the upload modal to the edit modal.
        We set a field in our parameterObject to hold our food id.
        When we come back from the upload modal to the edit modal the food_ID is lost, but not if we store it in the object and assign it as below.
         */
        if(typeof(invoker) == "undefined"){
            invoker = parameterObject.foodID;
        }else if(typeof(invoker) == "number"){
            //Set object ID = to invoker
            parameterObject.foodID = invoker;
        }

        //Set the loading image while ajax renders
        $('.edit-food-modal-body').addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

        //This ajax call loads up the modal-body with the form we receive.
        //(jQuery validation is inside the success callback function)
        $.ajax({
            type: 'post',
            url: parameterObject.ModalBodyPath+'edit_food.php',
            data: { food_id:  invoker },
            success: function (r) {
                //Inside the success return function for the ajax call is where we are validating and using jQuery with our
                //Newly-passed form.
                $('.edit-food-modal-body').html(r);

                //Initialize text counter
                countTextArea();

                //Initialize our datepickers.
                $('.edit-food-modal-body .datepicker').datepicker();

                /* Toggles for cyclone & sales */
                $('.edit-food-modal-body .showCyclone').click(function(){
                    $('.edit-food-modal-body .displayCyclonePrice').show(100);
                });

                $('.edit-food-modal-body .hideCyclone').click(function(){
                    $('.edit-food-modal-body .displayCyclonePrice').hide(100);
                    $('.edit-food-modal-body .cyclone-card-price').val("");
                });

                $('.edit-food-modal-body .showSales').click(function(){
                   $('.edit-food-modal-body .displaySales').show(100);
                });

                $('.edit-food-modal-body .hideSales').click(function(){
                    $('.edit-food-modal-body .displaySales').hide(100);

                    //Reset values on hide
                    $('.edit-food-modal-body .sale-price').val("");
                    $('.edit-food-modal-body .sale-start-date').val("");
                    $('.edit-food-modal-body .sale-end-date').val("");
                });

                /* Validation on the edit food modal */
                $("#edit_food_form").submit(function(e) {
                    var name = $('.edit-food-modal-body .food-name').val();
                    var desc = $('.edit-food-modal-body .food-desc').val();
                    var cyclonePrice = parseFloat($('.edit-food-modal-body .cyclone-card-price').val());
                    var price = parseFloat($('.edit-food-modal-body .regular-price').val());
                    var salePrice = parseFloat($('.edit-food-modal-body .sale-price').val());
                    var saleStart = $('.edit-food-modal-body .sale-start-date').val();
                    var saleEnd = $('.edit-food-modal-body .sale-end-date').val();
                    var valid = true;

                    if(!name || !desc || !price){
                        valid = false;
                        $('.edit-food-modal-body .displayAlert').show(100);
                    }else{
                        $('.edit-food-modal-body .displayAlert').hide(100);
                    }

                    //If any price is below 0, fail.
                    //Also, if sale or cyclone price are set, if either one of them is higher than the regular price, fail.
                    if(price < 0 || cyclonePrice < 0 || salePrice < 0 || (salePrice > 0 || cyclonePrice > 0) && (salePrice >= price || cyclonePrice >= price)){
                        valid = false;
                        $('.edit-food-modal-body .displayPriceAlert').show(100);
                    }else{
                        $('.edit-food-modal-body .displayPriceAlert').hide(100);
                    }

                    if((salePrice > 0 && (!saleStart || !saleEnd))){
                        valid = false;
                        $('.edit-food-modal-body .displayDateAlert').show(100);
                    }else if((saleStart || saleEnd) && !salePrice){
                        valid = false;
                        $('.edit-food-modal-body .displayDateAlert').show(100);
                    }else{
                        $('.edit-food-modal-body .displayDateAlert').hide(100);
                    }

                    //Require one checkbox being checked
                    if($('input[type=checkbox]:checked').length == 0)
                    {
                        $(".edit-food-modal-body .typeAlert").show(100);
                        valid = false;
                    }else{
                        $(".edit-food-modal-body .typeAlert").hide(100);
                    }

                    if (valid == true) {
                        e.preventDefault();
                        editItem(parameterObject);
                        //Hide modal after 2 seconds.
                       setTimeout(function(){
                            $('#editFoodModal').modal('toggle');
                       }, 2000);
                    }else{
                        e.preventDefault();
                    }
                });
            }
        });
    });

    $('#addFoodModal').on('show.bs.modal', function(e) {
        //Re-show in case the user has entered more than one item. (We hide it on form submission to clean up the modal.
        $('.add-food-modal-body .addFoodFormDiv').show();

        //Initialize our datepickers.
        $('.add-food-modal-body .datepicker').datepicker();

        /* Toggles for cyclone & sales */
        $('.add-food-modal-body .showCyclone').click(function () {
            $('.add-food-modal-body .displayCyclonePrice').show(100);
        });

        $('.add-food-modal-body .hideCyclone').click(function () {
            $('.add-food-modal-body .displayCyclonePrice').hide(100);
            $('.add-food-modal-body .cyclone-card-price').val("");
        });

        $('.add-food-modal-body .showSales').click(function () {
            $('.add-food-modal-body .displaySales').show(100);
        });

        $('.add-food-modal-body .hideSales').click(function () {
            $('.add-food-modal-body .displaySales').hide(100);

            //Reset values on hide
            $('.add-food-modal-body .sale-price').val("");
            $('.add-food-modal-body .sale-start-date').val("");
            $('.add-food-modal-body .sale-end-date').val("");
        });

        /* Validation on the edit food modal */
        $("#add_food_form").submit(function (e) {
            var name = $('.add-food-modal-body .food-name').val();
            var desc = $('.add-food-modal-body .food-desc').val();
            var cyclonePrice = parseFloat($('.add-food-modal-body .cyclone-card-price').val());
            var price = parseFloat($('.add-food-modal-body .regular-price').val());
            var salePrice = parseFloat($('.add-food-modal-body .sale-price').val());
            var saleStart = $('.add-food-modal-body .sale-start-date').val();
            var saleEnd = $('.add-food-modal-body .sale-end-date').val();
            var valid = true;

            if (!name || !desc || !price) {
                valid = false;
                $('.add-food-modal-body .displayAlert').show(100);
            } else {
                $('.add-food-modal-body .displayAlert').hide(100);
            }

            //If any price is below 0, fail.
            //Also, if sale or cyclone price are set, if either one of them is higher than the regular price, fail.
            if (price < 0 || cyclonePrice < 0 || salePrice < 0 || (salePrice > 0 || cyclonePrice > 0) && (salePrice >= price || cyclonePrice >= price)) {
                valid = false;
                $('.add-food-modal-body .displayPriceAlert').show(100);
            } else {
                $('.add-food-modal-body .displayPriceAlert').hide(100);
            }

            if (salePrice > 0 && (!saleStart || !saleEnd)) {
                valid = false;
                $('.add-food-modal-body .displayDateAlert').show(100);
            } else if((saleStart || saleEnd) && !salePrice){
                valid = false;
                $('.add-food-modal-body .displayDateAlert').show(100);
            } else {
                $('.add-food-modal-body .displayDateAlert').hide(100);
            }

            //Require one checkbox being checked
            if($('input[type=checkbox]:checked').length == 0)
            {
                $(".add-food-modal-body .typeAlert").show(100);
                valid = false;
            }else{
                $(".add-food-modal-body .typeAlert").hide(100);
            }

            if (valid === true) {
                e.preventDefault();
                addItem(parameterObject);
                //Hide modal after 2 seconds.
                setTimeout(function () {
                    $('#addFoodModal').modal('toggle');

                    //Hide all fields & errors.
                    $('.add-food-modal-body .loading-added').hide();
                    $('.add-food-modal-body .displayDateAlert').hide();
                    $('.add-food-modal-body .displayPriceAlert').hide();
                    $('.add-food-modal-body .displayAlert').hide();
                }, 2000);

                //Reset form.
                $('#add_food_form').trigger("reset");
            } else {
                e.preventDefault();
            }
        });
    });

    $('#addFoodModal').on('hidden.bs.modal', function(e){
        //Reset form.
        $('#add_food_form').trigger("reset");

        //Hide all fields & errors.
        $('.add-food-modal-body .loading-added').hide();
        $('.add-food-modal-body .displayDateAlert').hide();
        $('.add-food-modal-body .displayPriceAlert').hide();
        $('.add-food-modal-body .displayAlert').hide();
        $('.add-food-modal-body .displaySales').hide();
        $('.add-food-modal-body .displayCyclonePrice').hide();

    });

    $('#uploadModal').on('show.bs.modal', function(e){
        //Hide edit modal until this modal is done.
        $('#editFoodModal').modal('hide');

        //Get ID from form. (Hidden field)
            if($('input[name="food_id"]').val()){
                var id = $('input[name="food_id"]').val();
            }else{

            }

        //Set the loading image while ajax renders
        $('.upload-modal-body').addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

        $.ajax({
            type: 'post',
            url: parameterObject.ModalBodyPath+'upload.php',
            data: { food_id:  id },
            success: function (r) {
                $('.upload-modal-body').html(r);

                $("#upload_form").submit(function(e) {
                    e.preventDefault();

                    //Set the loading image while ajax renders
                    $('.upload-modal-body .loading').addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

                    //This function posts with ajax to update the new image. (Or insert one)
                    uploadImage(parameterObject);
                });
            }
        });
    });

    $('#deleteModal').on('show.bs.modal', function(e){

        parameterObject.foodID = $(e.relatedTarget).data('id');

        $('.delete-modal-body .item-information').html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

        //Show details of item to be deleted to verify to the user which one they've selected to delete.
        $.ajax({
            method: "post",
            url: 'ajaxFunctions.php?action=displayByIDNonTable&id='+parameterObject.foodID,
            success: function(r){
                $('.delete-modal-body .item-information').html(r);
                $("#deleteForm").submit(function(e){
                    e.preventDefault();
                    //Delete and get rid of item.
                    deleteItem(parameterObject);

                    //Hide modal after 1 second.
                    setTimeout(function () {
                        $('#deleteModal').modal('toggle');
                    }, 1000);
                });
            }
        });
    });

    $('#optionsModal').on('show.bs.modal', function(e){
        $('.options-modal-body .all-types').addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

        $.get("ajaxFunctions.php?action=displayAllTypes", function(r){
            $('.options-modal-body .all-types').removeClass("text-center").html(r);
        });
    });

    //If uploadModal is closed, re-open edit modal
    $('#uploadModal').on('hidden.bs.modal', function(e){
        $('#uploadModal').modal('hide');
        $('#editFoodModal').modal('show');
    });
    /*
     -------------------------------------------------------------------------------------------------------------------------------------------------------
    */

    /* Misc Functions */

    //Counts textareas and provides feedback
    countTextArea();

    //If modals are closed, hide errors.
    $(".modal").on("hidden.bs.modal", ".modal", function(){
        $(".passwordAlert").hide();
    });

    //Dynamic function clears the form of which the reset button is attached to.
    $(".clear-form-button").click(function(){
        $(this).closest('form')[0].reset();
        //Hide alerts on reset.
        $(".passwordAlert").hide();
    });

    /*
    -------------------------------------------------------------------------------------------------------------------------------------------------------
    */
});

function ajax_modal(type, url, data, modal_class){
    $.ajax({
        type: type,
        url: url,
        data: data,
        success: function(results){
            $('.' + modal_class).html(results);
        }
    });
}

/*
All functions take in a parameter object, just in case we have to make changes in the future, it will be quick and in one spot
Instead of all over the place
*/
function register(parameterObject){
    //Get all form data.
    var formData = $("#registerForm").serialize();

    //Show loading gif (pulling from a parameter object we are pulling in.
    //Doing it this way so we can make changes in one place in the future, instead of all over the place.
    $(".register-modal-body").addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

    ajax_modal("post", "ajaxFunctions.php?action=register", formData, "register-modal-body");

    //Hide modal after 2 seconds.
    setTimeout(function(){
        location.reload();
    }, 2000);
}

function login(parameterObject){
    //Get all form data.
    var formData = $("#loginForm").serialize();

    //Show loading gif (pulling from a parameter object we are pulling in.
    //Doing it this way so we can make changes in one place in the future, instead of all over the place.
    $(".login-modal-body").addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

    ajax_modal("post", "ajaxFunctions.php?action=login", formData, "login-modal-body");

    //Hide modal after 2 seconds.
    setTimeout(function(){
        location.reload();
    }, 2000);
}

function logout(parameterObject){
    //Show loading gif (pulling from a parameter object we are pulling in.
    //Doing it this way so we can make changes in one place in the future, instead of all over the place.
    $(".logout-modal-body").addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

    $.ajax({
       type: "GET",
        url: "ajaxFunctions.php?action=logout",
        success: function(m){
            $(".logout-modal-body").html("<h3>You are now logged out.</h3>");
        }
    });

    //Hide modal after 2 seconds.
    setTimeout(function(){
        location.reload();
    }, 2000);
}

function editItem(parameterObject){
    //Get all form data.
    var formData = $("#edit_food_form").serialize();
    //Get ID from form.
    var id = $('input[name="food_id"]').val();

    //Show loading gif (pulling from a parameter object we are pulling in.
    //Doing it this way so we can make changes in one place in the future, instead of all over the place.
    $(".edit-food-modal-body").addClass("text-center").html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

    //Make ajax call to update the current item.
    //In the callback function "complete", we are calling another script, passing the id along dynamically, and reloading the results instead of a page refresh.
    $.ajax({
        type: "POST",
        url: "ajaxFunctions.php?action=updateFood&id="+id,
        data: formData,
        success: function(results){
            $('.edit-food-modal-body').html(results);
        },
        //On complete, update info dynamically. (Updating a tr by ID)
        complete: function(){
            $.get('ajaxFunctions.php?action=displayByID&id='+id, formData, function(data){
                $("#id" + id).html(data);
            });
        }
    });
}

function addItem(parameterObject){
    //Get all form data.
    var formData = $("#add_food_form").serialize();

    //Show loading gif (pulling from a parameter object we are pulling in and assign it the class of loading.
    $('.add-food-modal-body .addFoodFormDiv').hide();
    $(".add-food-modal-body").append("<img class='img-rounded loading' src='"+parameterObject.loadingGifPath+"'/>");

    //Make ajax call to update the current item.
    //In the callback function "complete", we are calling another script, passing the id along dynamically, and reloading the results instead of a page refresh.
    $.ajax({
        type: "POST",
        url: "ajaxFunctions.php?action=addFood",
        data: formData,
        success: function(r){
            //Assign last inserted id returned from php script (echoed) into our parameter object.
            parameterObject.lastInsertID = r;

            //Hide loading gif and display results.
            $(".add-food-modal-body .loading").hide();
            $(".add-food-modal-body").append("<h3 class='loading-added'>Item added.</h3>");
        },
        //On complete, update info dynamically. (adding a tr with new ID)
        complete: function(r){
            $.get('ajaxFunctions.php?action=displayByID&id='+parameterObject.lastInsertID, function(data){
                //In the jquery selector, we are specifying to append to the last tr, much like a CSS psuedo selector.
                $('.foodTable tr:last').after("<tr id='id"+parameterObject.lastInsertID+"'>" + data + "</tr>");

                //This is used to scroll down to the newly added element.
                $('html, body').animate({
                   scrollTop: $('#id' + parameterObject.lastInsertID).offset().top
                });

                //This is to animate and show the user which item they just added.
                //Used http://codepen.io/anon/pen/LyiFg for reference.

                //Change color to a lightblue to indicate newly-added item. (Tried using .css and changing background-color that way; didn't work as intended
                $('#id' + parameterObject.lastInsertID).animate({
                   backgroundColor: "#87CEFA"
                });
                //Animate back to white with a 1 second timer.
                $('#id' + parameterObject.lastInsertID).animate({
                    backgroundColor: "white"
                }, 1000)
            });
        }
    });
}

function deleteItem(parameterObject){
    var id = parameterObject.foodID;

    $('.delete-modal-body .item-information').html("<img class='img-rounded' src='"+parameterObject.loadingGifPath+"'/>");

    $.ajax({
        type: "POST",
        url: "ajaxFunctions.php?action=delete&id="+id,
        data: {food_id: id},
        success: function(results){
            $('.delete-modal-body .item-information').html(results);
            //This removes the item in an animated way.
            //Using fadeout method to slowly make it "hide", then calling a callback function to actually remove this element from the DOM.
            $('#id' + id).fadeOut("slow", function(){
                $(this).remove();
            });
        }
    });
}

//Used this page for help & reference for this function (May need updating for older browser functionality)
//http://stackoverflow.com/questions/21044798/how-to-use-formdata-for-ajax-file-upload (Last solution)
function uploadImage(parameterObject){
    var form = $('#upload_form')[0];
    var formData = new FormData(form);
    formData.append('image', $('input[type=file]')[0].files[0]);

    //Get ID from form.
    var id = $('input[name="food_id"]').val();

    $.ajax({
        type: 'POST',
        url: 'ajaxFunctions.php?action=upload&id='+id,
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function (r) {
            $('.upload-modal-body').html(r);
        },
        //On complete, update image reference on main page and edit page
        complete: function(){
            //Updating entire tr on home page.
            $.get('ajaxFunctions.php?action=displayByID&id='+id, function(data){
                $("#id" + id).html(data);
            });

            //Updating image by id on edit modal.
            $.get('ajaxFunctions.php?action=displayUploadedImage&id='+id, function(data){
               $('#edit-food-image' + id).html(data);
            });

            //Hide modal after 2 seconds.
            setTimeout(function(){
                $('#uploadModal').modal('toggle');
                $('#editFoodModal').modal('toggle');
            }, 2000);
        }
    });
}

//To limit food description from going over the max allowed in database.
function countTextArea() {
    var maxLength = 250;
    $('textarea').keyup(function () {
        var length = $(this).val().length;
        length = maxLength - length;
        $(".remainingCount").text("Remaining characters: " + length);

        //Display a little warning message if they've gone over the limit.
        if (length == 0) {
            $(".remainingCount").addClass("red").append("<br />No more room for characters.");
        } else {
            $(".remainingCount").removeClass("red");
        }
    });
}

/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------
*/

