<?php
/*
Template Name: page-pay
*/

?>

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
	<style> body { visibility: hidden; }</style>
</head>

<body>
	<div id='mobile' class="mobile">
		<div class="mobile__container">
			<div class="mobile__top">
				<h2>Thank you, <span id="customer-name"></span></h2>
				<p>Your order: <span id="order-number"></span></p>
				<p>Total: <span id="order-total"></span></p>				
				<img src='<?php bloginfo('template_url'); ?>/assets/img/logo-lay.png' alt='logo'>				
			</div>
			<div class="mobile__content">
				<span>Next steps:</span>
				<div class="mobile__items">
					<div class="mobile__item">
						<div class="mobile__icon">
							<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/credit-card-1-1.svg"></object>
						</div>
						<p>Complete your payment</p>
					</div>
					<div class="mobile__item">
						<div class="mobile__icon">
							<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/Vector-31.svg"></object>
						</div>
						<p>We confirm your order</p>
					</div>
					<div class="mobile__item">
						<div class="mobile__icon">
							<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/Group-2.svg"></object>
						</div>
						<p>Documents are processed</p>
					</div>
					<div class="mobile__item">
						<div class="mobile__icon">
							<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/ais-1.svg"></object>
						</div>
						<p>Ship with tracking number</p>
					</div>
				</div>
			</div>
			<div class="mobile__buttons">
				<span>Choose a payment method:</span>
				<div class="mobile__button">
					<a href="" id="paypall-pay">
						<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/XMLID-2.svg"></object>
					</a>
					<a href="" id="stripe-pay">
						<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/stripe-4-1.svg"></object>
					</a>
					<a href="" id="visa-pay">
						<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/visa-logo.svg"></object>
					</a>
					<a href="" id="wise-pay">
						<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/wise-1-1.svg"></object>
					</a>
					<button class="flip-card" id="cash-pay">
						<div class="flip-card-inner">
							<div class="flip-card-front">
								<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/Cash_App-1.svg"></object>
							</div>
							<div class="flip-card-back">
								<span>Tag: $Apostil</span>
								<span>929.776.2142</span>
							</div>
						</div>
					</button>
					<button class="flip-card" id="zelle-pay">
						<div class="flip-card-inner">
							<div class="flip-card-front">
								<object type="image/svg+xml" data="https://apostil.co/wp-content/uploads/2026/01/zelle-1-1.svg"></object>
							</div>
							<div class="flip-card-back">
								<span>Tag: Apostil</span>
								<span>929.776.2142</span>
							</div>
						</div>
					</button>
				</div>
			</div>
<!-- 			<div class="mobile__bottom">
				<a href="https://apostil.co/terms-of-use/">Terms of Use</a>
				<a href="https://apostil.co/privacy-policy/">Privacy Policy</a>
			</div> -->
		</div>
	</div>
	<div id="popup-pay" aria-hidden="true" class="popup">
		<div class="popup__wrapper">
			<div class="popup__content">
				<div class="popup__text" style="position:relative;">
					<h2 id='popup-pay-message'></h2>
					<a href="https://apostil.co/">Return</a>
				</div>
			</div>
		</div>
	</div>
<script>
  document.getElementById('zelle-pay').addEventListener('click', function () {
    this.classList.toggle('is-flipped');
  });
	document.getElementById('cash-pay').addEventListener('click', function () {
		this.classList.toggle('is-flipped');
	});
</script>
<script>
function openPopup(id, message = '') {
  const popup = document.getElementById(id);
  if (!popup) return;

  popup.classList.add('popup_show');
  popup.setAttribute('aria-hidden', 'false');

  const msg = popup.querySelector('#popup-pay-message');
  if (msg && message) {
    msg.textContent = message;
  }
}

const params = new URLSearchParams(window.location.search);
const orderNumber = params.get('order');


fetch(`/wp-admin/admin-ajax.php?action=get_order_for_payment&order=${encodeURIComponent(orderNumber)}`)
  .then(r => r.json())
  .then(r => {
    if (!r.success) {
	  document.body.style.visibility = 'hidden';
      openPopup('popup-pay', 'Order not found!');
      return;
    }

    document.getElementById('order-number').textContent = r.data.orderNumber;
    document.getElementById('customer-name').textContent = r.data.name;
    document.getElementById('order-total').textContent = `$${r.data.total}`;

	document.getElementById('wise-pay').href = "https://wise.com/pay/business/apostilinc?amount="+r.data.total+"&description=Pay%20order%20"+r.data.orderNumber;

	document.body.style.visibility = 'visible';

	fetch(`?stripe_pay=${r.data.total}`)
	.then(r)
	{
		document.getElementById('stripe-pay').href = r;
	}

	
	fetch(`?stripe_pay=${r.data.total}`, { method: 'GET' })
    .then(res => {
        if (!res.ok) {
            throw new Error('HTTP ' + res.status);
        }
        return res.text();
    })
    .then(url => {
        url = url.trim(); // важно

        const link = document.getElementById('stripe-pay');
        link.href = url;
    })
    .catch(err => {
        console.error('Ошибка fetch:', err);
    });

	// fetch(`?paypal_pay=${r.data.total}`)
	// .then(r)
	// {
	// 	document.getElementById('paypall-pay').href = r;
	// }
	
	// paypall
	fetch(`?paypal_pay=${r.data.total}`, { method: 'GET' })
    .then(res => {
        if (!res.ok) {
            throw new Error('HTTP ' + res.status);
        }
        return res.text();
    })
    .then(url => {
        url = url.trim(); // важно

        const link = document.getElementById('paypall-pay');
        link.href = url;
    })
    .catch(err => {
        console.error('Ошибка fetch:', err);
    });
	// visa
	fetch(`?paypal_pay=${r.data.total}`, { method: 'GET' })
		.then(res => {
		if (!res.ok) {
			throw new Error('HTTP ' + res.status);
		}
		return res.text();
	})
		.then(url => {
		url = url.trim(); // важно

		const link = document.getElementById('visa-pay');
		link.href = url;
	})
		.catch(err => {
		console.error('Ошибка fetch:', err);
	});
	
  })
  .catch(() => {
	document.body.style.visibility = 'hidden';
    openPopup('popup-pay', 'Network error. Please try again.');
  });
</script>

</body>

</html>