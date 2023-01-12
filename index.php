<?php

require_once 'php/These.php';
require_once 'php/Connexion.php';
require_once 'php/stopWords.php';



session_start();

/*
* Renvoie une liste associant un code région (ex: fr-idf, fr-ara...)
* à son nombre d'occurences dans un fichier json
*/
function getRegionsCodeToEtablissementsNumber($theses){
    $regionsCodeToEtablissementsNumber = array();
    foreach ($theses as $these) {
        //echo $these->getCodeRegion()."<br>";
        $regionCode = 'fr-'.strtolower($these->getCodeRegion());
        if (array_key_exists($regionCode, $regionsCodeToEtablissementsNumber)) {
            $regionsCodeToEtablissementsNumber[$regionCode] += 1;
        }else{
            $regionsCodeToEtablissementsNumber[$regionCode] = 1;
        }
    }
    //print_r($regionsCodeToEtablissementsNumber);
    return $regionsCodeToEtablissementsNumber;
}





/*
 * Renvoie les 10 premiers sujets abordés les plus récurrents
 */
function getThe10MostReccurentSubjects($theses){
    $subjects = array();
    foreach($theses as $these){
        // split the subjects string into list
        $TMPsubjects = explode(",", $these->getSubjects());
        //print_r($TMPsubjects);
        foreach($TMPsubjects as $subject){
            // if the subject is not in the list, add it
            if(in_array($subject, $subjects) && strlen($subject) > 2){
                $subjects[$subject]++;
            }else{
                $subjects[$subject] = 1;
            }
        }
    }
    // sort the list by value
    arsort($subjects);
    // return the 10 first keys elements
    return array_slice($subjects, 0, 10);
}


/*
 * Renvoie le nombre d'établissements différents des thèses
 */
function getNumberDistinctEtablissements($theses){
    foreach($theses as $these){
        $etablissements[] = $these->getEtablissement();
        // remove duplicates
        $etablissements = array_unique($etablissements);
    }
    return count($etablissements);
}

function getSubjectsTextForCloud($theses){
    $sujets = strtolower("'".implode(",", array_filter(array_map(function($these) { return $these->getSubjects(); }, array_slice($theses, 0,10))))."'");

    // for each StopWords_French.stopwords array, we remove it from the subjects
    $stopWords = StopWords_French::stopwords();
    foreach($stopWords as $stopWord){
        // remove the stopword from the subjects only if it is not in the middle of a word
        $sujets = preg_replace("/\b$stopWord\b/i", "", $sujets);
    }
    return $sujets;
}

if (isset($_GET["random"])){
    //On prend un mot aléatoire dans un fichier texte
    $words = file('mot_francais.txt');
    $randomWord = $words[array_rand($words)];
    header('Location: index.php?search='.$randomWord);
}

include('php/thesesSearcher.php');


if (!isset($_GET['search'])) {
    $motif = "";
}
if (isset($_GET["search"])) {
    // filtrage des injections xss
    $motif = htmlspecialchars($_GET["search"]);



} 

$theses = getAllThesesByAttributes($db, $motif);
$_SESSION["theses"] = $theses;

// Récupération du nombre de directeurs
$nbDirectors = 0;
foreach ($theses as $these) {
    $nbDirectors += sizeof($these->getDirector());
}

// On concatène tous les sujets des thèses
$sujets = getSubjectsTextForCloud($theses);


?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="berachem" content="">
    <link rel="icon" type="image/png" href="img/book.png" />

    <title>Thèses.fr </title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/style.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/wordcloud.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Thèses.fr</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Alertes</span></a>
            </li>
