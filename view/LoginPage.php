<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
if (isset($GLOBALS["errorMessage"])){
$error = $GLOBALS["errorMessage"];
}
?>
<html>
    <head>
        <title>Login Tafjord</title>
            <link rel="shortcut icon" type="image/png" href="system/image/TafjordLogo3.png">
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="system/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="system/style/LoginPageCss.css" rel="stylesheet">

    </head>
    <body>
        
        
            <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    
                    <div class="account-wall">

                        <img class="profile-img" src="system/image/TafjordLogo3.png" alt="Tafjord Logo">
                        <form class="form-signin" id="login" action="?request=loginEngine" method="post">

                            <input type="text" class="form-control" placeholder="Brukernavn" name="givenUsername" required autofocus>
                            <input type="password" id="psw" autocomplete="off" class="form-control" placeholder="Passord" name="givenPassword" required>
                            <input form="login" type="hidden" id="date" name="givenLastLogin">
                            <div class="col-md-12 row">
                                <div class="col-md-6 pull-left">
                            <label class="checkbox">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="show-hide" value="">
                                Vis passord
                            </label>
                                </div>
                                <div class="col-md-6 pull-right" style="margin-top: 4%;">
                            <a href="#" data-toggle="modal" data-target="#forgottenPasswordModal">Glemt passord?</a>
                            </div>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                Logg inn</button>
                        </form>
                        <?php 
                        if (isset($GLOBALS["errorMessage"])){
                            ?> <div class="alert alert-danger">
                            <?php echo $error;
                            }
                        ?>
                            </div>
                        <div id="success"></div>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <div class="modal fade" id="forgottenPasswordModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Glemt passord</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                            <p>Dersom du har glemt passordet ditt kan du få tilsendt nytt ved å fylle ut 
                                informasjonen nedenfor. </p>
                            <form action="?request=newPassword" method="post" id="newPassword">

                                <tr>
                                    <td id="bordernone"><input class="form-control" type="text" required="required" name="givenUsername" placeholder="Brukernavn" value=""></td>
                                </tr>
                                <tr>
                                    <td id="bordernone"><input class="form-control" type="text" required="required" name="givenEmail" placeholder="E-postadresse" value=""></td>
                                </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="error"></div>
                    <input class="btn btn-success" form="newPassword" type="submit" value="Send nytt passord">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
                </form>
            </div>
        </div>
    </div> 
        <script type="text/javascript" src="system/js/hide-show-password.js"></script>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="system/Bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>


<script>
Date.prototype.yyyymmdd = function() {
   var yyyy = this.getFullYear();
   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
   return "".concat(yyyy).concat(mm).concat(dd);
  };

var d = new Date();
document.getElementById("date").value  = d.yyyymmdd();

</script>
<script>
    
$(function sendNewPassword() {

    $('#newPassword').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessage();
            },
            success: function () {
                $("#newPassword")[0].reset();
                $('#forgottenPasswordModal').modal('hide');
                successMessageNewPassword();
            }
        });
        return false;
    });
});


function errorMessage() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kunne ikke finne brukernavn eller epostadresse </div>').appendTo('#error')
            .delay(3000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}


function successMessageNewPassword() {
    $('<div class="alert alert-success"><strong>Sendt!</strong> Nytt passord er sendt til oppgitt E-postadresse </div>').appendTo('#success')
            .delay(4000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

</script>