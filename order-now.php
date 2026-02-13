<?php

/*

Template Name: page-order-now

*/

?>

<?php get_header(); ?>

<style>
	.loader {
		width: 24px;
		height: 24px;
		border: 2px solid #FFF;
		border-bottom-color: transparent;
		border-radius: 50%;
		display: inline-block;
		box-sizing: border-box;
		animation: rotation 1s linear infinite;
		}

		@keyframes rotation {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
    } 
</style>


<main class="page order-page">
	<section class="order-page__second second-order-page hero-about-page">

		<div class="second-order-page__container">

			<div class="hero-home-page__box">

				<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" class="title">Order Form for Notarization, Translation and Apostille</h1>

				<div class="hero-home-page__decor">

					<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">

				</div>

			</div>
			<form action="/wp-json/orders/v1/create" data-ajax  class="form" method="POST">
				<input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">

				<input type="hidden" name="form_subject" value="Тема листа">

				<div class="form__box">

					<h3>Step 1. Information about your documents</h3>

					<div class="form__content">

						<div class="form__left">

							<label class="form-label">
								<span>Country where the documents are going to be used? *</span>
								<select data-scroll data-required autocomplete="off" data-error="Error" name="country" data-class-modif="form" id='destinationCountry'></select>
							</label>

							<label class="form-label form-label-document">

								<span>Document type *</span>
								<select data-checkbox multiple data-scroll data-required autocomplete="off" data-error="Error" name="document-type[]" data-class-modif="form" id="documentTypes"></select>
							</label>
							
							<div class="form-label">
								<div class="form-label-row form-label-row-gap form-label-flex-row">
									<div>
										<input id="quantity" name="quantity" type="text" inputmode="numeric" pattern="[0-9]*" min="0" value='0' class="input">
										<b style='margin:0;text-align:center;'>Quantity</b>
									</div>
									<div>
										<input id="translation" name="translation" type="text" inputmode="numeric" pattern="[0-9]*" min="0" value='0' class="input">
										<b style='margin:0;text-align:center;'>Translate pages</b>
									</div>																	
								</div>
							</div>
																			
							<label class="form-label">

								<div class="form-label-row form-label-row-gap">
									<select data-scroll name="translate-from" data-class-modif="form" id='translationLanguagesFrom'></select>
									
									<select data-scroll name="translate-to" data-class-modif="form" id='translationLanguagesTo'></select>
								</div>

							</label>
							
							<label class="form-label" style='margin:0'>
								<b style='margin:0;font-weight:600'>Check quantity of documents and number of pages for translation</b>
							</label>

						</div>

						<div class="form__right">

							<label class="form-label form-label-document">
								<span>Service type *</span>
								<select multiple data-scroll data-required autocomplete="off" data-error="Error" name="service-type[]" data-class-modif="form" id='serviceTypes'></select>
							</label>

							<label class="form-label">
								<span>Processing and Authentication *</span>
								<select data-required autocomplete="off" data-error="Error" name="processing-type"  data-class-modif="form" id='processingAuthentication'></select>
							</label>
							<div class="form-label" style='margin:0'>
								<div class="form-upload-area _icon-upload" id="uploadArea">
									<div class="load-file">
										<p id="uploadMessage" style='margin:0'>Drag & drop files or </p>
										<label for="fileInput" class="browse-button">Browse</label>
									</div>
									<input type="file" id="fileInput" name="userfile[]" multiple hidden>
									<div id="fileList"></div>
									<span>Supported formates: JPEG, PNG, PDF, DOC, DOCX</span>
								</div>
							</div>

						</div>

					</div>

				</div>

								<div class="form__box">

					<div class="form__content">

						<div class="form__left">

							<h3>Step 2: Information about you and return shipping</h3>

							<div class="form-label-row">

								<label class="form-label">

									<input type="text" data-required name="delivery-name" id="delivery-name" autocomplete="off" maxlength="255" data-error="Error" placeholder="First Name *" class="input">

								</label>

								<label class="form-label">

									<input type="text" data-required name="delivery-soname" id="delivery-soname" autocomplete="off" maxlength="255" data-error="Error" placeholder="Last Name *" class="input">

								</label>

							</div>

							<div class="form-label-row">

								<label class="form-label">

									<input type="text" name="delivery-company" id="delivery-company" autocomplete="off" maxlength="255" placeholder="Company Name " class="input">

								</label>

							</div>

							<div class="form-label-row">
								<label class="form-label"> 
									<select data-scroll data-required autocomplete="off" data-error="Error" name="delivery-country" data-class-modif="form" id='countryFrom'></select>
								</label>

								<label class="form-label">

									<input type="text" data-required name="delivery-adress" id="delivery-adress" autocomplete="off" maxlength="255" data-error="Error" placeholder="Address *" class="input">

								</label>

							</div>

							<div class="form-label-row">

								<label class="form-label">

									<input type="text" data-required name="delivery-city" id="delivery-city" autocomplete="off" maxlength="255" data-error="Error" placeholder="City *" class="input">

								</label>
								<div class='form-state-zip'>
									
									<label class="form-label">
										<select data-scroll  autocomplete="off"  name="delivery-state" data-class-modif="form" id='usStatesFrom'></select>

									</label>
									<label class="form-label">
										<input type="text" data-required name="delivery-zip" id="delivery-zip" autocomplete="off" maxlength="255" data-error="Error" placeholder="Zip Code *" class="input">
									</label>
								</div>
							</div>

							<div class="form-label-row">

								<label class="form-label">

									<input type="tel" data-required name="delivery-tel" id="delivery-tel" autocomplete="off" maxlength="255" data-error="Error" placeholder="Phone Number *" class="input">

								</label>

								<label class="form-label">

									<input type="email" data-required name="delivery-email" id="delivery-email" autocomplete="off" maxlength="255" data-error="Error" placeholder="Email *" class="input">

								</label>

							</div>

						</div>

						<div class="form__right">

							<div class="form__top">

								<p>Is the return shipping address different from the provided?</p>

								<div class="options">

									<div class="options__item">

										<input id="o_1" class="options__input" type="radio" value="No" name="option">

										<label for="o_1" class="options__label"><span class="options__text">No</span></label>

									</div>

									<div class="options__item">

										<input id="o_2" class="options__input" checked type="radio" value="Yes" name="option">

										<label for="o_2" class="options__label"><span class="options__text">Yes</span></label>

									</div>

								</div>

							</div>

							<div class="form-label-row">

								<label class="form-label">

									<input type="text" data-required name="return-name" id="return-name" autocomplete="off" maxlength="255" data-error="Error" placeholder="First Name *" class="input">

								</label>

								<label class="form-label">

									<input type="text" data-required name="return-soname" id="return-soname" autocomplete="off" maxlength="255" data-error="Error" placeholder="Last Name *" class="input">

								</label>

							</div>

							<div class="form-label-row">

								<label class="form-label">

									<input type="text" name="return-company" id="return-company" autocomplete="off" maxlength="255" placeholder="Company Name " class="input">

								</label>

							</div>

							<div class="form-label-row">
								<label class="form-label">
									<select data-scroll data-required autocomplete="off" data-error="Error" name="return-country" data-class-modif="form" id='countryTo'></select>
								</label>

								<label class="form-label">

									<input type="text" data-required name="return-adress" id="return-adress" autocomplete="off" maxlength="255" data-error="Error" placeholder="Address *" class="input">

								</label>

							</div>

							<div class="form-label-row">
								
								<label class="form-label">

									<input type="text" data-required name="return-city" id="return-city" autocomplete="off" maxlength="255" data-error="Error" placeholder="City *" class="input">

								</label>

								<div class='form-state-zip'>
									
									<label class="form-label">
										<select data-scroll  autocomplete="off"  name="return-state" data-class-modif="form" id='usStatesTo'></select>
									</label>
									<label class="form-label">
										<input type="text" data-required name="return-zip" id="return-zip" autocomplete="off" maxlength="255" data-error="Error" placeholder="Zip Code *" class="input">
									</label>
								</div>

							</div>

							<div class="form-label-row">
								
								<label class="form-label">
									<input type="tel" data-required name="return-tel" id="return-tel" autocomplete="off" maxlength="255" data-error="Error" placeholder="Phone Number *" class="input">
								</label>

								<label class="form-label delivery-wrapper"> 
									<select data-scroll name="delivery" data-required data-error="Error" data-class-modif="form" id='deliveryMethod'></select>
								</label>

							</div>

						</div>

					</div>

					<button type="submit" class="button button-fill">Place an order</button>
					<p class="form-success-message" style="display:none; color: green; margin-top: 30px;text-align:center;">
						Thank you! Your order has been successfully submitted.
					</p>
				</div>

				<input type="hidden" name="order-total" id="order-total-input">
				<input type="hidden" name="paypal_link" id="paypal_link">

			</form>

			<div class="order form__box">
				<h2>Order <span id="order-number"></span></h2>
				<input type="hidden" name="order-number" id="order-number-input" value="">
				
				<div class="order__top">

					<div class="order__data">

						<h3>Item</h3>

						<h3>Cost</h3>

					</div>

					<span class="_icon-trash"></span>

				</div>

				<div class="order__list">					
					<div class="order-group" data-group="notarization"></div>
					<div class="order-group" data-group="translation"></div>
					<div class="order-group" data-group="apostille"></div>
					<div class="order-group" data-group="record"></div>
					<div class="order-group" data-group="delivery"></div>
				</div>

				<div class="order__bottom">

					<h3>Subtotal </h3>

					<h3 id="subtotal">$0.00</h3>

				</div>

				<div class="order__bottom">

					<h3>NY State Tax (<span id="taxLabel">8.875</span>%)</h3>

					<h3 id="taxAmount">$0.00</h3>

				</div>

				<div class="order__bottom">

					<h3>Total </h3>

					<h3 id="grand">$0.00</h3>

				</div>

				<div class="order__agree">

					<div class="checkbox agree">

						<input id="c_11" data-error="Ошибка" class="checkbox__input" type="checkbox" value="1" name="agree">

						<label for="c_11" class="checkbox__label"><span class="checkbox__text">I agree to

								the</span></label>

						<a href="https://apostil.co/terms-of-use/">Terms and

							Conditions</a>

					</div>

				</div>

