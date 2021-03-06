
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Tafjord</title>
        <link rel="shortcut icon" type="image/png" href="image/TafjordLogo3.png">
        <!-- Bootstrap -->
        <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        
        
        
        <link rel="stylesheet" type="text/css" href="Bootstrap/daterangepicker.css">
        <link href="style/home.css" rel="stylesheet">
        <script src="js/handlebars-v4.0.5.js"></script>
        
        
    </head>
    <body>

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

            <div class="container-fluid">
                <div class="navbar-header">
                    

                    <a class="navbar-left" href="?request=home" style="margin-left: 25px; margin-top: 10px;">
                        <img src="image/TafjordLogo.png" alt="Tafjord Logo Snarvei Home">

                    </a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#usernav"><span class="glyphicon glyphicon-user"></span> 
                            <!-- Display logged in users name -->
                        <?php echo  $_SESSION["nameOfUser"]; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="?request=editUser">Rediger profil</a></li>
                            <li><a href="../">Logout</a></li>
                        </ul>
                    </li> 
                </ul>
                <form class="navbar-form navbar-right">
                    <input type="text" class="form-control" placeholder="Søk..">
                </form>
                
            </div>
        
            <!-- Navigation sidebar -->
        <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                    


                        <li><a href="?request=home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                        <li><a href="?request=sale"><span class="glyphicon glyphicon-export"></span> Registrer Uttak</a></li>
                        <li><a href="?request=return"><span class="glyphicon glyphicon-import"></span> Registrer Retur</a></li>    

                        <li><a href="?request=transfer"><span class="glyphicon glyphicon-random"></span> Overføring</a></li>

                        <li><a href="?request=mySales"><span class="glyphicon glyphicon-stats"></span> Dine Salg</a></li>
                        <li><a href="?request=myReturns"><span class="glyphicon glyphicon-stats"></span> Dine Returer</a></li>
                        
                           <!-- Administrator only options -->
                        <?php if ($_SESSION["userLevel"] == "Administrator") {?>
                        <li><a href="?request=logg"><span class="glyphicon glyphicon-paperclip"></span> Logg</a></li>
                        <li><a id="show-hide-toogle" href="#"><span class="glyphicon glyphicon-wrench"></span> Admin<span class="caret"></span></a>
                        
                            <ul id="dropdown" class="nav nav-second-level" hidden>
                                <li>
                                    <a href="?request=userAdm"><span class="glyphicon glyphicon-user"></span> Bruker</a>
                                </li>
                                <li>
                                    <a  href="?request=storageAdm"><span class="glyphicon glyphicon-home"></span> Lager</a>
                                </li>
                                <li>
                                    <a href="?request=productAdm"><span class="glyphicon glyphicon-shopping-cart"></span> Produkt</a>
                                </li>
                                <li>
                                    <a href="?request=mediaAdm"><span class="glyphicon glyphicon-picture"></span> Media</a>
                                </li>
                                <li>
                                    <a href="?request=categoryAdm"><span class="glyphicon glyphicon-folder-open"></span> Kategori</a>
                                </li>
                                <li>
                                    <a href="?request=groupAdm"><span class="glyphicon glyphicon-share"></span> Gruppe</a>
                                </li>
                            </ul>
                  
                        </li>
                        <?php }?>
                        <li><a href="?request=employeeTraning"><span class="glyphicon glyphicon glyphicon-info-sign"></span> Opplæring</a></li>
                    </ul>    
     

                </div>


            </div>
            </nav>
        
          
        
      

        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
        <script src="Bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="Bootstrap/moment.min.js"></script>
        <script type="text/javascript" src="Bootstrap/daterangepicker.js"></script>
        
        <script src="Charts/Chart.js"></script>

 
        <!-- Hide or show dropdown funciton -->
<script>


$(document).ready(function(){
    $("#show-hide-toogle").click(function(){
        $("#dropdown").toggle();
     
    });
});
 
</script>


