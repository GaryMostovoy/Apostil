// API
fetch('/wp-admin/admin-ajax.php?action=get_prices_items')
  .then(r => r.json())
  .then(r => {
    if (!r.success) throw 'Prices error';

    const normalized = normalizeItems(r.data);

    renderServices(normalized.services);
    renderTranslation(normalized.translation);
    renderShipping(normalized.shipping);
  });
	
// helpers
function getCheckedValues(name) {
  return [...document.querySelectorAll(`[name="${name}"]:checked`)]
    .map(el => el.value);
}

function collectPriceSelection() {
  const radios = document.querySelectorAll(
    'input[type="radio"][data-processing]:checked'
  );

  const map = {};

  radios.forEach(radio => {
    const item = radio.dataset.item;
    const processing = radio.dataset.processing;

    // одне значення processing на один item — завжди останній стан
    map[item] = processing;
  });

  return Object.entries(map).map(([item, processing]) => ({
    item,
    processing
  }));
}

// JS-рендер таблиці
function normalizeItems(items) {
  return {
    services: items.filter(i => i.group === 'services'),
    shipping: items.filter(i => i.group === 'shipping'),
    translation: items.find(i => i.id === 'translation')
  };
}
	
function renderServices(items) {

  const tbody = document.getElementById('pricingBody');

  items.forEach(item => {
    const tr = document.createElement('tr');

    tr.innerHTML = `
      <th scope="row">${item.text}</th>
      ${item.prices.map(p => `
        <td class="tier">
          <label class="radio">
            <input type="radio"
                   name="${item.id}"
                   data-item="${item.id}"
                   data-processing="${p.id}">
            <span>$${p.value}</span>
          </label>
        </td>
      `).join('')}
    `;

    tbody.appendChild(tr);
  });
}

