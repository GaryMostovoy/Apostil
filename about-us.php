<?php
/*
Template Name: page-about-us
*/
?>
<?php get_header(); ?>

<main class="page about-page">
	<section class="about-page__hero hero-about-page">
		<div class="hero-about-page__container">
			<div class="hero-about-page__left">
				<div class="hero-home-page__box">
					<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" data-da=".hero-about-page__container, 992, 2" class="title"><?php echo CFS()->get('about-us_h1'); ?>
					</h1>
					<div class="hero-home-page__decor">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
					</div>
				</div>
				<p data-watch="" data-watch-threshold="0.2" data-watch-once=""><?php echo CFS()->get('about-us_p1'); ?></p>
				<p data-watch="" data-watch-threshold="0.2" data-watch-once=""><?php echo CFS()->get('about-us_p2'); ?></p>
			</div>
			<div class="hero-about-page__right popup-cursor" data-popup='#popup-video' data-video-id='<?php echo esc_attr(CFS()->get('about-us_video')); ?>'>
				<div class='hero-about-page__play'>
					<svg class="play-icon" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
						<path d="M400 0C179.087 0 0 179.087 0 400C0 620.913 179.087 800 400 800C620.913 800 800 620.913 800 400C800 179.087 620.913 0 400 0Z"/>
						<path d="M546.236 435.647L334.785 557.727C328.264 561.492 320.439 563.486 313.013 563.486C289.438 563.486 269.81 544.307 269.81 520.733V276.571C269.81 252.997 289.438 233.818 313.013 233.818C320.442 233.818 328.04 235.809 334.564 239.577L546.125 361.657C559.502 369.379 567.542 383.208 567.542 398.651C567.542 414.093 559.612 427.925 546.236 435.647Z" fill="white" />
					</svg>
				</div>
				<div class="hero-about-page__img">
					<img src="<?php echo CFS()->get('about-us_img'); ?>" alt="utverzdenie">
				</div>
				<div data-watch="" data-watch-threshold="0.5" data-watch-once="" class="hero-about-page__frame">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/logo.webp" alt="apostil">
				</div>
			</div>
		</div>
	</section>
	<section class="how-page__second second-how-page second-how-page-wdecor">
		<div class="second-how-page__container">
			<?php
				$team = CFS()->get('how-it-works_second_loop-about');
				if ( $team ) :
				foreach ( $team as $member ) : ?>
					<div class="second-how-page__row second-how-page__row-reverce">
						<div class="second-how-page__img">
							<img src="<?php echo $member['how-it-works_second_image-1-about']; ?>" alt="<?php echo $member['how-it-works_second_h2-1-about']; ?>">
						</div>
						<div class="second-how-page__text">
							<h2 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo $member['how-it-works_second_h2-1-about']; ?></h2>
							<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo $member['how-it-works_second_text-1-about']; ?></p>
							<?php if ( !empty($member['how-it-works_second_button-1-about']) ) : ?>
							<a href="<?php echo esc_url($member['how-it-works_second_link-1-about']); ?>" class="button button-fill">
								<?php echo esc_html($member['how-it-works_second_button-1-about']); ?>
							</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach;
				endif;
			?>
		</div>
	</section>	
	<section class="about-page__second second-about-page">
		<div class="second-about-page__container">
			<div class="second-about-page__left">
				<span class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('about-us_why_choose_h'); ?></span>
				<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('about-us_why_choose_p'); ?>
				</p>
				<a href='https://wa.me/19297762142' class="faq-home-page__bot" data-da=".second-about-page__right, 768, 6">
					<div class="faq-home-page__icon">
						<img src="https://apostil.co/wp-content/uploads/2025/11/operator-1-e1762242746888.webp" alt="bot">
					</div>
					<div class="faq-home-page__text">
						<p>Call now <span>+1 855 APOSTIL</span> or get your <br> Notary assistant through <span>WhatsApp</span>
						</p>
					</div>
				</a>
				<a href="https://apostil.co/order-now/" class="button button-fill" data-da=".second-about-page__right, 768, 7">Order
					now</a>
			</div>
			<div class="second-about-page__right">
				<ul>								
					<?php
						$team = CFS()->get('about-us_why_choose_loop');
						if ( $team ) :
						foreach ( $team as $member ) : ?>
						<li data-watch="" data-watch-threshold="0.5" data-watch-once="">
							<a href='<?php echo $member['about-us_why_choose_link']; ?>'>	
							<div class="choose-home-page__li">
									<img src="<?php echo $member['about-us_why_choose_icon']; ?>" alt="<?php echo $member['about-us_why_choose_text']; ?>">
								</div>
								<p><?php echo $member['about-us_why_choose_text']; ?></p>	
							</a>
						</li>
						<?php endforeach;
						endif;
					?>					
				</ul>
				<div class='second-about-page__buttons'  data-da=".second-about-page__right, 768, 8">
				<button class="button button-video" data-popup='#popup' data-video-id='<?php echo esc_attr(CFS()->get('vimeo_not', 10)); ?>'>
					<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
					  <circle cx="21.5" cy="21.5" r="21.5" fill="#004020" fill-opacity="0.16" />
					  <circle cx="21.5" cy="21.5" r="17.5" fill="#004020" />
					  <path d="M17 24.6094V18.3253C17 16.8174 18.6054 15.8518 19.9374 16.5586L25.7381 19.6365C27.1412 20.381 27.1583 22.3853 25.7682 23.1537L19.9675 26.3598C18.6345 27.0966 17 26.1324 17 24.6094Z" fill="white" />
					</svg>
					<p>How to&nbsp;<span>Notarize</span></p>
				</button>
				<button class="button button-video"  data-popup='#popup1' data-video-id='<?php echo esc_attr(CFS()->get('vimeo_ap', 10)); ?>'>
					<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="21.5" cy="21.5" r="21.5" fill="#004020" fill-opacity="0.16" />
						<circle cx="21.5" cy="21.5" r="17.5" fill="#004020" />
						<path d="M17 24.6094V18.3253C17 16.8174 18.6054 15.8518 19.9374 16.5586L25.7381 19.6365C27.1412 20.381 27.1583 22.3853 25.7682 23.1537L19.9675 26.3598C18.6345 27.0966 17 26.1324 17 24.6094Z" fill="white" />
					</svg>
					<p>How to&nbsp;<span>Apostille</span></p>
				</button>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>