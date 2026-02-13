<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?> 
	<link rel="shortcut icon" href="https://apostil.co/wp-content/uploads/2025/06/Apostil_logo-03.png"> 
	<link rel="apple-touch-icon" href="https://apostil.co/wp-content/uploads/2025/12/logo.jpg">
	<meta name="facebook-domain-verification" content="rq4xmz0r0zmkjkss35vwq9r6h0c0t4" />
</head>

<body>

	<div class="preloader">
		<video class="preloader__video" autoplay muted playsinline loop>
			<source src="https://apostil.co/wp-content/uploads/2025/06/stamp_1.gif.mp4" type="video/mp4">
		</video>
	</div>
	<div class="wrapper">
		<header class="header">
			<div class="header__container">
				<a href='/' class='header__logo'><img src='<?php bloginfo('template_url'); ?>/assets/img/logo.webp' alt='logo'></a>
				<div class='header__menu menu'>
					<button type='button' class='menu__icon icon-menu' aria-label="Open menu"> <span></span></button>
					<nav class='menu__body'>
					<?php wp_nav_menu(['menu_class' => 'menu__list', 'theme_location' => 'menu_header']); ?>	
					</nav>
				</div>
				<a href="https://apostil.co/order-now/" class="header__desk button button-fill" data-da=".menu__list, 991.98, 6">Order Now</a>
				<a href='https://wa.me/19297762142' class="header__mob _icon-phone" aria-label="call now"></a>
			</div>
		</header>