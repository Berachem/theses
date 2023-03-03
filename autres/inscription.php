<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Thèses.fr - Inscription</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/style.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Inscrivez-vous!</h1>
                                        <p>
                                            <a href="../index.php">Retour à l'accueil</a>
                                            <br>
                                            <br>
                                            <a class="small" href="connexion.php">Se connecter</a>
                                            <br>
                                           Créez des alertes et configurez vos paramètres !
                                        </p>
                                    </div>
                                    <!-- FORMULAIRE --> 
                                    <form class="user" method="POST" action="../php/profile/registerUser.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="pseudo" aria-describedby="pseudoHelp"
                                                placeholder="Pseudo" name="pseudo">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Entrez votre email" name="email">
                                            <p id="emailHelp" class="small text-danger" style="display:none">Email non valide ou existant</p>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" placeholder="Mot de passe" name="password">
                                            <p id="passwordHelp" class="small text-danger" style="display:none">Mot de passe ne respectant pas les critères RGPD
                                                (12 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial)  
                                            </p>
                                        </div>
                                        <input class="btn btn-primary btn-user btn-block" id="submit" type="submit">
                                    
                                        </input>
                                        <p id="errorHelp" class="small text-danger" style="display:none">Veuillez remplir tous les champs correctement</p>
                                    </form>
                                    <hr>
                                    <?php
                                        if (isset($_GET['registered']) && ($_GET['registered'] == 'false')) {
                                            echo '<p class="small text-danger">Erreur lors de l\'inscription</p>';
                                        }
                                    ?>
                                    <br>
                                    <br>
                                    <p class="small text-center">Thèses.berachem.dev</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // email regex validation and email not in database
        // emailHelp show
        
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            if (!re.test(email)) {
                return false;
            } 

            console.log (email);
            var formData = new FormData();
            formData.append("email", email);
            var res = fetch("../php/profile/isMailAvailable_API.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    document.getElementById("errorHelp").style.display = "none";
                    return true;
                } else {
                    document.getElementById("errorHelp").style.display = "block";
                    return false;
                }
            })
            .catch(error => {
                console.error(error);
            });

            return res;
 
        }
        
        function validatePassword(password) {
            var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{12,}$/;
            return re.test(password);
        }
        
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var emailHelp = document.getElementById("emailHelp");
            var passwordHelp = document.getElementById("passwordHelp");
            var submit = document.getElementById("submit");
            var errorHelp = document.getElementById("errorHelp");
        
            if (!validateEmail(email) && email.length > 0) {
                emailHelp.style.display = "block";
                submit.style.display = "none";
                errorHelp.style.display = "none";
                return false;
            } else {
                emailHelp.style.display = "none";
            }
        
            if (!validatePassword(password) && password.length > 0) {
                passwordHelp.style.display = "block";
                submit.style.display = "none";
                errorHelp.style.display = "none";
                return false;
            } else {
                passwordHelp.style.display = "none";
            }
        
            if (email.length > 0 && password.length > 0) {
                submit.style.display = "block";
                errorHelp.style.display = "none";
            } else {
                submit.style.display = "none";
                errorHelp.style.display = "block";
            }
        
            return true;
        }
        
        document.getElementById("email").addEventListener("keyup", function() {
            validateForm();
        });
        
        document.getElementById("password").addEventListener("keyup", function() {
            validateForm();
        });
        
        </script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/script.min.js"></script>

</body>

</html>