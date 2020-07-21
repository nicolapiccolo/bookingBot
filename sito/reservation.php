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
                    <li class="active">
                        <a href="index.php"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                     
                    <li>
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
                                    <?php
                                        echo $_GET['id'];
                                        echo " </li>";
                                    ?></li>
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
                    <div class="col-lg-8">
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
                                                    $result = $manager->getReservation($_GET['id']);
                                                    $data = substr($result['orario'], 0,10);
                                                    $time = substr($result['orario'], 11,5);
                                                    $passed = $manager->getTimePassed($result['time']);
                                                    $p = "";
                                                    $note = "";
                                                    if($passed>1){
                                                                $p = $passed." Days Ago";
                                                                }
                                                            else if($passed == 0){
                                                                $p = "Today";
                                                            }
                                                                else{$p=$passed." Day Ago";
                                                                }
                                                    if($result['nota']==""){
                                                        $note = "no note left";
                                                    }
                                                    else{
                                                        $note = $result['nota'];
                                                    }
                                                    echo "
                                                    <div class='row m-0'>
                                                        <div class='col-sm-8'>
                                                            <div class='page-header float-left'>
                                                                <div class='page-title'>
                                                                    <h3 style='padding-top:15px;'>Reservation ID: ".$result['id']."</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='col-sm-4'>
                                                            <div class='page-header float-right'>
                                                                <div class='page-title'>
                                                                    <ol class='breadcrumb text-right'>
                                                                        <span class='reservation-".$result['status']."'>".$result['status']."
                                                            </span>
                                                                    </ol>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    </div>
                                                    <hr>
                                                    <form action='#' method='post'>
                                                        <div class='form-group text-left'>
                                                            <label for='cc-payment' class='control-label mb-1'>Received: ".
                                                            $p."</label>
                                                    </div>
                                                        <div class='row'>
                                                    <div class='col-6'>
                                                        <div class='form-group'>
                                                            <label for='cc-exp' class='control-label mb-1'>Date</label>
                                                            <input type='date'  name='data' value=".$data." class='form-control' placeholder='aaaa-mm-dd' required autofocus pattern='^([0-9]{4}[-/]?((0[13-9]|1[012])[-/]?(0[1-9]|[12][0-9]|30)|(0[13578]|1[02])[-/]?31|02[-/]?(0[1-9]|1[0-9]|2[0-8]))|([0-9]{2}(([2468][048]|[02468][48])|[13579][26])|([13579][26]|[02468][048]|0[0-9]|1[0-6])00)[-/]?02[-/]?29)$'>
                                                            <span class='help-block' data-valmsg-for='cc-exp' data-valmsg-replace='true'></span>
                                                        </div>
                                                    </div>
                                                    <div class='col-6'>
                                                        <label for='x_card_code' class='control-label mb-1'>Time</label>
                                                        <div class='input-group'>
                                                            <input type='time'  name='time' value=".$time." class='form-control' placeholder='hh:mm' required autofocus
                                                            pattern=''>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                <div class='col-6'>
                                                    <div class='form-group'>
                                                        <label for='cc-exp' class='control-label mb-1'>Number of Person</label>
                                                        <input type='number'  name='n_posti' value=".$result['n_posti']." class='form-control' placeholder='N Person' required autofocus min='1' >
                                                        <span class='help-block' data-valmsg-for='cc-exp' data-valmsg-replace='true'></span>
                                                    </div>
                                                </div>
                                                <div class='col-6'>
                                                    <div class='card-body'>
                                                                <label for='cc-exp' class='control-label mb-1'>Status</label>
                                                              <select data-placeholder='Choose Status...'' class='standardSelect' tabindex='1'required name='status'>
                                                                <option value='pending' label='pending'>Pending</option>
                                                                <option value='confirmed'>Confirmed</option>
                                                                <option value='cancelled'>Cancelled</option>
                                                            </select>
                                                        </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <label for='cc-exp' class='control-label mb-1'>Customer Notes</label>
                                            <div class='card-body'>
                                                <p class='text-muted m-b-15'>".$note."
                                                </p>
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
                                        </div> <!-- .card -->

                                    </div><!--/.col-->
                                    <div class='col-md-4'>
                        <div class='card'>
                            
                            <div class='card-body'>
                                <div class='mx-auto d-block' style='padding:50px'>
                                    <img class='rounded-circle mx-auto d-block' src='images/user.png' alt='Card image cap'>
                                    <h3 class='text-center mt-2 mb-1'>".$result['customer']."</h3>
                                    
                                </div>
                                <hr>
                                <div class='card-text text-sm-center'>
                                    <div class='icon-container'>
                                        <span class='ti-mobile'></span><span class='icon-name'>".$result['cell']."</span>
                                    </div>
                                    <div class='icon-container'>
                                    <span class='ti-email'></span><span class='icon-name'>".$result['email']."</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>";

                    
                    if(isset($_POST['save'])){
                        $manager->setReservation($_GET['id'],$_POST['data'],$_POST['time'],$_POST['n_posti'],$_POST['status']);
                    
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
