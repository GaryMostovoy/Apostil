<?php
/*
Template Name: page-terms-of-use
*/
?>
<?php get_header(); ?>

<main class="page policy-page">
	<section class="policy-page__hero hero-policy-page">
		<div class="hero-policy-page__container">
			<div class="hero-policy-page__decor">
				<img src="<?php bloginfo('template_url'); ?>/assets/img/decor-policy.webp" alt="apostil">
			</div>
			<div class="hero-home-page__box">
				<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" class="title"><?php the_title() ?></h1>
				<div class="hero-home-page__decor">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
				</div>
			</div>
			<?php the_content() ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>