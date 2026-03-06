<?php

/*

Template Name: HTML Sitemap

*/

?>
<?php get_header(); ?>

<main class="page">
	<section class="page-404 hero-prices-page">
		<div class="page-404__container">
			<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" class="title sitemap-title">Sitemap</h1>
			<div class="html-sitemap">
				<nav>
					<?php wp_nav_menu(['menu_class' => 'sitemap__list', 'theme_location' => 'menu_sitemap']); ?>	
				</nav>
			</div>
		</div>
	</section>
<?php get_footer(); ?>