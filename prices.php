<?php
/*
Template Name: page-prices
*/
?>
<?php get_header(); ?>
 <style>
    :root {
      --brand:#0c6b41;
      --brand-600:#0a5b36;
      --muted:#f6f7f8;
      --text:#0f1a14;
      --border:#e6e8ea;
    }
    *{box-sizing:border-box}
    body {color:var(--text); margin:0;}
	 .prices-page__calculate {margin-bottom:40px}

    .eyebrow {display:inline-flex; align-items:center; gap:10px; color:var(--brand); font-weight:700; letter-spacing:.06em; text-transform:uppercase; font-size:12px;}
    .eyebrow::before, .eyebrow::after{content:""; width:24px; height:1.5px; background:var(--brand); opacity:.5}
    .intro {font-size:clamp(15px, 2.2vw, 18px); color:#2a2f2b; max-width:760px; margin:15px 0px;line-height:165%}

    .card {background:white; border-radius:16px; box-shadow: 0 2px 10px rgba(0,0,0,.04);}    
    .controls {display:grid; grid-template-columns:2fr 1fr 1fr; gap:15px; padding:3px 3px 3px 18px; background:#edf2ef; border-radius:14px;margin-bottom:17px}
    .control {display:flex; align-items:center; gap:10px}
	 .control.control-input {justify-content:center}
    .control label {font-weight:600;white-space:nowrap}
    .control input[type="number"],
    .control input[type="text"] {width:100%; max-width:160px; padding:6px 6px;}
	 .control .select {width:100%}
    table {width:100%; border-collapse:collapse}
	table tbody tr:nth-child(odd) {background: rgba(255, 255, 255, 0.29);}
    thead th {padding:10px 16px;}
    tbody td, tbody th {padding:6px 5px 6px 16px;}
    tbody th {text-align:left; font-weight:600;font-size:20px;font-family:Gilroy-Bold}
    td.tier {text-align:center}
    .muted {color:#000000; font-weight:600;display:block;font-family:Gilroy-Bold}
    .pill {font-family: Gilroy-Bold;display:inline-block; padding:6px 10px; border-radius:999px; background:#eef7f1; color:var(--brand); font-weight:700; font-size:12px}
	 th[scope="row"] {width:350px; max-width:350px}
    .footer-calc {display:grid; grid-template-columns: 1fr; gap:14px; padding:3px 3px 3px 18px; background:#EDF2EF; margin-top:17px;border-radius:14px}
	.footer-top {display:flex; flex-wrap:wrap; gap:16px; align-items:center;justify-content:space-between}
    .totals {display:flex; flex-wrap:wrap; gap:16px; align-items:center}
    .totals .line {min-width:150px;display:flex;align-items:center;column-gap:12px;row-gap:5px;font-family:Gilroy-Bold}
	 .totals .line b {font-weight: 400;font-size: 20px;line-height: 206%;color: #000;padding:5px 25px;border-radius: 14px;background:#ffffff;font-family:Gilroy-Medium}
    .btns {display:flex; gap:16px}
	.btn.secondary{min-width:200px}
	 .radio {position:relative; display:inline-flex; align-items:center; gap:8px; padding:0px 16px; border:1px solid var(--border); border-radius:10px; transition:.15s; cursor:pointer; user-select:none}
	 .radio input {position:absolute; inset:0; opacity:0;}
	 .radio:has(input:checked){border-color:var(--brand); box-shadow:0 0 0 3px rgba(12,107,65,.12)}
	 label.radio {width: 64px;height: 37px;background:#ffffff;border-radius:14px;position: relative; cursor: pointer}
	 label.radio input[type="radio"] { position: absolute;inset: 0;width: 100%; height: 100%; opacity: 0;cursor: pointer;z-index: 2;}
	 #taxLabel {font-size:16px}
	 #QuantityPages, #translationPages {width:36px}
	 .simplebar-wrapper {background: #edf2ef; border-radius:14px;padding-bottom:15px}
	 
	 @media (max-width: 992px) {
		 .controls {grid-template-columns:1fr 1fr;}	 
		 .controls .control-select {grid-area: 1 / 1 / 2 / 3; }
		 .control.control-input {justify-content:flex-start} 
		 .controls .control.control-input:last-of-type { justify-content:flex-end}
	 }

	 @media (max-width: 780px) {
		 .prices-page__calculate .card { overflow-x: auto; -webkit-overflow-scrolling: touch; }
		 .prices-page__calculate table {width: max-content;  min-width: 400px;   }
		 th[scope="row"] {width:300px; max-width:300px;font-family:Gilroy-Bold;font-weight:700}
		 .overflow {max-height:580px; }
		 tbody td, tbody th {border:none;padding-top:3px;padding-bottom:3px}
		 tbody th {font-size:14px;font-weight:700}
		 table tbody tr:nth-child(odd) {background: none;}
		 label.radio {width: 50px;height: 34px;font-size:16px}
		 .radio {padding:1px 10px 0px 10px}
		 .overflow .simplebar-track.simplebar-horizontal { border: 1px solid #004020;border-radius:20px; top:50px;}
		 .overflow .simplebar-scrollbar.simplebar-visible:before { background:#004020;opacity:1;}
		 .control.control-input {flex-direction:column; align-items:center; font-size:16px;font-family:Gilroy-Bold;font-weight:700}
		 .control.control-select label {font-family:Gilroy-Bold;font-weight:700}
		 .control.control-select {  grid-area: 1 / 1 / 2 / 3;  }
		 .card { border:none; }
		 .footer-calc {background: #edf2ef; border-radius:14px; margin-top:14px; padding:12px 16px}
		 .controls {margin-bottom:0;padding:16px 16px 10px 16px;border:none;background: #edf2ef;border-top-right-radius:14px; border-top-left-radius:14px;border-bottom-right-radius:0;border-bottom-left-radius:0}
		 .overflow.simplebar-scrollable-x {border:none}
		 .simplebar-wrapper {background: #edf2ef;border-bottom-right-radius:14px; border-bottom-left-radius:14px;border-top-right-radius:0; border-top-left-radius:0;padding-bottom:10px}
		 td.tier { width:60px}
		 .totals {flex-wrap:nowrap;gap:10px;width:100%;justify-content:space-between}
		 .totals .line {font-size: 16px;min-width:auto;flex-direction:column}
		 .totals .line b {font-size: 16px;padding:5px 25px;}
		 .btns {width:100%;}
		 .btns button {width:50%;padding:14px;font-size:20px;font-weight:500}
		 .btn.secondary{min-width:auto}
		 .pill {font-family:Gilroy-Medium}
		 th.h3 span.muted {font-size:14px;font-weight:400;font-family:Gilroy-Medium;}
		 thead th {background:none;border:none;padding:10px 5px 20px 5px}
		 thead th.h3 {font-size:16px;line-height:100%}
		 .h3.mobile-arrow {padding-left:16px}
	 }
	 
	 @media (max-width: 600px) {
		 .controls .control-select {
			 flex-direction:column;
			 align-items:flex-start;
		 }
		 th[scope="row"] {
			 width:150px;
			 max-width:150px;
		 }
	 }
	 
	 @media (max-width: 550px) {
		 .prices-page__calculate table {
			 width: max-content;
			 min-width:370px;
		 }
		 th[scope="row"] {
			 width:120px;
			 max-width:120px;
			 padding-right:0px;
			 padding-left:10px;
		 }
		 .tier,
		 .h3 {
			 padding-left:0px;
			 padding-right:0px;
		 }
		 .h3.mobile-arrow{
			 padding-left:10px
		 }
		 .second-prices-page__container {
			 margin-left:-15px !important;
			 margin-right:-15px !important;
		 }
	 }
	 @media (any-hover:hover) {
		 label.radio:hover {
			 border-color:var(--brand); 
			 box-shadow:0 0 0 3px rgba(12,107,65,.12);
		 }
		 input[type='radio'] {
			 cursor:pointer;	
		 }
	 }
  </style>

<main class="page prices-page">
	<section class="prices-page__hero hero-prices-page">
		<div class="hero-prices-page__container">
			<div class="hero-home-page__box">
				<h1 data-watch="" data-watch-threshold="0.5" data-watch-once="" class="title">Price Calculator</h1>
				<div class="hero-home-page__decor">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/home/hero-decor.webp" alt="decor">
				</div>
			</div>
			<p data-watch="" data-watch-threshold="0.5" data-watch-once="">We offer fair, transparent pricing for
				all of our services. No hidden fees, no surprises—just reliable service with fast turnaround.</p>
			<p data-watch="" data-watch-threshold="0.5" data-watch-once="">Choose your services with the calculator and check the final cost before you pay. Best price and fastest results - guaranteed.</p>
		</div>
	</section>
	<section class="prices-page__second second-prices-page">
		<div class="second-prices-page__container">
		<form id="calc-form">
		  <div class="prices-page__calculate">

			<div class="card" id="calc-card">
			  <div class="controls">
				<div class="control"  style='display:none'>
<!-- 				  <label for="tax">Sales tax, %</label>
				  <input id="tax" type="number" min="0" step="0.001" value="8.875" /> -->
				</div>
				  <div class="control control-select" id='DocumentTypePagesWrapper'>
					  <label for="documentTypes">Document type *</label>
					  <select data-checkbox multiple data-scroll data-required autocomplete="off" data-error="Error" name="document-type[]" data-class-modif="form" id="documentTypes"></select>
				  </div>
				  <div class="control control-input">
					  <label for="quantity">Quantity</label>
					  <input id="quantity" type="text" inputmode="numeric" pattern="[0-9]*">
				  </div>
				<div class="control control-input">
				  <label for="translation">Translate pages</label>
					<input id="translation" type="text" inputmode="numeric" pattern="[0-9]*">
				</div>
			  </div>
			  <div data-simplebar class='overflow'>
				<table id="priceTable" aria-describedby="calc-help">
				  <thead class='mobile-hide'>
					<tr>
					  <th class='h3 mobile-arrow' style='text-align:left'>Services</th>
					  <th class="h3">Standard<span class="muted">5–9 days</span></th>
					  <th class="h3">Express<span class="muted">1–2 days</span></th>
					  <th class='h3'>Urgent<span class="muted">3 hours</span></th>
					</tr>
				  </thead>
				  <tbody><tbody id="pricingBody"></tbody>
				</table>
			  </div>

			  <div class="footer-calc">
				  <div class="footer-top">  
					  <div class="totals">
						  <div class="line">Subtotal: <b>$<span id="subtotal">0.00</span></b></div>
						  <div class="line"><div style='white-space:nowrap'>Tax (<span id="taxLabel">8.875</span>%):</div> <b>$<span id="taxAmount">0.00</span></b></div>
						  <div class="line">Total: <b class="pill">$<span id="grand">0.00</span></b></div>
					  </div>
					  <div class="btns">
						  <button class="btn secondary button button-transparent" id="resetBtn" type="button" data-click_id="reset" data-click_text="Reset">Reset</button>
						  <button class="btn primary button button-fill" id="orderBtn" type="button" data-click_id="order_now" data-click_text="Order now">Order now</button>
					  </div>
				  </div>
			  </div>
			</div>
		  </div>
		</form>
		</div>
	</section>
	<section class="prices-page__additional additional-prices-page">
		<div class="additional-prices-page__container">
			<div class="additional-prices-page__left">
				<p>Discounts</p>
				<ul>
					<li> - Bulk pricing available for 5+ documents</li>
					<li> - Monthly plans for businesses</li>
				</ul>
			</div>
			<a href="https://wa.me/19297762142" class="additional-home-page__right">
				<div class="additional-home-page__img">
					<img src="https://apostil.co/wp-content/uploads/2025/11/operator-1-e1762242746888.webp" alt="bot">
				</div>
				<div class="additional-home-page__text">
					<p>Call us <span>+1 855 APOSTIL</span> or text <span>WhatsApp</span></p>
					<p>Get Apostille assistance in any language</p>
				</div>
			</a>
		</div>
		<p>To get a personalized quote, contact our team or use our instant estimate tool on the website.</p>
	</section>
	<section class="how-page__second second-how-page second-how-page-wdecor">
		<div class="second-how-page__container">
			<?php
			$team = CFS()->get('how-it-works_second_loop-prices');
			if ( $team ) :
			foreach ( $team as $member ) : ?>
			<div class="second-how-page__row second-how-page__row-reverce">
				<div class="second-how-page__img">
					<img src="<?php echo $member['how-it-works_second_image-1-prices']; ?>" alt="<?php echo $member['how-it-works_second_h2-1-prices']; ?>">
				</div>
				<div class="second-how-page__text">
					<h2 class="title" data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo $member['how-it-works_second_h2-1-prices']; ?></h2>
					<p data-watch="" data-watch-threshold="0.5" data-watch-once=""><?php echo $member['how-it-works_second_text-1-prices']; ?></p>
				</div>
			</div>
			<?php endforeach;
			endif;
			?>
		</div>
	</section>	
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selectWrapper = document.getElementById('DocumentTypePagesWrapper');

    function highlightPlaceholder() {
        var contents = selectWrapper.querySelectorAll('.select__content');
        contents.forEach(function(el) {
            if (el.textContent.trim() === 'Start by selecting the document') {
           el.style.fontWeight = '700';
            el.style.fontFamily = 'Gilroy-Bold';
            } else {
                el.style.color = '';
                el.style.fontStyle = '';
            }
        });
    }

    highlightPlaceholder();

    var observer = new MutationObserver(highlightPlaceholder);
    observer.observe(selectWrapper, { childList: true, subtree: true });
});
</script>
<script>
	(function() {
		// Універсальна відправка ecommerce (gtag -> fallback to dataLayer)
		function sendEcommerceEvent(eventName, payload) {
			if (typeof gtag === 'function') {
				try {
					gtag('event', eventName, payload);
					console.log('gtag sent', eventName, payload);
					return;
				} catch (e) {
					console.warn('gtag call failed:', e);
				}
			}

			// Fallback для GTM
			if (window.dataLayer && Array.isArray(window.dataLayer)) {
				window.dataLayer.push({
					event: eventName,
					ecommerce: {
						currency: payload.currency || 'USD',
						value: payload.value || 0,
						items: payload.items || []
					}
				});
				console.log ('dataLayer pushed', eventName, payload);
				return;
			}

			console.warn('No gtag or dataLayer available. Event not sent:', eventName, payload);
		}

		// Збираємо обрані опції
		function getSelectedItems() {
			const items = [];
			document.querySelectorAll('#calc-form input[type="radio"]:checked').forEach(radio => {
				items.push({
					item_id: radio.dataset.click_id || 'unknown_id',
					item_name: radio.dataset.click_text || radio.value || 'unknown_name',
					affiliation: 'calculator',
					price: parseFloat(radio.dataset.price) || 0,
					quantity: 1
				});
			});
			return items;
		}

		function calcTotal(items) {
			return items.reduce((s, it) => s + (Number(it.price) || 0) * (Number(it.quantity) || 1), 0);
		}

		document.addEventListener('DOMContentLoaded', function() {
			const form = document.querySelector('#calc-form');
			if (!form) {
				console.warn('#calc-form not found — listeners not attached');
				return;
			}

			form.addEventListener('change', function(e) {
				const t = e.target;
				if (t && t.matches('input[type="radio"]')) {
					const items = getSelectedItems();
					const value = calcTotal(items);

					sendEcommerceEvent('add_to_cart', {
						currency: 'USD',
						value: value,
						items: items
					});
				}
			});

			// Order now
			const orderBtn = document.getElementById('orderBtn');
			if (orderBtn) {
				orderBtn.addEventListener('click', function() {
					const items = getSelectedItems();
					const value = calcTotal(items);
					sendEcommerceEvent('begin_checkout', {
						currency: 'USD',
						value: value,
						items: items
					});
				});
			} else {
				console.warn('#orderBtn not found');
			}

			// Reset
			const resetBtn = document.getElementById('resetBtn');
			if (resetBtn) {
				resetBtn.addEventListener('click', function() {
					const items = getSelectedItems(); 
					const value = calcTotal(items);
					sendEcommerceEvent('remove_from_cart', {
						currency: 'USD',
						value: value,
						items: items
					});

					// Скидаємо форму
					form.reset();
				});
			} else {
				console.warn('#resetBtn not found');
			}
		});
	})();
</script>


<script>
// Вибір Select залежно від значення chekbox на головній сторінці
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    let documentsParam = params.get('documents');

    if (!documentsParam) return;

    documentsParam = decodeURIComponent(documentsParam.replace(/\+/g, ' '));
    const selectedValues = documentsParam.split(',').map(v => v.trim());

    console.log('FROM URL:', selectedValues);

    const interval = setInterval(() => {

        const select = document.querySelector('.select[data-id="1"]');
        if (!select) return;

        const optionButtons = select.querySelectorAll('.select__option');
        if (!optionButtons.length) return;

        clearInterval(interval);
        console.log('FOUND OPTIONS:', optionButtons.length);

        selectedValues.forEach(value => {

            const targetOption = Array.from(optionButtons).find(btn => 
                btn.dataset.value.trim().toLowerCase() === value.toLowerCase()
            );

//             console.log('TRY SELECT:', value, !!targetOption);

            if (targetOption && !targetOption.classList.contains('_select-selected')) {
                targetOption.click();
            }
        });

        const selectTitle = select.querySelector('.select__title');
        if (select.classList.contains('_select-open') && selectTitle) {
            selectTitle.click();
        }

    }, 300);
});
</script>

<?php get_footer(); ?>