<?php
    session_start();
    include 'header.php';

    $partnersQuery = "SELECT * FROM partners WHERE is_deleted = 0";
    $partnersStmt = $pdo->query($partnersQuery);
    $partners = $partnersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    h1, p{
    text-align: center;
    }

    label{
    display:block;
    margin:1em 0 .2em;
    }

    input, textarea{
    display:block;
    width:100%;
    padding:.3em;
    font-size:20px;
    background-color:#fbfbfb;
    border:solid 1px #CCC;
    resize:vertical;
    }

    textarea{
    min-height:180px;
    }

    @media screen and (min-width:600px) {
        form:after{
            content:'';
            display:block;
            clear:both;
        }
        .column{
            width:50%;
            padding:1em;
            float:left;
        }
    }
</style>  
    <section class="abt bg-light">
        <div class="container">
            <div class="row align-items-center py-5">
                <center>
                    <div class="col-md-12 text-white">
                        <h1 style="color: black;"><?php echo __('about_us'); ?></h1>
                        <p class="text-dark">
                            <?php echo __('para_about'); ?>
                        </p>
                    </div>
                </center>
            </div>
        </div>
    </section>
    <section class="bg-light px-5 ">
        <div class="row text-center pb-3">
            <div class="col-lg-12 m-auto">
                <h1 class="h1"><?php echo __('our_services'); ?></h1>
                <p>
                    <?php echo __('para_services'); ?>
                </p>
            </div>
        </div>
        <div class="row py-5 ">
            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow bg-white">
                    <div class="h1 text-danger text-center"><i class="fa fa-motorcycle" aria-hidden="true"></i></div>
                    <h2 class="h5 mt-4 text-center"><?php echo __('motor_selling'); ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow bg-white">
                    <div class="h1 text-danger text-center"><i class="bi bi-wrench-adjustable"></i></div>
                    <h2 class="h5 mt-4 text-center"><?php echo __('All_thingd_related_to_motors'); ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow bg-white">
                    <div class="h1 text-danger text-center"><i class="bi bi-gear-fill"></i></div>
                    <h2 class="h5 mt-4 text-center"><?php echo __('Assistant_till_the_end'); ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow bg-white">
                    <div class="h1 text-danger text-center"><i class="fa fa-user"></i></div>
                    <h2 class="h5 mt-4 text-center"><?php echo __('hours_of_service'); ?></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->
    <!-- Start Brands -->
    <div class="bg-light">
        <div class="col-lg-12">
            <h1 class="h1"><?php echo __('Our_Partners'); ?></h1>
            <p class="text-center">
                <?php echo __('para_partners'); ?>
            </p>
        </div>
        <center>
            <div class="col-lg-10">
                <div class="row">
                    <?php foreach ($partners as $partner): ?>
                        <div class="col-md-3">
                            <a href="<?php echo $partner['website_link']; ?>" target="_blank"><img class="img-fluid brand-img" src="<?php echo $partner['logo']; ?>" alt="Brand Logo"></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </center>
    </div>
    <form class="p-5 bg-light" action="contact_form.php" method="post" id="section_id">
        <h1><?php echo __('contact_us'); ?></h1>
        <center>
            <p><?php echo __('contact_p'); ?></p>
        </center>
        <div class="column">
            <label for="the-name"><?php echo __('name'); ?></label>
            <input type="text" name="name" id="the-name">

            <label for="the-email"><?php echo __('email'); ?> :</label>
            <input type="email" name="email" id="the-email">

            <label for="the-phone"><?php echo __('phone_number'); ?> :</label>
            <input type="tel" name="phone" id="the-phone">

        </div>
        <div class="column">
            <label for="the-message"><?php echo __('message'); ?> :</label>
            <textarea class="mb-4" name="message" id="the-message"></textarea>
            <input type="submit" value="<?php echo __('send_message'); ?>">
        </div>
    </form>
<?php include 'footer.php';?>