<?php

include("php/lib/parse.env.php");
require_once 'php/These.php';
require_once 'php/Connexion.php';
require_once 'php/stopWords.php';

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: autres/connexion.php');
    exit;
}
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

    <title>Thèses.fr | Alertes</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="alertes.php">
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
                    <form method="GET" action="index.php" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher..." aria-label="Search" name="search" aria-describedby="basic-addon2">
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
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher..." aria-label="Search" aria-describedby="basic-addon2">
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
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
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
                        <?php
                        if (isset($_SESSION['id'])) {
                            echo '
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">' . $_SESSION["pseudo"] . '</span>
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
                    ';
                        } else {
                            // bouttons de connexion et d'inscription
                            echo '
                    <li class="nav-item">
                        <a class="nav-link" href="autres/connexion.php">
                            <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="autres/inscription.php">
                            <i class="fas fa-user-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                            Inscription
                        </a>
                    </li>';
                        }


                        ?>


                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                    <?php

                        if (isset($_GET["try"])){

                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                Checkez vos mails ! Un essai a été réalisé.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>';
                            
                        }else if (isset($_GET["add"])){
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                Motif ajouté avec succès !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>';
                            
                        }else if (isset($_GET["delete"])){
                                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                Motif supprimé avec succès !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>';

                        }else if (isset($_GET["full"])){
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Vous ne pouvez pas ajouter plus de 2 alertes !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>';
                        }

                    ?>

                        <h1 class="mb-4">Liste des alertes configurées <b>(Max 2)</b></h1>

                        <p class="mb-4">Vous pouvez ajouter ou supprimer des alertes en cliquant sur les boutons ci-dessous. En fonction de l'intervalle de temps que vous avez choisi, vous recevrez un mail à chaque fois que le motif sera trouvé dans les ressources.</p>

                        <?php
                        // Récupération de tous les crontabs recherchés
                        // $crontabs = getAllResearchedPaterns();

                        //for debug
                        $crontabs = $db->q(
                        'SELECT * FROM T_Alertes WHERE idUser = :id', 
                        array(
                            array('id', $_SESSION['id'], PDO::PARAM_INT)
                            )
                        );




                        if (count($crontabs) > 0) {
                            // Affichage du tableau si au moins un crontab est trouvé
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead><tr><th>Motif</th><th>Intervalle</th><th>Actions</th></tr></thead>';
                            echo '<tbody>';

                            foreach ($crontabs as $crontab) {
                                echo '<tr>';
                                echo '<td>"' . $crontab->patern . '"</td>';
                                echo '<td>' . $crontab->intervalle . ' heure(s)</td>';
                                echo '<td>
                                <a href="php/profile/deletePatern.php?id=' . $crontab->id . '">
                                    <button class="btn btn-danger" type="button">
                                        <i class="fas fa-trash fa-sm"></i>
                                    </button>
                                </a>
                                <a href="php/profile/sendMail.php?idPatern=' . $crontab->id. '">
                                    <button class="btn btn-success" type="button">
                                        <i class="fas fa-play fa-sm"></i>
                                    </button>
                                </a>
                                
                                </td>';
                                echo '</tr>';
                            }

                            echo '</tbody></table>';
                            echo '</div>';
                        } else {
                            // Affichage d'un message si aucun crontab n'est trouvé
                           // echo '<p>Aucun crontab trouvé.</p>';
                           echo '<div class="alert alert-info" role="alert">
                            Vous n\'avez pas encore ajouté de motifs. Cliquez sur le bouton ci-dessous pour en ajouter un.
                            </div>';
                            
                        }
                        ?>

                        <hr>

                        <h2>Ajouter un motif</h2>

                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#ajouterCrontabModal">Nouveau motif</button>

                        <div class="modal fade" id="ajouterCrontabModal" tabindex="-1" role="dialog" aria-labelledby="ajouterCrontabModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ajouterCrontabModalLabel">Nouveau motif</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="php/profile/addPatern.php">
                                            <div class="form-group">
                                                <label for="patern">Motif :</label>
                                                <input type="text" class="form-control" id="patern" name="patern">
                                            </div>
                                            <div class="form-group">
                                                <label for="intervalle">Intervalle :</label>
                                                <select class="form-control" id="intervalle" name="intervalle">
                                                    <option value="1">1 heure</option>
                                                    <option value="6">6 heures</option>
                                                    <option value="12">12 heures</option>
                                                    <option value="24">24 heures</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End of Page Content -->



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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Prêt à vous déconnecter ? :)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Sélectionnez "Déconnexion" ci-dessous si vous êtes prêt à mettre fin à votre session en cours.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary" href="php/profile/logoutUser.php">Déconnexion</a>
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
    include("js/chart-pie-langues.php");
    include("js/chart-pie-online.php");
    include("js/chart-pie-embargo.php");
    include("js/chart-pie-etablissements.php");
    include("js/chart-area.php");

    ?>
</body>



</html>