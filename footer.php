<footer class="bg-dark" id="footer">
    <div class="mx-4">
        <div class="row">
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">
                    <img src="assets/img/logo-footer.png" class="footer-img" alt="<?php echo $translations['logo_alt']; ?>" />
                </h2>
                <ul class="list-unstyled footer-link-list">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        <span class="footer text-decoration-none"><?php echo $translations['address']; ?></span>
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <span class="footer text-decoration-none">+212 629 474 030</span>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span class="footer text-decoration-none">aymenoml2002@gmail.com</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 pt-5">
                <h2 class="h2 border-bottom pb-3 border-light"><?php echo $translations['products_heading']; ?></h2>
                <ul class="list-unstyled footer-link-list">
                    <?php foreach ($categories as $category): ?>
                        <li><a class="footer text-decoration-none" href="all-categories.php?category=<?php echo urlencode($category['NomCategorie']); ?>"><?php echo $category['NomCategorie']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4 pt-5">
                <h2 class="h2 border-bottom pb-3 border-light"><?php echo $translations['further_info_heading']; ?></h2>
                <ul class="list-unstyled footer-link-list">
                    <li><a class="footer text-decoration-none" href="index.php"><?php echo $translations['home']; ?></a></li>
                    <li><a class="footer text-decoration-none" href="about.php"><?php echo $translations['about_us']; ?></a></li>
                    <li><a class="footer text-decoration-none" href="about.php#section_id"><?php echo $translations['contact']; ?></a></li>
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
   <script src="assets/js/slick.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>