function renderTranslation(item) {

  if (!item) return;

  const tbody = document.getElementById('pricingBody');
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <th scope="row">${item.text}</th>
    ${item.prices.map(p => `
      <td class="tier">
        <label class="radio">
          <input type="radio"
                 name="translation"
                 data-item="translation"
                 data-processing="${p.id}">
          <span>$${p.value}</span>
        </label>
      </td>
    `).join('')}
  `;

  tbody.appendChild(tr);
}

function renderShipping(items) {

  const tbody = document.getElementById('pricingBody');

  items.forEach(item => {
    const tr = document.createElement('tr');

    tr.innerHTML = `
      <th scope="row">${item.text}</th>
      ${item.prices.map(p => `
        <td class="tier">
          <label class="radio">
            <input type="radio"
                   name="${item.radioGroup}"
                   data-item="${item.id}"
                   data-processing="${p.id}">
            <span>$${p.value}</span>
          </label>
        </td>
      `).join('')}
    `;

    tbody.appendChild(tr);
  });
}

// Глобальна змінна
let lastProcessingSelection = null;

document.addEventListener('change', e => {
    if (e.target.matches('[data-processing]')) {
	lastProcessingSelection = {
      item: e.target.dataset.item,
      processing: e.target.dataset.processing
    };

//     console.log('%c[PROCESSING CLICK]','color: purple; font-weight: bold;', lastProcessingSelection);
	  
	applyLastProcessingOnFront(lastProcessingSelection);
  }

  if (
    e.target.matches('input[type="radio"]') ||
    e.target.matches('input[type="checkbox"]') ||
    e.target.matches('select')
  ) {
    calculate();
  }
	
	// Звичайні select
	if (e.target.matches('select')) {
		calculate();
	}
});

// Вибір всіх radio по останньому обраному
function applyLastProcessingOnFront(lastProcessing) {
  if (!lastProcessing || !lastProcessing.processing) return;

  const radios = document.querySelectorAll(
    'input[type="radio"][data-processing]'
  );

  // Групуємо по data-item
  const groups = {};

  radios.forEach(radio => {
    const key = radio.dataset.item;
    if (!groups[key]) {
      groups[key] = [];
    }
    groups[key].push(radio);
  });

  Object.values(groups).forEach(group => {
    // якщо є обраний в цій групі
    const checkedRadio = group.find(r => r.checked);
    if (!checkedRadio) return;

    // знімаємо поточний
    checkedRadio.checked = false;

    // шукаємо потрібний processing В цій де delivery / послузі
    const target = group.find(
      r => r.dataset.processing === lastProcessing.processing
    );

    if (target) {
      target.checked = true;
    }
  });
}

window.addEventListener('DOMContentLoaded', () => {
	
  const resetBtn = document.getElementById('resetBtn');
	
	const docBlock = $('#DocumentTypePagesWrapper');
	if (!resetBtn || !docBlock) return;
	const initialHTML = docBlock.innerHTML;
	
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
	  docBlock.innerHTML = initialHTML;
		
      document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
      document.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
		
		$$('input[name="document-type[]"]').forEach(cb => (cb.checked = false));
				
		
		// скидаємо select и заново завантажуємо через АРІ
		const docSelectWrapper = $('#DocumentTypePagesWrapper');
		if (docSelectWrapper) {
			const docSelect = document.getElementById('documentTypes');
			if (docSelect) {
				// очищаємо старі опції
				docSelect.innerHTML = '';
			}

			fetch('/wp-admin/admin-ajax.php?action=get_calculator_config')
				.then(res => res.json())
				.then(res => {
				if (!res.success) throw new Error('API error');
				const data = res.data;

				// заново рендеримо опції
				renderSelect('documentTypes', data.documentTypes, 'Start by selecting the document');

				// ініціалізуємо кастомный select через існуючий SelectConstructor
				if (window.modules_flsModules?.select) {
					const selectInstance = window.modules_flsModules.select;
					selectInstance.selectInit(docSelect, 1); 
					}

					// скидаємо quantity і translation 
					const qty = document.getElementById('quantity');
					if (qty) {
					qty.value = 0;
					qty.dispatchEvent(new Event('input', { bubbles: true }));
			}

					  const translation = document.getElementById('translation');
			if (translation) {
				translation.value = 0;
				translation.dispatchEvent(new Event('input', { bubbles: true }));
			}

			// оновлюємо розрахунки
			updateTotals({
				subtotal: 0,
				tax: 0,
				taxRate: document.getElementById('taxLabel').textContent,
				total: 0
			});
		})
			.catch(err => console.error(err));

	}

   });
  }
});


// Виклик калькулятора
function calculate() {

  const priceSelection = collectPriceSelection();

  // ЛОГ ОСТАННЬОГО ОБРАНОГО processing
  const lastProcessing = priceSelection.length
    ? priceSelection[priceSelection.length - 1]
    : null;

//   console.log('%c[CALCULATE] last processing →','color: red; font-weight: bold;', lastProcessing);

  const payload = {
    quantity: +document.getElementById('quantity').value || 0,
    documentTypes: getCheckedValues('documentTypes'),
    translation: +document.getElementById('translation').value || 0,
	priceSelection: collectPriceSelection(),
  };

//   console.log('%c[CALCULATE] payload →','color: blue; font-weight: bold;', JSON.parse(JSON.stringify(payload)) );

  fetch('/wp-admin/admin-ajax.php?action=calculate_price', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
    .then(r => r.json())
    .then(r => {
//       console.log( '%c[CALCULATE] response →', 'color: green; font-weight: bold;', r);

      if (r.success && r.data) {
        window.lastCalculationResponse = r.data;
		applyLastProcessingOnFront(r.data.lastProcessing);
      }

      if (!r.success || !r.data || !r.data.summary) {
        updateTotals({
          subtotal: 0,
          tax: 0,
          taxRate: document.getElementById('taxLabel').textContent,
          total: 0
        });
        return;
      }

      updateTotals(r.data.summary);
    });
}

// debounce, щоб АРІ не викликалося занадто часто
let calcTimer;

function triggerCalc() {
  clearTimeout(calcTimer);
  calcTimer = setTimeout(() => {

    const translationInput = document.getElementById('translation');
    const hasTranslationRadio = document.querySelector(
      'input[type="radio"][name="translation"]:checked'
    );

    const isTranslationRequired = translationInput && !hasTranslationRadio;

    //  quantity завжди тригерить calculate
    if (typeof calculate === 'function') {
      calculate();
    }
  }, 150);
}

// Виведення результатів
function updateTotals(summary) {
 document.getElementById('subtotal').textContent = summary.subtotal.toFixed(2);
 document.getElementById('taxAmount').textContent = summary.tax.toFixed(2);
 document.getElementById('taxLabel').textContent = summary.taxRate;
 document.getElementById('grand').textContent = summary.total.toFixed(2);
}




// ------------------------------------------------------------------------------- КОД ----------------------------------------------------------------------- //
const $ = (q, ctx = document) => ctx.querySelector(q);
const $$ = (q, ctx = document) => Array.from(ctx.querySelectorAll(q));

let docSelect = $('#documentTypes');
let qtyInput = $('#quantity');
let translationInput = $('#translation');
const resetBtn = $('#resetBtn');
const orderBtn = $('#orderBtn');

if (docSelect) {
	observeCustomSelect();  // створюємо observer один раз
}

// === Отримуємо обрані документи ===
function getSelectedDocs() {
	const boxes = $$('input[name="document-type[]"]');
	if (boxes.length) {
		const checked = boxes
			.filter(b => b.checked && b.value && b.value.trim())
			.map(b => b.value.trim());
		if (checked.length) return checked;
	}

	if (docSelect) {
		const opts = Array.from(docSelect.options || [])
			.filter(o => o.selected && o.value && o.value.trim())
			.map(o => o.value.trim());
		return opts;
	}

	return [];
}

// === Оновлюємо поле quantity ===
function updateQuantityFromSelect() {
	const sel = getSelectedDocs();
	const minQty = sel.length || 0;
	qtyInput = $('#quantity');
	qtyInput.value = qtyInput.value || 0;
	if (!qtyInput) return;
	
	let current = parseInt(qtyInput.value) || 0;

	qtyInput.setAttribute('inputmode', 'numeric');
	qtyInput.setAttribute('pattern', '[0-9]*');
	qtyInput.setAttribute('type', 'text');
	qtyInput.removeAttribute('readonly');

	if (!qtyInput.parentElement.classList.contains('qty-wrapper')) {
		const wrapper = document.createElement('div');
		wrapper.className = 'qty-wrapper';
		qtyInput.parentElement.insertBefore(wrapper, qtyInput);
		wrapper.appendChild(qtyInput);

		const minusBtn = document.createElement('button');
		minusBtn.type = 'button';
		minusBtn.textContent = '–';
		minusBtn.className = 'qty-btn minus';
		const plusBtn = document.createElement('button');
		plusBtn.type = 'button';
		plusBtn.textContent = '+';
		plusBtn.className = 'qty-btn plus';

		wrapper.insertBefore(minusBtn, qtyInput);
		wrapper.appendChild(plusBtn);
		
		qtyInput.addEventListener('input', triggerCalc);
		
		plusBtn.addEventListener('click', () => {
			let val = parseInt(qtyInput.value) || 0;
			qtyInput.value = val + 1;
			qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
		});
		
		minusBtn.addEventListener('click', () => {
			let val = parseInt(qtyInput.value) || 0;
			let minVal = parseInt(qtyInput.getAttribute('min')) || 0;

			if (val > minVal) {
				qtyInput.value = val - 1;
				qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
			}
		});
	}

	// якщо документів немає — quantity = 0
	if (minQty === 0) {
		if (current !== 0) {
			qtyInput.value = 0;
			triggerCalc();
		}
		qtyInput.setAttribute('min', 0);
		return;
	}

	// встановлюємо мінімальне значення
	qtyInput.setAttribute('min', minQty);

	// якщо quantity менше кількості документів → підтягуємо
	if (current < minQty) {
		qtyInput.value = minQty;
		triggerCalc();
	}

	qtyInput.removeEventListener('input', qtyInput._limitHandler);
	qtyInput.removeEventListener('blur', qtyInput._blurHandler);

	qtyInput._limitHandler = () => {
		qtyInput.value = qtyInput.value.replace(/\D/g, '');
		 triggerCalc();
	};
	qtyInput._blurHandler = () => {
		let val = parseInt(qtyInput.value) || 0;
		let minVal = parseInt(qtyInput.getAttribute('min')) || 0;
		if (val < minVal) val = minVal;
		qtyInput.value = val;
		triggerCalc();
	};

	qtyInput.addEventListener('input', qtyInput._limitHandler);
	qtyInput.addEventListener('blur', qtyInput._blurHandler);

	if (typeof window.__initDocSelectWatcher === 'function') {
		window.__initDocSelectWatcher(); 
	}
}

if (qtyInput) {
	qtyInput.addEventListener('input', triggerCalc);
	updateQuantityFromSelect();
}

// translation

function isTranslationActive() {
	return !!document.querySelector(
		'input[type="radio"][name="translation"]:checked'
	);
}

function resetTranslationRadios() {
		document.querySelectorAll('input[type="radio"][name="translation"]').forEach(radio => {
			radio.checked = false;
			radio.classList.remove('_form-focus');
			radio.closest('label')?.classList.remove('_form-focus');
			radio.dispatchEvent(new Event('change', { bubbles: true }));
	});
}

function addPlusMinusToTranslationPages() {
	const input = document.getElementById('translation');
	if (!input) return;

	const min = 0;

	// оформлення кнопок + і -
	input.setAttribute('inputmode', 'numeric');
	input.setAttribute('pattern', '[0-9]*');
	input.setAttribute('type', 'text');
	if (!input.value) {
		input.value = '0';
	}

	if (!input.parentElement.classList.contains('qty-wrapper')) {
		const wrapper = document.createElement('div');
		wrapper.className = 'qty-wrapper';
		input.parentElement.insertBefore(wrapper, input);
		wrapper.appendChild(input);

		const minusBtn = document.createElement('button');
		minusBtn.type = 'button';
		minusBtn.textContent = '–';
		minusBtn.className = 'qty-btn minus';

		const plusBtn = document.createElement('button');
		plusBtn.type = 'button';
		plusBtn.textContent = '+';
		plusBtn.className = 'qty-btn plus';

		wrapper.insertBefore(minusBtn, input);
		wrapper.appendChild(plusBtn);

		// +
		plusBtn.addEventListener('click', () => {
			if (!isTranslationActive()) return;

			let val = parseInt(input.value) || 0;
			input.value = val + 1;
			triggerCalc();
		});

		// -
		minusBtn.addEventListener('click', () => {
			if (!isTranslationActive()) return;

			let val = parseInt(input.value) || 0;

			if (val <= 1) {
				input.value = '0';
				resetTranslationRadios();
			} else {
				input.value = val - 1;
			}

			triggerCalc();
		});
	}
}

if (translationInput) {
	translationInput.addEventListener('input', triggerCalc);
	addPlusMinusToTranslationPages();
}

document.addEventListener('change', e => {
	if (!e.target.matches('input[type="radio"][name="translation"]')) return;

	// иільки клік користувача
	if (!e.isTrusted) return;

	const input = document.getElementById('translation');
	if (!input) return;

	if (!input.value || input.value === '0') {
		input.value = '1';
	}

	triggerCalc();
});


// === MutationObserver для кастомного select ===
function observeCustomSelect() {
	if (!docSelect) return;
	try {
		const mo = new MutationObserver(muts => {
			for (const mut of muts) {
				if (
					mut.type === 'attributes' ||
					mut.type === 'childList' ||
					mut.target.classList.contains('select__option') ||
					mut.target.closest('.select')
				) {
					updateQuantityFromSelect();
					break;
				}
			}
		});
		mo.observe(docSelect.closest('.select') || docSelect, {
			attributes: true,
			childList: true,
			subtree: true
		});
	} catch (e) {
		console.warn('Ошибка в MutationObserver', e);
	}
}

// ===Блокування/розблокування radio-кнопок по класу _select-active списку  ===
(function () {
  let selectClassObserver = null;
  let bodyObserver = null;

  // Універсальне блокування/розблокування
  function setLocked(locked) {
    const qtyIds = ['quantity'];

    qtyIds.forEach(id => {
      const input = document.getElementById(id);
      if (!input) return;

      const wrapper = input.closest('.qty-wrapper');
      const plusBtn = wrapper ? wrapper.querySelector('.qty-btn.plus') : null;
      const minusBtn = wrapper ? wrapper.querySelector('.qty-btn.minus') : null;

      input.disabled = locked;
      input.readOnly = locked;
      input.classList.toggle('locked', locked);
      if (locked) input.setAttribute('tabindex', '-1'); else input.removeAttribute('tabindex');

      if (plusBtn) {
        plusBtn.disabled = locked;
        if (locked) plusBtn.setAttribute('aria-disabled', 'true'); else plusBtn.removeAttribute('aria-disabled');
      }
      if (minusBtn) {
        minusBtn.disabled = locked;
        if (locked) minusBtn.setAttribute('aria-disabled', 'true'); else minusBtn.removeAttribute('aria-disabled');
      }
    });

    // блокування/розблокування radio для RON і Apostille
    document.querySelectorAll('input[type="radio"][name="service_type_notarization"], input[type="radio"][name="service_type_apostille"], input[type="radio"][name="service_type_record"]')
      .forEach(r => {
        r.disabled = locked;
        if (locked) r.setAttribute('aria-disabled', 'true'); else r.removeAttribute('aria-disabled');
        const row = r.closest('tr');
        if (row) row.classList.toggle('locked', locked);
      });
  }

  // Перевіряємо чи активний select
  function isSelectActive() {
    if (typeof docSelect !== 'undefined' && docSelect) {
      const wrap = docSelect.closest('.select');
      if (wrap) return wrap.classList.contains('_select-active');
      if (docSelect.selectedOptions && docSelect.selectedOptions.length) {
        return Array.from(docSelect.selectedOptions).some(o => o.value && o.value.trim());
      }
    }
    return !!document.querySelector('#DocumentTypePagesWrapper .select._select-active');
  }

  function updateLockedFromSelect() {
    const active = isSelectActive();
    setLocked(!active);
  }

  function initSelectWatcher() {
    if (selectClassObserver) { selectClassObserver.disconnect(); selectClassObserver = null; }
    if (bodyObserver) { bodyObserver.disconnect(); bodyObserver = null; }

    updateLockedFromSelect();

    const wrapperEl = document.querySelector('#DocumentTypePagesWrapper .select') || (docSelect && docSelect.closest && docSelect.closest('.select'));
    if (wrapperEl) {
      selectClassObserver = new MutationObserver(() => updateLockedFromSelect());
      selectClassObserver.observe(wrapperEl, { attributes: true, attributeFilter: ['class'] });

      if (typeof docSelect !== 'undefined' && docSelect) {
        try { docSelect.removeEventListener('change', updateLockedFromSelect); } catch (e) {}
        docSelect.addEventListener('change', updateLockedFromSelect);
      }
    } else {
      bodyObserver = new MutationObserver((muts, obs) => {
        const found = document.querySelector('#DocumentTypePagesWrapper .select');
        if (found) {
          obs.disconnect();
          initSelectWatcher();
        }
      });
      bodyObserver.observe(document.body, { childList: true, subtree: true });
    }
  }

  function showHighlight() {
    const docTypeBlock = document.getElementById('DocumentTypePagesWrapper');
    if (!docTypeBlock) return;
    docTypeBlock.classList.add('highlight-missing');
    setTimeout(() => docTypeBlock.classList.remove('highlight-missing'), 1400);
  }

	
	
  // === Перехоплення кліків ===
  // Перехоплюємо клік по заблокаваним елементам
document.addEventListener('pointerdown', (e) => {
  const t = e.target;
  const qtyIds = ['quantity', 'translation'];
  const clickedOnQty = qtyIds.some(id => t === document.getElementById(id) || (!!t.closest && !!t.closest('.qty-wrapper')));
  const clickedOnRadio = t.matches && t.matches('input[type="radio"][name="service_type_notarization"], input[type="radio"][name="service_type_apostille"], input[type="radio"][name="service_type_record"], input[type="radio"][name="translation"]');

  const translationInput = document.getElementById('translation');
  const clickedTranslationQty = t === translationInput || (!!t.closest && t.closest('.qty-wrapper')?.contains(translationInput));

  const translationRadios = document.querySelectorAll('input[name="translation"]');
  const anyTranslationChecked = Array.from(translationRadios).some(r => r.checked);

	// 1) Клік по translation і не обрано жодного translation radio → підсвічуємо ВСІ radio translate
	if (clickedTranslationQty && !anyTranslationChecked) {
    document.querySelectorAll('label.radio input[name="translation"]').forEach(radio => {
      const label = radio.closest('label.radio');
      if (label && !label.classList.contains('highlight-missing')) {
        label.classList.add('highlight-missing');
        setTimeout(() => label.classList.remove('highlight-missing'), 1400);
      }
    });
    e.preventDefault();
    e.stopPropagation && e.stopPropagation();
    return;
  }

 	// 2) Якщо клік по translation при будь-якій іншій умові → нічого не підсвічуємо і викликаєм showHighlight()
  if (clickedTranslationQty) {
    return;
  }
	
	// 3) Якщо клік по radio translation → нічого не підсвічуємо
  if (clickedOnRadio && t.name === 'translation') {
    return;
  }

	// 4) Підсвічуємо DocumentTypePagesWrapper тфльки якщо клік по іншим заблокованим елементам (quantity, ron, apostille)
  if ((clickedOnQty && qtyIds.some(id => {
        const el = document.getElementById(id);
        // виключаємо translation з перевірки
        return el && el.id !== 'translation' && (el.disabled || el.readOnly);
      })) || (clickedOnRadio && t.disabled && t.name !== 'translation')) {
    showHighlight();
    e.preventDefault();
    e.stopPropagation && e.stopPropagation();
  }
}, true);

  // === Ініціалізація ===
  window.__initDocSelectWatcher = initSelectWatcher;

  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    initSelectWatcher();
  } else {
    window.addEventListener('DOMContentLoaded', () => {
      initSelectWatcher();
    });
  }

  // Повне блокування при завантаженні
  document.addEventListener('DOMContentLoaded', () => setLocked(true));
})();

														   
// ---------------------------------------------------------------------------------------------------------------------
// Збір данних для відправки на /order-now/
function getMultiSelectValues(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return [];

    const selectedBtns = select.parentElement.querySelectorAll('._select-selected');
    return Array.from(selectedBtns).map(btn => btn.dataset.value);
}

// --- Беремо обрані послуги на калькуляторі з /prices 
function getSelectedServices() {
    const services = [];
    if (document.querySelector('input[name="service_type_notarization"]:checked')) services.push('service_type_notarization');
    if (document.querySelector('input[name="service_type_apostille"]:checked')) services.push('service_type_apostille');
    if (document.querySelector('input[name="service_type_record"]:checked')) services.push('service_type_record');
    return services;
}

// --- Беремо обрані ljrevtynb на калькуляторі з /prices
function getSelectedDocs() {
    const selectedBtns = document.querySelectorAll('.select__options ._select-selected');
    return Array.from(selectedBtns).map(btn => btn.dataset.value);
}
// --- Беремо processing s delivery
function getProcessingFromApi() {
	const items = window.lastCalculationResponse?.items || [];
	const item = items.find(i => i.processing?.id);
	return item?.processing?.id || null;
}
														   
function getDeliveryFromApi() {
	const items = window.lastCalculationResponse?.items || [];
	const delivery = items.find(i => i.group === 'delivery');
	return delivery?.deliveryMethod || null;
}
														   
if (orderBtn) {
	orderBtn.addEventListener('click', () => {
														   
	if (!window.lastCalculationResponse) {
		console.warn('API response not ready yet');
		return;
	}

		const state = {
			services: getSelectedServices(),
			documentTypes: getSelectedDocs(),
			translation: Number(document.getElementById('translation')?.value) || 0,
			quantity: Number(document.getElementById('quantity')?.value) || 0,
			processing: getProcessingFromApi(),
			deliveryMethod: getDeliveryFromApi()
		};

		localStorage.setItem(
			'orderCalculatorState',
			JSON.stringify(state)
		);

		window.location.href = '/order-now/';
	});
}