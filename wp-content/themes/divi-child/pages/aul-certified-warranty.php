<?php /* Template Name: Aul Certified Warranty Tempalte */ ?>
<?php get_header(); ?>
<?php 
    $bannerGroup = wp_get_attachment_image_src(get_field('aul_certified_warranty_banner','options'), 'full');
    $bannerImage = $bannerGroup[0];
    $banenrWidth = $bannerGroup[1];
    $bannerHeight = $bannerGroup[2];
    $bannerAlt = get_post_meta(get_field('aul_certified_warranty_banner', 'options'), '_wp_attachment_image_alt', true);
    if( get_field('aul_certified_warranty_banner_heading','options') ) {
        $bannerHeading = get_field('aul_certified_warranty_banner_heading', 'options');
    }
    if( get_field('aul_certified_warranty_banner_content','options') ){
        $bannerContent = get_field('aul_certified_warranty_banner_content', 'options');
    }
    echo divi_child_page_banner($bannerImage,$bannerAlt, $banenrWidth, $bannerHeight, $bannerHeading, $bannerContent );
    ?>
    <div class="content-page-container">
       <div class="row">
            <div class="col-12 col-lg-9">
                <h3 class="font-segoe font-weight-bold p-0 font-xxl text-grey-3 mb-30"><?php echo get_the_title(); ?></h3>
                <p class="text-grey-3 font-helvetica font-lg mb-30">AUL Certified Limited Warranty Program means peace of mind! Vehicles with an AUL Certified Limited Warranty are available for 3 months or 3,000 miles - whichever comes first.</p>
                <ul class="text-grey-6 font-lg pl-20 mb-5" style="list-style-type:disc;">
                    <li class="mb-2">Visual Inspection</li>
                    <li class="mb-2">Engine Check</li>
                    <li class="mb-2">Transmission Check</li>
                    <li class="mb-2">Suspension Check</li>
                    <li class="mb-2">Interior and Exterior Inspection</li>
                    <li class="mb-2">Air Conditioning System Check</li>
                    <li class="mb-2">Braking System Check</li>
                    <li class="mb-2">Static Test</li>
                    <li>Road Test</li>
                </ul>
                <p class="font-helvetica text-grey-3 font-lg pb-0">
                    Covered Components ENGINE Crankshaft and bearings, oil pump, fuel pump, water pump, internal timing gears or chain, camshaft bearings, valve lifters, rocker arm assemblies and push rods, valve guides, pistons and rings, wrist pins, connection rods, distributor drive gear, all internal components of and including engine block and cylinder heads, manifolds, and the turbocharger housing. Gaskets and oil seals. DIESEL ENGINE (if equipped) All of the above listed parts, plus diesel fuel injection pump and vacuum pump. TRANSMISSION/TRANSAXLE Case, all internally lubricated parts, torque converter. Includes transfer case and all internally lubricated parts. FRONT/REAR-WHEEL DRIVE Final drive housing, all internally lubricated parts, axle shafts, axle housing and axle shaft bearings, constant velocity joints, axle housing, all internally lubricated parts, propeller shafts, “U” joints.
                </p>
            </div>
            <div class="col-12 col-lg-3">
                <?php echo divi_child_content_links(); ?>
            </div>
       </div>
    </div>


<?php get_footer() ?>