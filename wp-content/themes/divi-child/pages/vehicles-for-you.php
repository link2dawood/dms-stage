<?php /* Template Name: Vehicles For You */
get_header(); ?>

<main class="py-5 px-g">
    <h1 class="text-capitalize text-primary font-lg font-inter font-weight-bold p-0 mb-30">Tell Us about your dream vehicle</h1>
    <div class="vehicles-for-you-form garage-response-wrapper my-garage-tab-wrapper" data-paged="1">
        <?php echo do_shortcode('[contact-form-7 id="0812027" title="My Garage Form"]'); ?>
    </div>
    <button class="btn btn-primary w-100 load-more-garage-results d-none" data-paged="1">Load More</button>
</main>

<?php get_footer(); ?>
<script>
    $(document).ready(function($) {
        $('.select2-dropdown').select2()
    })
</script>