<style>
	#paypal-button-container{
		width:100%;
		max-width: 350px;
		margin: 0 auto;
	}
</style>

				<script src="https://www.paypal.com/sdk/js?client-id=AQK6hmUZQO-efOJqKN9MDpzoPa_3dHtPuBHn--L2Lx5JDuUT3i29ktIr2UyAlXznyzXCs_iyzDKlU0PQ&currency=USD"></script>

				<div id="paypal-button-container"></div>

				<a style="margin-bottom:0px;width:100%;max-width: 350px;" id="stripePayLink" href="#" target="_blank">
					<button style="background: #2c2e2f;color: #ffffff;height: 45px;width:100%;padding:0px 10px;margin: 10px auto;font-family: sans-serif;display: flex;align-items: center;justify-content: center;border-radius: 4px;">					
					<svg width="32px" height="32px" viewBox="0 -149 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" style="margin-right: 12px;">
						<g>
							<path d="M35.9822222,83.4844444 C35.9822222,77.9377778 40.5333333,75.8044444 48.0711111,75.8044444 C58.88,75.8044444 72.5333333,79.0755556 83.3422222,84.9066667 L83.3422222,51.4844444 C71.5377778,46.7911111 59.8755556,44.9422222 48.0711111,44.9422222 C19.2,44.9422222 0,60.0177778 0,85.1911111 C0,124.444444 54.0444444,118.186667 54.0444444,135.111111 C54.0444444,141.653333 48.3555556,143.786667 40.3911111,143.786667 C28.5866667,143.786667 13.5111111,138.951111 1.56444444,132.408889 L1.56444444,166.257778 C14.7911111,171.946667 28.16,174.364444 40.3911111,174.364444 C69.9733333,174.364444 90.3111111,159.715556 90.3111111,134.257778 C90.1688889,91.8755556 35.9822222,99.4133333 35.9822222,83.4844444 Z M132.124444,16.4977778 L97.4222222,23.8933333 L97.28,137.813333 C97.28,158.862222 113.066667,174.364444 134.115556,174.364444 C145.777778,174.364444 154.311111,172.231111 159.004444,169.671111 L159.004444,140.8 C154.453333,142.648889 131.982222,149.191111 131.982222,128.142222 L131.982222,77.6533333 L159.004444,77.6533333 L159.004444,47.36 L131.982222,47.36 L132.124444,16.4977778 Z M203.235556,57.8844444 L200.96,47.36 L170.24,47.36 L170.24,171.804444 L205.795556,171.804444 L205.795556,87.4666667 C214.186667,76.5155556 228.408889,78.5066667 232.817778,80.0711111 L232.817778,47.36 C228.266667,45.6533333 211.626667,42.5244444 203.235556,57.8844444 Z M241.493333,47.36 L277.191111,47.36 L277.191111,171.804444 L241.493333,171.804444 L241.493333,47.36 Z M241.493333,36.5511111 L277.191111,28.8711111 L277.191111,0 L241.493333,7.53777778 L241.493333,36.5511111 Z M351.431111,44.9422222 C337.493333,44.9422222 328.533333,51.4844444 323.555556,56.0355556 L321.706667,47.2177778 L290.417778,47.2177778 L290.417778,213.048889 L325.973333,205.511111 L326.115556,165.262222 C331.235556,168.96 338.773333,174.222222 351.288889,174.222222 C376.746667,174.222222 399.928889,153.742222 399.928889,108.657778 C399.786667,67.4133333 376.32,44.9422222 351.431111,44.9422222 Z M342.897778,142.933333 C334.506667,142.933333 329.528889,139.946667 326.115556,136.248889 L325.973333,83.4844444 C329.671111,79.36 334.791111,76.5155556 342.897778,76.5155556 C355.84,76.5155556 364.8,91.0222222 364.8,109.653333 C364.8,128.711111 355.982222,142.933333 342.897778,142.933333 Z M512,110.08 C512,73.6711111 494.364444,44.9422222 460.657778,44.9422222 C426.808889,44.9422222 406.328889,73.6711111 406.328889,109.795556 C406.328889,152.604444 430.506667,174.222222 465.208889,174.222222 C482.133333,174.222222 494.933333,170.382222 504.604444,164.977778 L504.604444,136.533333 C494.933333,141.368889 483.84,144.355556 469.76,144.355556 C455.964444,144.355556 443.733333,139.52 442.168889,122.737778 L511.715556,122.737778 C511.715556,120.888889 512,113.493333 512,110.08 Z M441.742222,96.5688889 C441.742222,80.4977778 451.555556,73.8133333 460.515556,73.8133333 C469.191111,73.8133333 478.435556,80.4977778 478.435556,96.5688889 L441.742222,96.5688889 L441.742222,96.5688889 Z" fill="#FFFFFF">

					</path>
						</g>
					</svg>
						Pay by Stripe
					</button>
				</a>
					
				<a style="margin-bottom:10px;width:100%;max-width: 350px;" id="wisePayLink" href="#" target="_blank">
					<button style="background: #2c2e2f;color: #ffffff;height: 45px;width:100%;padding:0px 10px;margin: 0px auto;font-family: sans-serif;border-radius: 4px;">Pay by Wise</button>
				</a>
				
				<a id="zellePayLink" style='width:100%;max-width: 350px;' href="#" target="_blank">
					<button style="background: #2c2e2f;color: #ffffff;height: 45px;width:100%;padding:0px 10px;margin: 0px auto;font-family: sans-serif;border-radius: 4px;">Pay by Zelle</button>
				</a>

				<p>* Please note that prices are subject to change depending on each case. Processing Times noted

					above are estimates only and include the time required for the government agencies issuing the

					Apostille.</p>

			</div>

		</div>
