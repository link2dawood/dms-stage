<div class="inventory-dropdown-container d-none">
    <div class="pr-md-5 mr-md-2">
        <h3 class="font-md font-helvetica font-weight-bold text-grey-3 text-capitalize pb-0 mb-30">
            shop by vehicle style
        </h3>
        <div class="vehicles-types mb-20 d-flex justify-content-between flex-wrap">
            <div class="d-flex align-items-center justify-content-end justify-content-lg-center flex-column w-50">
                <a class="d-flex justify-content-center align-items-center mb-2" href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=coupe&type_of_vehicle%5b%5d=sedan">
                    <img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-dropdown-cars.webp" alt="car image" width="135" height="39">
                </a>
                <a href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=coupe&type_of_vehicle%5b%5d=sedan" class="text-link d-block text-center font-helvetica font-xs text-primary">Cars</a>
            </div>
            <div class="d-flex align-items-center justify-content-end justify-content-lg-center flex-column w-50">
                <a class="d-flex justify-content-center align-items-center mb-2" href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=suv&type_of_vehicle%5b%5d=hatchback">
                    <img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-dropdown-suvs.webp" alt="Suvs image" width="135" height="45">
                </a>
                <a href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=suv&type_of_vehicle%5b%5d=hatchback" class="text-link d-block text-center font-helvetica font-xs text-primary">Suvs & Crossovers</a>
            </div>
            <div class="d-flex align-items-center justify-content-end justify-content-lg-center flex-column w-50">
                <a class="d-flex justify-content-center align-items-center mb-2" href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=truck">
                    <img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-dropdown-trucks.webp" alt="truck image" width="135" height="50">
                </a>
                <a href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=truck" class="text-link d-block text-center font-helvetica font-xs text-primary">Trucks</a>
            </div>
            <div class="d-flex align-items-center justify-content-end justify-content-lg-center flex-column w-50">
                <a class="d-flex justify-content-center align-items-center mb-2" href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=wagon">
                    <img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-dropdown-vans.webp" alt="vans image" width="135" height="50">
                </a>
                <a href="<?php echo site_url();?>/inventory/?type_of_vehicle%5b%5d=wagon" class="text-link d-block text-center font-helvetica font-xs text-primary">Vans</a>
            </div>
        </div>
        <?php 
            $args = array(
                'post_type'      => 'listings',
                'posts_per_page' => -1,
            );
            $posts = get_posts($args);
            $postCount = count($posts);
            echo '<a data-color="white" class="view-all-inventory-cta text-white p-3 text-uppercase btn btn-primary d-inline-block w-100 rounded-lg" href="'.site_url().'/inventory">View all vehicles ('.$postCount.')</a>';
        ?>
    </div>
    <div>
        <h3 class="font-md font-helvetica font-weight-bold text-grey-3 text-capitalize pb-0 mb-30">
           price
        </h3>
        <a href="<?php echo site_url();?>/inventory/?final_price%5b%5d=10000" class="text-capitalize font-sm mb-20 d-block"> under $10,000</a>
        <a href="<?php echo site_url();?>/inventory/?final_price%5b%5d=10000%2c20000" class="text-capitalize font-sm mb-20 d-block">$10,000 - $20,000</a>
        <a href="<?php echo site_url();?>/inventory/?final_price%5b%5d=20000%2c30000" class="text-capitalize font-sm mb-20 d-block">$20,000 - $30,000</a>
        <a href="<?php echo site_url();?>/inventory/?final_price%5b%5d=40000%2c50000" class="text-capitalize font-sm mb-20 d-block">$40,000 - $50,000</a>
        <a href="<?php echo site_url();?>/inventory/?final_price%5b%5d=50000" class="text-capitalize font-sm d-block">over $50,000</a>          
    </div>
    <div>
        <h3 class="font-md font-helvetica font-weight-bold text-grey-3 text-capitalize pb-0 mb-15">specials</h3>
        <a href="<?php echo site_url();?>/specials/managers-specials" class="text-capitalize mb-15 d-inline-block">managers specials</a>  
        <h3 class="font-md font-helvetica font-weight-bold text-grey-3 text-capitalize pb-0 mb-30">Shopping Tools</h3>
        <a href="<?php echo site_url();?>/inventory/?certified-pre-owned-toyota&make%5b%5d=toyota&certified%5b%5d=yes" class="text-capitalize font-sm mb-20 d-block">Certified Pre-Owned Toyota</a>
        <a href="<?php echo site_url();?>/inventory/?certified-pre-owned-kia&make%5b%5d=kia&certified%5b%5d=yes" class="text-capitalize font-sm mb-20 d-block">Certified Pre-Owned Kia</a>
        <a href="<?php echo site_url();?>/inventory/?certified-pre-owned-ford&make%5b%5d=ford&certified%5b%5d=yes" class="text-capitalize font-sm mb-20 d-block">Certified Pre-Owned Ford</a>
        <a href="<?php echo site_url();?>/finance/value-your-trade" class="text-capitalize font-sm mb-20 d-block">We Will Buy Your Vehicle!</a>
    </div>
</div>