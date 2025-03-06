<?php include 'db.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MOTORENA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/wwal.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style-login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet"type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"/>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
   <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="nav_top">
      <div class="container text-light">
         <div class="w-100 d-flex justify-content-between">
            <div class="header">
               <i class="fa fa-envelope mx-2"></i>
               <a class="navbar-sm-brand text-light text-decoration-none">alarcon442002@gmail.com</a>
               <i class="fa fa-phone mx-2"></i>
               <a class="navbar-sm-brand text-light text-decoration-none">+212 629 474 030</a>
            </div>
            <div>
               <a class="text-light" href="https://www.facebook.com/profile.php?id=100034950181394" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
               <a class="text-light" href="https://www.instagram.com/alar_con44/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
               <a class="text-light" href="https://twitter.com/alarcon_44" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
               <a class="text-light" href="https://www.linkedin.com/in/aymen-oumaalla-676b46268/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
            </div>
         </div>
      </div>
   </nav>
    <nav class="navbar navbar-expand-lg navbar-light shadow">
      <div class="container-fluid" style=" display: flex; align-items: center; justify-content: center;">
         <center>
            <a class="navbar-brand text-success logo h1 align-self-center  mx-4" href="index.php">
               <img src="assets/img/logo.png" alt="Logo"/>
            </a>
         </center>
      </div>
   </nav>
    <div class="section bg-light ">
		<div class="login-container">
			<div class="col-12 text-center align-self-center py-5">
				<div class="section pb-5 pt-5 pt-sm-2 text-center">
					<h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
		          	<input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
		          	<label for="reg-log"></label>
					<div class="card-3d-wrap mx-auto">
						<div class="card-3d-wrapper">
							<div class="card-front">
								<div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3" id="login_side">Log In</h4>
                                        <form action="log-in.php" method="POST">
                                            <div class="form-group">
                                                <input type="Email" class="form-style" id="" name="email" placeholder="Email" required>
                                                <i class="input-icon uil uil-at"></i>
                                            </div>    
                                            <div class="form-group mt-2">
                                                <i class="input-icon uil uil-lock-alt"></i>
                                                <input type="password" class="form-style" id="password" name="password" placeholder="Password" required>
                                                <span class="material-icons-outlined eye-icon" onclick="togglePasswordVisibility()">
                                                    visibility
                                                </span>
                                            </div>
                                            <button type="submit" class="btn mt-2">Login</button>
                                            <p class="mb-0 mt-4 text-center"><a href="forgot-password.php" class="link">Forgot your password?</a></p>
                                            <?php
                                                if (isset($_GET['error'])) {
                                                    $error = $_GET['error'];
                                                    echo '<div class="alert alert-danger mt-3">' . $error . '</div>';
                                                }
                                            ?>
                                        </form>
                                    </div>
		      					</div>
		      				</div>
							<div class="card-back">
								<div class="center-wrap">
									<div class="section text-center">
										<h4 class="my-3 py-3" id="signup_side">Sign Up</h4>
                                 <form action="sign-up.php" method="post">
                                    <div class="form-group">
                                       <input type="text" class="form-style" name="username" placeholder="Full Name" required>
                                       <i class="input-icon uil uil-user"></i>
                                   </div>	
                                   <div class="form-group mt-2">
                                       <input type="Email" class="form-style" name="Email" placeholder="Email" required>
                                       <i class="input-icon uil uil-at"></i>
                                   </div>
                                   <div class="form-group mt-2">
                                       <i class="input-icon uil uil-lock-alt"></i>
                                       <input type="password" class="form-style" id="password1" name="password" placeholder="Password" required>
                                       <span class="material-icons-outlined eye-icon" onclick="togglePasswordVisibility1()">
                                          visibility
                                       </span>
                                    </div>
                                   <button type="submit" class="btn mt-2">Register</button>
                                 </form>
			      					</div>
			      				</div>
		      				</div>
		      			</div>
			      	</div>
		      	</div>
	      	</div>
	    </div>
	</div>
<footer class="bg-dark" id="footer">
    <div class="px-5">
        <div class="row">
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">
                    <img src="assets/img/logo-footer.png" class="footer-img" alt="logo_alt" />
                </h2>
                <ul class="list-unstyled footer-link-list">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        <span class="text-decoration-none">address</span>
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <span class="text-decoration-none">+212 629 474 030</span>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span class="text-decoration-none">aymenoml2002@gmail.com</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 pt-5">
                <h2 class="h2 border-bottom pb-3 border-light">Products Heading</h2>
                <ul class="list-unstyled footer-link-list">
                    <?php
                    $stmt = $pdo->prepare("SELECT NomCategorie FROM categorieproduit");
                    $stmt->execute();

                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    foreach ($stmt->fetchAll() as $row) {
                        $categoryName = urlencode($row['NomCategorie']);
                        echo '<li><a class="text-decoration-none" href="all-categories.php?category=' . $categoryName . '">' . $row['NomCategorie'] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-4 pt-5">
                <h2 class="h2 border-bottom pb-3 border-light">Further Info Heading</h2>
                <ul class="list-unstyled footer-link-list"> 
                    <li><a class="text-decoration-none" href="index.php">Home</a></li>
                    <li><a class="text-decoration-none" href="about.php">About Us</a></li>
                    <li><a class="text-decoration-none" href="about.php#section-id">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->
<!-- Start Script -->
   <script src="assets/js/jquery-1.11.0.min.js"></script>
   <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
   <script src="assets/js/bootstrap.bundle.min.js"></script>
   <script src="assets/js/script-index.js"></script>
   <script src="assets/js/custom.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
   <script src="assets/js/custom.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></body>
</html>