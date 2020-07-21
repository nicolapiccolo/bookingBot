<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mr Alfred</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="favicon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>
    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li >
                        <a href="index.php"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                     
                    <li class="active">
                        <a href="settings.php"><i class="menu-icon fa fa-cogs"></i>Settings </a>
                    </li>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./"><img src="logoSito.png" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="logoSito.png" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            
        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-12">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <ol class="breadcrumb text-left">
                                    <li><a href="index.php">All Reservations</a></li>
                                    <li class="active">Reservation 
                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="content">
            <div class="animated fadeIn">


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <div class="card-title">
                                        <?php
                                        require __DIR__.'/vendor/autoload.php';
                                                    require 'manager.php'; 
                                                    use Kreait\ Firebase\ Factory;
                                                    use Kreait\ Firebase\ ServiceAccount;
                                                    $manager = new Manager();
                                                    
                                                    echo "
                                                    <div class='row m-0'>
                                                        <div class='col-sm-8'>
                                                            <div class='page-header float-left'>
                                                                <div class='page-title'>
                                                                    <h3 style='padding-top:15px;'>Settings</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='col-sm-4'>
                                                            <div class='page-header float-right'>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>


                                                    </div>
                                                    <hr>
                                                    <form action='#' method='post'>
                                                        
                                                        <div class='row'>
                                                    <div class='col-6'>
                                                        <label for='x_card_code' class='control-label mb-1'>Time Opening</label>
                                                        <div class='input-group'>
                                                            <input type='time'  name='timeOpening' value=".$manager->getTimeOpening()." class='form-control' placeholder='hh:mm' required autofocus
                                                            pattern=''>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class='col-6'>
                                                        <label for='x_card_code' class='control-label mb-1'>Time Closing</label>
                                                        <div class='input-group'>
                                                            <input type='time'  name='timeClosing' value=".$manager->getTimeClosing()." class='form-control' placeholder='hh:mm' required autofocus
                                                            pattern=''>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                <div class='col-6'>
                                                    <div class='form-group'>
                                                        <label for='cc-exp' class='control-label mb-1'>Total seats</label>
                                                        <input type='number'  name='tot_seats' value=".$manager->getTotalSeats()." class='form-control' placeholder='Total seats' required autofocus min='1' >
                                                        <span class='help-block' data-valmsg-for='cc-exp' data-valmsg-replace='true'></span>
                                                    </div>
                                                </div>
                                                <div class='col-6'>
                                                    <div class='form-group'>
                                                        <label for='cc-exp' class='control-label mb-1'>Range</label>
                                                        <input type='number'  name='span' value=".$manager->getSpan()." class='form-control' placeholder='Range' required autofocus min='1' >
                                                        <span class='help-block' data-valmsg-for='cc-exp' data-valmsg-replace='true'></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                    <button class='btn btn-lg btn-info btn-block' type='submit' value='save' name='save'>
                                                        Save changes
                                                       
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                            </div>
                                        </div>
                                                    ";

                    
                    if(isset($_POST['save'])){
                        $manager->saveSettings($_POST['timeOpening'],$_POST['timeClosing'],$_POST['tot_seats'], $_POST['span']);
                    
                    }
                    
                                        ?>
                                            

                                        
                                                
                                                
                                            
                                            
                                            
                                            
                                            

                    
                    
                  
                    

    <div class="clearfix"></div>

    

</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>


</body>
</html>
