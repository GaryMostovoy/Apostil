<?php get_header(); ?>

<main class="page blog-page">
	<section class="about-page__hero hero-about-page">
		<div class="hero-about-page__container">
			<div class="hero-about-page__left">
				<div class="hero-home-page__box">
					<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" data-da=".hero-about-page__container, 992, 2" class="title"><?php single_cat_title(); ?></h1>
					<div class="hero-home-page__decor">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="blog__categories categories-blog">
		<div class="categories-blog__container">
			<nav class="categories-blog__filter">
				<ul class="categories-blog__list">
					<li>
						<a href="<?= esc_url(get_permalink(get_option('page_for_posts'))); ?>"
						   class="<?= is_home() ? 'active' : ''; ?>">
							All ARTICLES
						</a>
					</li>

					<?php
					$categories = get_categories([
						'taxonomy' => 'category',
						'hide_empty' => true,
						'include'    => [19, 16, 17, 18],
						'orderby'    => 'include'
					]);

					foreach ($categories as $category) : $is_active = is_category($category->term_id); ?>
					<li>
						<a href="<?= esc_url(get_category_link($category->term_id)); ?>"
						   class="<?= $is_active ? 'active' : ''; ?>">
							<?= esc_html($category->name); ?>
						</a>
					</li>
					<?php endforeach;
					?>
				</ul>
			</nav>
			<div class="categories-blog__articles">
				<?php 
				$paged = max( 1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page') );

				if (have_posts()) : 
					while (have_posts()) : the_post(); 
						$cats = wp_get_post_categories(get_the_ID(), ['fields' => 'slugs']);
						$cat_slugs = implode(' ', $cats);
				?>
						<article class="categories-blog__article post-item" data-category="<?= esc_attr($cat_slugs); ?>">
							<div class="categories-blog__top">
								<date><?= get_the_date('m.d.Y'); ?></date>
							</div>
							<div class="categories-blog__center">
								<a href="<?= get_permalink(); ?>" class="categories-blog__img">
									<?php has_post_thumbnail()
										? the_post_thumbnail('medium', ['alt' => get_the_title()])
										: print '<img src="'.get_template_directory_uri().'/img/placeholder.jpg" alt="">';
									?>
								</a>
								<date><?= get_the_date('m.d.Y'); ?></date>
								<h3><a href="<?= get_permalink(); ?>"><?= get_the_title(); ?></a></h3>
							</div>
							<div class="categories-blog__bottom">
								<p><?= wp_trim_words(get_the_excerpt(), 28, '...'); ?></p>
								<a href="<?= get_permalink(); ?>" class="button button-fill">Read more</a>
							</div>
						</article>
				<?php 
					endwhile; 
				endif; 
				?>
			</div>

			<?php if ($wp_query->max_num_pages > 1) : ?>
				<div class="categories-blog__pagination">
					<?php
					echo paginate_links([
						'base' => get_pagenum_link(1) . '%_%',
						'format' => 'page/%#%/',
						'current' => $paged,
						'total' => $wp_query->max_num_pages,
						'prev_text' => '<span class="categories-blog__prev"><svg width="20" height="11" viewBox="0 0 20 11" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19.7812 5.29891L1.4139 5.29891M1.4139 5.29891L6.00574 9.89075M1.4139 5.29891L6.00574 0.707075" stroke="#004020" stroke-width="2" />
								</svg></span>',
						'next_text' => '<span class="categories-blog__next"><svg width="20" height="11" viewBox="0 0 20 11" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M4.01431e-07 5.29891L18.3673 5.29891M18.3673 5.29891L13.7755 9.89075M18.3673 5.29891L13.7755 0.707075" stroke="#004020" stroke-width="2" />
								</svg></span>',
					]);
					?>
				</div>
			<?php endif; ?>
	
		</div>
	</section>
</main>

<?php get_footer(); ?>