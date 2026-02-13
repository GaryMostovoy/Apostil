<?php
/*
Template Name: page-how-it-works
*/
?>
<?php get_header(); ?>

<main class="page how-page">
		<section class="about-page__hero hero-about-page">
		<div class="hero-about-page__container">
			<div class="hero-about-page__left">
				<div class="hero-home-page__box">
					<p data-watch="" data-watch-threshold="0.5" data-watch-once="" data-da=".hero-about-page__container, 992, 2" class="title">Begin Online Notarization</p>
					<div class="hero-home-page__decor">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
					</div>
				</div>
				<p class="hero-about-page__left-p-desk" data-watch="" data-watch-threshold="0.2" data-watch-once="">To process on your phone - <span>scan the QR code below.</span></p>
				<p class="hero-about-page__left-p-mob" data-watch="" data-watch-threshold="0.2" data-watch-once="">To start the online notarization now - <span>click the button</span></p>
				<div class="hero-about-page__qr">
					<img src="https://apostil.co/wp-content/uploads/2025/07/image-12.png" alt="qr">
				</div>
				<span>OR</span>
				<a href="https://app.proof.com/easy-link?ApiKey=uU5wdXRksmaFuahc2m124maA" class="button button-fill">Start Online Notarization</a>
			</div>
			<div class="hero-about-page__right popup-cursor">
				<div class='hero-about-page__play' data-popup='#popup' data-video-id='<?php echo esc_attr(CFS()->get('vimeo_not', 10)); ?>'>
					<svg class="play-icon" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
						<path d="M400 0C179.087 0 0 179.087 0 400C0 620.913 179.087 800 400 800C620.913 800 800 620.913 800 400C800 179.087 620.913 0 400 0Z"/>
						<path d="M546.236 435.647L334.785 557.727C328.264 561.492 320.439 563.486 313.013 563.486C289.438 563.486 269.81 544.307 269.81 520.733V276.571C269.81 252.997 289.438 233.818 313.013 233.818C320.442 233.818 328.04 235.809 334.564 239.577L546.125 361.657C559.502 369.379 567.542 383.208 567.542 398.651C567.542 414.093 559.612 427.925 546.236 435.647Z" fill="white" />
					</svg>
				</div>
				<div class="hero-about-page__img">
					<img src="https://apostil.co/wp-content/uploads/2025/11/individuals.webp" alt="individuals">
				</div>
				<div data-watch="" data-watch-threshold="0.5" data-watch-once="" class="hero-about-page__frame">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/logo.webp" alt="apostil">
				</div>
			</div>
		</div>
	</section>
	<section class="how-page__hero hero-how-page">
		<div class="hero-how-page__container">
			<div class="hero-home-page__box">
				<p data-watch="" data-watch-threshold="0.5" data-watch-once="" class="title">How to Notarize Your
					Document Online</p>
				<p data-watch="" data-watch-threshold="0.5" data-watch-once="">A fast, secure way to notarize your
					documents from anywhere — no lines, no travel, just a few
					simple steps.</p>
				<div class="hero-home-page__decor">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
				</div>
			</div>
			<div data-tabs class="tabs">
				<div data-tabs-body class="tabs__content">
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/01.webp" alt="Upload or scan your document">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-1'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-1'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/02.webp" alt="Create a Proof account">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-2'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-2'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/03.webp" alt="Verify your connection">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-3'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-3'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/04.webp" alt="Verify your identity">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-4'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-4'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/05.webp" alt="Connect with a notary on a video call">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-5'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-5'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/06.webp" alt="Access your completed document">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-6'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-6'); ?></p>
						</div>
					</div>
					<div class="tabs__body">
						<div class="tabs__img">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/07.webp" alt="Download or send document to another person">
						</div>
						<div class="tabs__title"><?php echo CFS()->get('how-it-works_notarize_h2-7'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-7'); ?></p>
						</div>
					</div>
				</div>
				<nav data-tabs-titles class="tabs__navigation">
					<div class="_tab-active">
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-1'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-1'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-2'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-2'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-3'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-3'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-4'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-4'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-5'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-5'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-6'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-6'); ?></p>
						</button>
					</div>
					<div>
						<span></span>
						<button type="button" class="tabs__title _tab-active"><?php echo CFS()->get('how-it-works_notarize_h2-7'); ?>
							<p><?php echo CFS()->get('how-it-works_notarize_p-7'); ?></p>
						</button>
					</div>
					<div class='hero-home-page__buttons' data-da=".tabs__content, 992, 7">
						<a href="https://app.proof.com/easy-link?ApiKey=uU5wdXRksmaFuahc2m124maA" class="button button-fill" target='_blank'  >Notarize Now</a>
					</div>
				</nav>
			</div>
		</div>
	</section>
	<section class="how-page__second second-how-page">
		<div class="second-how-page__container">
			<div class="second-how-page__row">
				<div class="second-how-page__img">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/11.webp" alt="Background checks">
				</div>
				<div class="second-how-page__text">
					<h1 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_h2-1'); ?></h1>
					<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_p-1'); ?></p>
					<?php
					$second_button = CFS()->get('how-it-works_second_button-1');
					if ( !empty($second_button) ) : ?>
						<a href="<?php echo esc_url( CFS()->get('how-it-works_second_link-1') ); ?>" class="button button-fill">
							<?php echo esc_html( $second_button ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="second-how-page__row">
				<div class="second-how-page__img">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/12.webp" alt="Birth, death and marriage certificates">
				</div>
				<div class="second-how-page__text">
					<h2 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_h2-2'); ?></h2>
					<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_p-2'); ?></p>
					<?php
					$second_button = CFS()->get('how-it-works_second_button-2');
					if ( !empty($second_button) ) : ?>
						<a href="<?php echo esc_url( CFS()->get('how-it-works_second_link-2') ); ?>" class="button button-fill">
							<?php echo esc_html( $second_button ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<section class="home-page__additional additional-home-page">
		<div class="additional-home-page__container">
			<div class="additional-home-page__left">
				<p>Additional Options</p>
				<ul>
					<li> - Same-day or 3-hour rush service (NYC only)</li>
					<li> - Certified translation into 50+ languages</li>
				</ul>
			</div>
			<a href="https://wa.me/19297762142" class="additional-home-page__right">
				<div class="additional-home-page__img">
					<img src="https://apostil.co/wp-content/uploads/2025/11/operator-2-e1762242718770.webp" alt="bot">
				</div>
				<div class="additional-home-page__text">
					<p>Call us <span>+1 855 APOSTIL</span> or text <span>WhatsApp</span></p>
					<p>Get Apostille assistance in any language</p>
				</div>
			</a>
		</div>
	</section>
	<section class="how-page__second second-how-page second-how-page-wdecor">
			<div class="second-how-page__container">
				<div class="second-how-page__row">
					<div class="second-how-page__img">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/13.webp" alt="Educational documents">
					</div>
					<div class="second-how-page__text">
						<h2 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_h2-3'); ?></h2>
						<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_p-3'); ?></p>
						<?php
						$second_button = CFS()->get('how-it-works_second_button-3');
						if ( !empty($second_button) ) : ?>
							<a href="<?php echo esc_url( CFS()->get('how-it-works_second_link-3') ); ?>" class="button button-fill">
								<?php echo esc_html( $second_button ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="second-how-page__row">
					<div class="second-how-page__img">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/how-to-work/14.webp" alt="All other documents">
					</div>
					<div class="second-how-page__text">
						<h2 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_h2-4'); ?></h2>
						<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo CFS()->get('how-it-works_second_p-4'); ?></p>
						<?php
						$second_button = CFS()->get('how-it-works_second_button-4');
						if ( !empty($second_button) ) : ?>
							<a href="<?php echo esc_url( CFS()->get('how-it-works_second_link-4') ); ?>" class="button button-fill">
								<?php echo esc_html( $second_button ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="second-how-page__label">
					<p>Please Note: Department of State is unable to Authenticate/Apostille Federal Documents. </p>
				</div>
			</div>
		</section>
</main>

<?php get_footer(); ?>