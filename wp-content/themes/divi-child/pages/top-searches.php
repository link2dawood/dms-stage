<?php /* Template Name: Top Searches Template */
get_header(); ?>
<main class="py-5 px-g">
    <div class="position-relative top-search-input mb-30">
        <input type="search" class="p-2 pl-5 font-lg text-eight font-inter w-100 text-primary rounded-lg top-searches-search-bar primary-top-searchbar no-zoom" placeholder="Search">
        <i class="fa fa-search text-primary position-absolute font-md"></i>
    </div>
    <div class="d-none align-items-center justify-content-between user-searched-query-wrapper mb-30">
        <h1 class="user-searched-query p-0 m-0 text-capitalize font-weight-bold text-eight font-xl"></h1>
        <i class="fa fa-times font-xxl text-danger cursor-pointer remove-user-searched-query"></i>
    </div>
    <div class="top-search-container">
        <h3 class="text-eight font-lg font-weight-bold mb-20 p-0">Top Searches</h3>
        <?php echo do_shortcode('[mwtsa_display_latest_searches unit="month" count="8" only_with_results="false" wrapper_class="top-searches-wrapper mwtsa-latest-searches"]'); ?>
    </div>
    <!-- Search Result Section -->
    <!-- Inventory Search Result Section -->
    <div class="inventory-results mb-5 d-none">
        <div class="d-flex align-items-center justify-content-between mb-20">
            <h3 class="text-eight font-xl font-weight-bold mb-0 p-0">Top Searches</h3>
            <p class="inventory-results-counter m-0 p-0 text-eight">0</p>
        </div>
        <div class="inventory-results-wrapper"></div>
    </div>
    <!-- Beyond Value Results Section -->
    <div class="beyondvalue-results mb-5 d-none">
        <div class="d-flex align-items-center justify-content-between mb-20">
            <h3 class="text-eight font-xl font-weight-bold mb-0 p-0">Beyond Value</h3>
            <p class="beyondvalue-results-counter m-0 p-0 text-eight">0</p>
        </div>
        <div class="beyondvalue-results-wrapper"></div>
    </div>
    <!-- Blogs Results Section -->
    <div class="blogs-results d-none">
        <div class="d-flex align-items-center justify-content-between mb-20">
            <h3 class="text-eight font-xl font-weight-bold mb-0 p-0">Blogs</h3>
            <p class="blogs-results-counter m-0 p-0 text-eight">0</p>
        </div>
        <div class="blogs-results-wrapper"></div>
    </div>
</main>
<?php get_footer(); ?>