<!--
     
            <hr class="sidebar-divider">

            
            <div class="sidebar-heading">
                Interface
            </div>

             Nav Item - Pages Collapse Menu 
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

                -->


            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form method="GET" action="index.php"
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher..."
                                aria-label="Search" name ="search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                                <a href="index.php?random=1">
                                    <button class="btn btn-success" type="button">
                                        <i class="fas fa-random fa-sm"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Rechercher..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                            <a href="index.php?random=1">
                                                <button class="btn btn-success" type="button">
                                                    <i class="fas fa-random fa-sm"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alertes
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">26 octobre 2003</div>
                                        <span class="font-weight-bold">Les alertes ne sont pas encore implémentées.</span>
                                    </div>
                                </a>
       
                                <a class="dropdown-item text-center small text-gray-500" href="#">Voir plus</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Berachem MARKRIA</span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Paramètres
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activité
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                        <?php

                            if ($motif != ""){
                                echo '<i class="badge badge-primary">Recherche pour "'.$motif.'"</i>';
                            }

                        ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
                            <?php
                                    if (sizeof($theses) == 0) {
                                        echo '
                                            <div class="badge badge-warning text-white shadow">
                                                <div class="card-body">
                                                <i class="fas fa-exclamation-triangle"></i> Nous n\'avons rien trouvé comme thèses à propos de cette recherche... :/ réessayez
                                                </div>
                                            </div>';
                                    }

                            ?>
                        <a href="https://www.theses.fr/" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-eye fa-sm text-white-50"></i> Consulter le site officiel</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-right">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Nombre de thèses</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo sizeof($theses); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-down">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Nombre de directeurs de thèses</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                 <?php 
                                                    echo $nbDirectors;
                                                 ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-down">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Nombre de thèses en ligne
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                <?php 
                                                    $nbTheseEnligne = 0;
                                                    foreach ($theses as $these) {
                                                        if ($these->getEnligne() == "oui") {
                                                            $nbTheseEnligne++;
                                                        }
                                                    }
                                                    echo $nbTheseEnligne;
                                                 
                                                 
                                                 ?> thèses
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php
                                                            echo sizeof($theses) != 0 ? round($nbTheseEnligne / sizeof($theses) * 100) : 0;
                                                        ?>%
                                                        
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?php
                                                                echo sizeof($theses) != 0 ? round($nbTheseEnligne / sizeof($theses) * 100) : 0;
                                                            ?>%" aria-valuenow="
                                                            <?php
                                                                echo sizeof($theses) != 0 ? round($nbTheseEnligne / sizeof($theses) * 100) : 0;
                                                            ?>
                                                        " aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-left">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Nombre d'établissements</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo getNumberDistinctEtablissements($theses) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-school fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7" data-aos="fade-right">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Evolution du nombre de thèses dans le temps</h6>
                                   
                                   <?php
                                   if (isset($_GET["chart"])){
                                    $_SESSION["chart"] = $_GET["chart"];
                                    if ($_GET["chart"] == "year") {
                                        echo '<a href="index.php?search='.$motif.'&chart=month" class ="btn" style="background-color: black; color : white">Voir par mois</a>';
                                    } else {
                                        echo '<a href="index.php?search='.$motif.'&chart=year" class ="btn" style="background-color: black; color : white">Voir par années</a>';
                                    }
                        
                                   }else{
                                    echo '<a href="index.php?search='.$motif.'&chart=month" class ="btn" style="background-color: black; color : white">Voir par mois</a>';

                                   }
                                    ?>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5" data-aos="fade-left">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Langues</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Français
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> Anglais
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Français & Anglais
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-dark"></i> Autres
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                            <!-- Approach -->
                        <div class="col-xl-8 col-lg-7" data-aos="fade-right">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Nuage de mots
                                        </h6>
                                    </div>
                                   
                                   
                                             
                                  
                                    <div class="card-body">
                         



                                    <figure class="highcharts-figure">
                                    <div id="nuage"></div>
                                    <p>
                                   
                                </p>

                                    </figure>


                                    <script>
                                           
                                            
                                            const text =
                                            <?php   
                                                echo $sujets;
                                            ?>,
                                            lines = text.replace(/[():'?0-9]+/g, '').split(/[,\. ]+/g),
                                            data = lines.reduce((arr, word) => {
                                                let obj = Highcharts.find(arr, obj => obj.name === word);
                                                if (obj) {
                                                obj.weight += 1;
                                                } else {
                                                obj = {
                                                    name: word,
                                                    weight: 1
                                                };
                                                arr.push(obj);
                                                }
                                                return arr;
                                            }, []);

                                            Highcharts.chart('nuage', {
                                            accessibility: {
                                                screenReaderSection: {
                                                beforeChartFormat: '<h5>{chartTitle}</h5>' +
                                                    '<div>{chartSubtitle}</div>' +
                                                    '<div>{chartLongdesc}</div>' +
                                                    '<div>{viewTableButton}</div>'
                                                }
                                            },
                                            series: [{
                                                type: 'wordcloud',
                                                data,
                                                name: 'Occurrences'
                                            }],
                                            title: {
                                                text: 'Nuage de mots'
                                            },
                                            subtitle: {
                                                text: 'L\'ensemble de tous les sujets des thèses'
                                            },
                                            tooltip: {
                                                headerFormat: '<span style="font-size: 16px"><b>{point.key}</b></span><br>'
                                            }
                                            });

                                    </script>
                                        
                                        
                                    
    
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-5" data-aos="fade-left">

                                <!-- Illustrations -->
                                <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Carte de France</h6>
                                        </div>
                                        <div class="card-body">

                                            <script>

                                                (async () => {

                                                const topology = await fetch(
                                                    'https://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json'
                                                ).then(response => response.json());

                                                // Prepare demo data. The data is joined to map using value of 'hc-key'
                                                // property by default. See API docs for 'joinBy' for more info on linking
                                                // data and map.
                                                const data = [
                                                    <?php
                                                        foreach(getRegionsCodeToEtablissementsNumber($theses) as $region => $number){
                                                            echo "['".$region."',".$number."],";
                                                        }
                                                    ?>

                                                ];

                                                // Create the chart
                                                Highcharts.mapChart('france-map', {
                                                    chart: {
                                                        map: topology
                                                    },

                                                    title: {
                                                        text: 'Carte Intéractive'
                                                    },

                                                    subtitle: {
                                                        text: 'Régions de <a href="http://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json">France</a>'
                                                    },

                                                    mapNavigation: {
                                                        enabled: true,
                                                        buttonOptions: {
                                                            verticalAlign: 'bottom'
                                                        }
                                                    },

                                                    colorAxis: {
                                                        min: 0
                                                    },

                                                    series: [{
                                                        data: data,
                                                        name: 'Nombre de Thèses',
                                                        states: {
                                                            hover: {
                                                                color: 'red'
                                                            }
                                                            
                                                        },
                                                        dataLabels: {
                                                            enabled: true,
                                                            format: '{point.name}'
                                                        }
                                                    }]
                                                });

                                                })();


                                            </script>
                                            <div id="france-map"></div>
                                            <!--
                                            <p>Add some quality, svg illustrations to your project courtesy of <a
                                                    target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                                constantly updated collection of beautiful svg images that you can use
                                                completely free and without attribution!</p>
                                                                            -->
                                        </div>
                                </div>

                             </div>
                     </div>
                     </div>
                    
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">TOP 10 des thèses les plus pertinentes</h6>
                    </div>
                    <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" 
                                data-toggle="table" data-pagination="true"
                                data-search="true" data-show-columns="true">
                                    <thead>
                                        <tr>
                                            <th>NNT</th>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Date de Soutenance</th>
                                            <th>Etablissement</th>
                                            <th>langue</th>
                                            <th>discipline</th>
                                            <th>Liens</th>
                                            <th>Télécharger</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $SliceTheses = array_slice($theses,0,10);
                                            foreach($SliceTheses as $these){
                                                echo "<tr>";
                                                echo "<td> <i>".$these->getID()."</i></td>";
                                                echo "<td>".$these->getTitre()."</td>";
                                                echo "<td>".$these->getAuteur()."</td>";
                                                echo "<td>".$these->getDate()."</td>";
                                                echo "<td>".$these->getEtablissement()."</td>";
                                                if ($these->getLangue() == "fr"){
                                                    echo '<td> <img src="img/fr.png" alt="" width="35" height="35"> </td>'; 
                                                } else if ($these->getLangue() == "en"){
                                                    echo '<td> <img src="img/en.png" alt="" width="35" height="35"> </td>';
                                                } else if ($these->getLangue() == "enfr"){
                                                    echo '<td> <img src="img/fr.png" alt="" width="35" height="35"><img src="img/en.png" alt="" width="35" height="35"> </td>';
                                                } else {
                                                    echo "<td>Autres</td>";
                                                }
                                                echo "<td>".$these->getDiscipline()."</td>";
                                                echo '<td><a class="btn btn-info" href="https://www.theses.fr/'.$these->getID().'" target="_blank">  
                                                <i class="fas fa-eye"></i>
                                                Consulter</a></td>';
                                                if ($these->getEnligne()=="oui"){
                                                    echo '<td><a class="btn btn-dark" href="https://www.theses.fr/'.$these->getID().'/document" target="_blank">  
                                                    Télécharger</a></td>';
                                                }

                                                echo "</tr>";
                                                
                                             
                                            }

                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                        <!-- 
                        <div class="col-lg-6 mb-4">

                            
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Réseaux</h6>
                                </div>
                                <div class="card-body">
                        
                                    <figure class="highcharts-figure">
                                        <div id="reseau"></div>
                             
                                    </figure>

                                    <script>
                                        // Add the nodes option through an event call. We want to start with the parent
                                        // item and apply separate colors to each child element, then the same color to
                                        // grandchildren.
                                        Highcharts.addEvent(
                                        Highcharts.Series,
                                        'afterSetOptions',
                                        function (e) {
                                            var colors = Highcharts.getOptions().colors,
                                            i = 0,
                                            nodes = {};

                                            if (
                                            this instanceof Highcharts.seriesTypes.networkgraph &&
                                            e.options.id === 'lang-tree'
                                            ) {
                                            e.options.data.forEach(function (link) {

                                                if (link[0] === 'Proto Indo-European') {
                                                nodes['Proto Indo-European'] = {
                                                    id: 'ddddd',
                                                    marker: {
                                                    radius: 20
                                                    }
                                                };
                                                nodes[link[1]] = {
                                                    id: link[1],
                                                    marker: {
                                                    radius: 10
                                                    },
                                                    color: colors[i++]
                                                };
                                                } else if (nodes[link[0]] && nodes[link[0]].color) {
                                                nodes[link[1]] = {
                                                    id: link[1],
                                                    color: nodes[link[0]].color
                                                };
                                                }
                                            });

                                            e.options.nodes = Object.keys(nodes).map(function (id) {
                                                return nodes[id];
                                            });
                                            }
                                        }
                                        );

                                        Highcharts.chart('reseau', {
                                        chart: {
                                            type: 'networkgraph',
                                            height: '100%'
                                        },
                                        title: {
                                            text: 'Réseau des personnes en lien entre-elles à travers les différents thèses'
                                        },
                                        subtitle: {
                                            text: 'A Force-Directed Network Graph in Highcharts'
                                        },
                                        plotOptions: {
                                            networkgraph: {
                                            keys: ['from', 'to'],
                                            layoutAlgorithm: {
                                                enableSimulation: true,
                                                friction: -0.9
                                            }
                                            }
                                        },
                                        series: [{
                                            accessibility: {
                                            enabled: false
                                            },
                                            dataLabels: {
                                            enabled: true,
                                            linkFormat: ''
                                            },
                                            id: 'lang-tree',
                                            data: [
                                            <?php
                                            /*
                                            foreach($theses as $these){
                                                foreach(array_merge($these->getAllMembreJury()) as $pers){
                                                    echo "['".$these->getAuteur()."', '".$pers. "'],";
                                                }
                                            }

                                            */
                                            ?>
                                        
                                            ]
                                        }]
                                        });

                                    </script>

                                </div>
                            </div>
                                    

                           
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            Primary
                                            <div class="text-white-50 small">#4e73df</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            Success
                                            <div class="text-white-50 small">#1cc88a</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Info
                                            <div class="text-white-50 small">#36b9cc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            Warning
                                            <div class="text-white-50 small">#f6c23e</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Danger
                                            <div class="text-white-50 small">#e74a3b</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-secondary text-white shadow">
                                        <div class="card-body">
                                            Secondary
                                            <div class="text-white-50 small">#858796</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-light text-black shadow">
                                        <div class="card-body">
                                            Light
                                            <div class="text-black-50 small">#f8f9fc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-dark text-white shadow">
                                        <div class="card-body">
                                            Dark
                                            <div class="text-white-50 small">#5a5c69</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            -->

                        </div>

                       
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>
                            <?php 
                                $copyYear = 2022; 
                                $curYear = date('Y'); 
                                echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '');
                                ?> By Berachem MARKRIA</span>
                        <span>
                            |
                            <a href="https://www.theses.fr/" class="text-dark">Site officiel</a>
                            |
                            <a href="https://github.com/Berachem/theses" class="text-dark">Code Source</a>
                            |
                            <a href="https://www.cnil.fr/" class="text-dark">CNIL</a>
                            |
                            <a href="" class="text-dark">Accueil</a>
                            |
                            <a href="mailto:berachem.markria@gmail.com" class="text-dark">Contact</a>
                        </span>
                </div>

            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/script.min.js"></script>

    <!-- Page level plugins -->
  
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


<?php
    include("js/chart-pie.php");
    include("js/chart-area.php");
    
?>
</body>



</html>