<script>
	if (!window.APP_KEY) {
	  console.warn('No APP_KEY set, skipping order number fetch');
	} else {
	  fetch('/wp-json/orders/v1/create', {
		method: 'POST',
		headers: {
		  'Content-Type': 'application/json',
		  'X-App-Key': window.APP_KEY
		},
		body: JSON.stringify({})
	  })
		.then(r => r.json())
		.then(r => {
		  if (!r.success) return;
		  document.getElementById('order-number').textContent = r.orderNumber;
		  document.getElementById('order-number-input').value = r.orderNumber;
		})
		.catch(err => console.error(err));
	}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tels = document.querySelectorAll('input[type="tel"]');

    tels.forEach(input => {

        // Якщо поле порожнє - відразу ставимо "+"
//         input.addEventListener('focus', () => {
//             if (input.value.trim() === '') {
//                 input.value = '+';
//             }
//         });

        input.addEventListener('input', () => {
            let val = input.value;

            // Видаляємо все, крім цифр і +
            val = val.replace(/[^\d+]/g, '');

            // Заллишаємо тільки один "+" і только на початку
//             if (!val.startsWith('+')) {
//                 val = '+' + val.replace(/\+/g, '');
//             } else {
//                 val = '+' + val.slice(1).replace(/\+/g, '');
//             }

            input.value = val;
        });

        // Забороняємо видаляти "+"
//         input.addEventListener('keydown', (e) => {
//             if (input.selectionStart === 1 && input.value === '+' && e.key === 'Backspace') {
//                 e.preventDefault();
//             }
//         });
    });
});
</script>	

	<script>

		paypal.Buttons({
			style: {
				color: 'black'
			},
			createOrder: function(data, actions) {

				var rawAmount = document.getElementById('order-total-input').value;
				var amount = parseFloat(rawAmount.replace('$', '').trim()).toFixed(2);
				
				var transaction_id = document.getElementById('order-number').innerText;
				var orderNumberInput = document.getElementById('order-number').innerText.trim();
				
				localStorage.setItem('transaction_id', transaction_id);
				localStorage.setItem('amount', amount);

				var agree = document.getElementById('c_11').checked;

				let items = document.querySelectorAll(".order__data");

				let itemsArray = [];

				items.forEach((item) => {
					if (items[0] === item) return;

					let nameElement = item.querySelector("p");
					let priceElement = item.querySelector("h3");

					if (!nameElement || !priceElement) return;

					let rawPrice = priceElement.textContent?.replace("$", "").trim();

					// Пропускаємо PAID і FREE
					if (["PAID", "FREE"].includes(rawPrice)) {
					console.warn("Пропущено товар с неправильною ціною", nameElement.textContent, rawPrice);
					return;
					}

					let price = parseFloat(rawPrice);
					if (isNaN(price)) {
						console.warn( "Ціна не є числом:", nameElement.textContent, rawPrice);
						return;
					}
					
					let rawName = nameElement.innerHTML
						.replace(/<br\s*\/?>/gi, "\n") 
						.replace(/<\/?[^>]+(>|$)/g, "");

					let itemObject = {
						item_name: rawName,
						price: price,
						quantity: 1
					};

					itemsArray.push(itemObject);
				});

				
				localStorage.setItem('items', JSON.stringify(itemsArray));
					if (!agree) {
						alert('Please agree to the terms and conditions');
						return Promise.reject('Terms not accepted');
					}
					if (!orderNumberInput) {
						alert(  "1. Complete the order information first.\n" +
							    "2. Press \"Place an Order\" button to confirm.\n" +
							    "3. Then you are able to proceed with payment.");
						return Promise.reject('Order number is missing');
					}

					window.dataLayer.push({
						event: "add_payment_info",
						ecommerce: { 
							transaction_id: transaction_id,
							currency: "USD",
							value: amount,
							payment_type: "PayPal",
							items: itemsArray
						}
					});

					var rawtotal = itemsArray.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
					let total = parseFloat(rawtotal.replace('$', '').trim()).toFixed(2);

					// Переводимо масив в формат, який розуміє PayPal
					var paypalItems = itemsArray.map(item => ({
					name: item.item_name,
					unit_amount: {
						currency_code: "USD",
						value: item.price.toFixed(2)
					},
						quantity: item.quantity.toString()
					}));

					let rawtaxValue = document.getElementById('taxAmount').innerText.replace("$", "").trim();
					let taxValue = parseFloat(rawtaxValue.replace('$', '').trim()).toFixed(2);
		
					return actions.order.create({
						purchase_units: [{
							amount: {
							currency_code: "USD",
							value: amount,
							breakdown: {
								item_total: {
									currency_code: "USD",
									value: total
								},
								tax_total: {
									currency_code: "USD",
									value: taxValue // "2.60"
								}
							}
							},
							items: paypalItems
						}],
						application_context: {
							shipping_preference: "NO_SHIPPING"
						}
					});
			},
			onApprove: function(data, actions) {
				return actions.order.capture().then(function(details) {

					let itemsLocal = JSON.parse(localStorage.getItem('items')) || [];

					window.dataLayer.push({
						event: "purchase",
						ecommerce: { 
							transaction_id: localStorage.getItem('transaction_id'),
							currency: "USD",
							value: localStorage.getItem('amount'),
							items: itemsLocal
						}
					});

					alert('Payment completed, thank you, ' + details.payer.name.given_name);
				});
			}
		}).render('#paypal-button-container');
	</script>


	</section>

</main>

<?php get_footer(); ?>