$(document).ready(function(){
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
            register();
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
            login();
        }else{
            $(".passwordAlert").show();
        }
    });

    //Logout action
    $("#logoutForm").submit(function(e){
        //Stop form from refreshing page.
        e.preventDefault();

        logout();
    });

    //If modals are closed, hide errors.
    $(".modal").on("hidden.bs.modal", function(){
        $(".passwordAlert").hide();
    });

    //Dynamic function clears the form of which the reset button is attached to.
    $(".clearFormButton").click(function(){
        $(this).closest('form')[0].reset();
        //Hide alerts on reset.
        $(".passwordAlert").hide();
    })
});

function ajax_modal(type, url, data){
    $.ajax({
        type: type,
        url: url,
        data: data,
        success: function(results){
            $('.modal-body').html(results);
        }
    });
}

function post_modal(url, formData){
    //Send form data to a script.
    $.post(url, formData)
        .done(function(data){
            $('.modal-body').html(data);
        });
}

function register(){
        //Get all form data.
        var formData = $("#registerForm").serialize();

        //Show loading gif and add class to center it.
        $(".modal-body").addClass("text-center").html("<img class='img-rounded' src='Styles/loading.gif'/>");


        ajax_modal("post", "users.php?action=register", formData);

        //Hide modal after 2.5 seconds.
        setTimeout(function(){
            $("#registerModal").modal("hide");
            location.reload();
        }, 2000);
}

function login(){
    //Get all form data.
    var formData = $("#loginForm").serialize();

    //Show loading gif and add a class to center it.
    $(".modal-body").addClass("text-center").html("<img class='img-rounded' src='Styles/loading.gif'/>");

    ajax_modal("post", "users.php?action=login", formData);

    //Hide modal after 2.5 seconds.
    setTimeout(function(){
        $("#loginModal").modal("hide");
        location.reload();
    }, 2000);
}

function logout(){
    //Show loading gif.
    $(".modal-body").html("<img class='img-rounded' src='Styles/loading.gif'/>");

    $.ajax({
       type: "GET",
        url: "users.php?action=logout",
        success: function(m){
            $(".modal-body").html("<h3>You are now logged out.</h3>");
        }
    });

    //Hide modal after 2.5 seconds.
    setTimeout(function(){
        $("#logoutModal").modal("hide");
        location.reload();
    }, 2000);
}
