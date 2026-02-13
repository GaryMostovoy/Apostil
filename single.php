<?php get_header(); ?>

<main class="page blog-page">
	<section class="article__page page-article">
		<div class="page-article__container">
			<div class="page-article__share">
			</div>
			<div class="page-article__img">
				<?php the_post_thumbnail( 'full', ['alt' => get_the_title()] ); ?>
			</div>
			<div class="page-article__info">
				<?php
					$cats = get_the_category();
					if ( !empty($cats) ): 
					?>
						<a href="<?php echo get_category_link($cats[0]->term_id); ?>">
							<?php echo esc_html($cats[0]->name); ?>
						</a>
				<?php endif; ?>
				<div class="page-article__date">
					<date><?php echo get_the_date('m.d.Y'); ?></date>
					<div class="page-article__box">
						<span>Share:</span>
						<div class="page-article__social">
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="_icon-fb"></a>
	<!-- 						<a href="#" class="_icon-instagram" onclick="navigator.clipboard.writeText('<?php echo get_permalink(); ?>'); return false;"></a> -->
							<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>"
							   target="_blank"
							   class="_icon-x">
							</a>
							<a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank"  rel="noopener" class="_icon-tg"></a>
						</div>
					</div>
				</div>
			</div>
			<h1 data-da=".page-article__share, 992, 1"><?php the_title(); ?></h1>
			<?php the_content() ?>
			<div class="page-article__buttons">
				<a href='https://apostil.co/prices/' class='button button-fill'>Get Quote</a>
				<a href='https://apostil.co/order-now/' class='button button-fill'>Order Now</a>
				<a href='https://wa.me/9297762142' class='button button-transparent'>WhatsApp</a>
				<a href="https://apps.apple.com/us/app/apostil/id6756267706" class='' target="_blank" rel="nofollow"><img src="https://apostil.co/wp-content/uploads/2026/01/app-store.webp" style="border: 0;" alt="App store" /></a>
				<a href="https://play.google.com/store/apps/details?id=co.apostil" class='' target="_blank" rel="nofollow"><img src="https://apostil.co/wp-content/uploads/2026/01/google-play.webp" style="border: 0;" alt="Google play" /></a>

			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>