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
                        <img class="profile-img" src="system/image/Tafjord1.jpg" alt="">
                        <form class="form-signin" id="login" action="?page=loginEngine" method="post">
                            <input type="text" class="form-control" placeholder="Brukernavn" name="givenUsername" required autofocus>
                            <input type="password" id="psw" autocomplete="off" class="form-control" placeholder="Passord" name="givenPassword" required>
                            <input form="login" type="hidden" id="date" name="givenLastLogin">

                            <label class="checkbox">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="show-hide" value="">
                                Vis passord
                            </label>
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
                    </div>
                </div>
            </div>
            
        </div>
        <script type="text/javascript" src="system/js/hide-show-password.js"></script>
        <script type="text/javascript" src="system/js/userAdm.js"></script>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../Bootstrap/js/bootstrap.min.js"></script>
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

