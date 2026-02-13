// API
// Глобальна змінна
let allServicesMap = {};

// Після отримання конфигурації з API
fetch('/wp-admin/admin-ajax.php?action=get_calculator_config')
  .then(r => r.json())
  .then(r => {
    if (!r.success) throw new Error('API error');

    allServicesMap = r.data.serviceTypes.reduce((acc, item) => {
      // item.id — ключ, item.text — текст послуги
      acc[item.id] = { text: item.text };
      return acc;
    }, {});

//     console.log('allServicesMap', allServicesMap);
  })
  .catch(console.error);
	
// Рендер одного item для виводу
function renderItem(item) {
  const row = document.createElement('div');
  row.className = 'order__row';
  row.dataset.type = item.type;

  if (item.serviceId) row.dataset.id = item.serviceId;
  if (item.deliveryMethod) row.dataset.id = item.deliveryMethod;

  let titleHtml = item.title;

  // processing (якщо є)
  if (item.processing?.text) {
    titleHtml += `<br>${item.processing.text}`;
  }

  // documents
  if (item.documents) {
    titleHtml += `<br>${item.documents} document`;
  }

  // translation pages
	if (item.type === 'translation' && item.pages) {
	  titleHtml = `${item.title} (${item.pages} page${item.pages > 1 ? 's' : ''})`;
	}

  row.innerHTML = `
    <div class="order__data">
      <p>${titleHtml}</p>
      <h3>$${item.total.toFixed(2)}</h3>
    </div>
    <div class="order__icons">
      <span class="pen _icon-pen" style="cursor:pointer"></span>
      <span class="trash _icon-trash" style="cursor:pointer"></span>
    </div>
  `;

  return row;
}

// Маппінг для того, щоб виставити в правильному порядку послуги
const SERVICE_GROUP_MAP = {
  service_type_notarization: 'notarization',
  service_type_apostille: 'apostille',
  service_type_record: 'record'
};

// При рендері підміняємо group
function resolveGroup(item) {
  if (item.group === 'delivery') return 'delivery';
  if (item.group === 'translation') return 'translation';

  if (item.type === 'service' && item.serviceId) {
    return SERVICE_GROUP_MAP[item.serviceId] || 'record';
  }

  return null;
}


// Розкладуємо по групам
function renderOrder(response) {
  clearGroups();

  if (!response?.items || !Array.isArray(response.items)) {
    console.warn('[renderOrder] No items to render', response);
    return;
  }

  response.items.forEach(item => {
    const groupName = resolveGroup(item);
    if (!groupName) return;

    const group = document.querySelector(
      `.order-group[data-group="${groupName}"]`
    );
    if (!group) return;

    group.appendChild(renderItem(item));
  });
}


// Очищення груп
function clearGroups() {
  document.querySelectorAll('.order-group').forEach(group => {
    group.innerHTML = '';
  });
}


	function getSelectValue(id) {
	  const el = document.getElementById(id);
	  return el && el.value ? el.value : null;
	}

	function getMultiSelectValues(id) {
	  const el = document.getElementById(id);
	  if (!el) return [];
	  return Array.from(el.selectedOptions).map(o => o.value);
	}
	
// Виклик тринера на кожен change
document.addEventListener('change', e => {
  if (e.target.matches('select, input')) {
    triggerCalc();
  }
});

// debounce, щоб АРІ не викликалося занадто часто
let calcTimer;

function triggerCalc() {
  clearTimeout(calcTimer);
  calcTimer = setTimeout(calculate, 150);
}

document.addEventListener('click', e => {

  if (e.target.closest('.select__option')) {
    triggerCalc();
  }

  if (e.target.matches('input')) {
    triggerCalc();
  }

});


// Стан обраних сервисів
let selectedDelivery = null; // синхронізуємо з payload.deliveryMethod


// Виклик калькулятора
function calculate({ ignoreServices = false } = {}) {

  const payload = {
    quantity: +document.getElementById('quantity')?.value || 0,
    documentTypes: getMultiSelectValues('documentTypes'),
     serviceTypes: getMultiSelectValues('serviceTypes'),
	processingTypeOrder: getSelectValue('processingAuthentication'),
    translation: +document.getElementById('translation')?.value || 0,
    deliveryMethod: getSelectValue('deliveryMethod')
  };

//   console.log('%c[CALCULATE payload]', 'color: blue', payload);

  fetch('/wp-admin/admin-ajax.php?action=calculate_price_order', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
    .then(r => r.json())
	.then(r => {
// 	  console.log('%c[CALCULATE response]', 'color: green', r);

	  if (!r.success || !r.data?.summary) {
		clearGroups();
		updateTotals({ subtotal: 0, tax: 0, taxRate: 0, total: 0 });
		return;
	  }

	  updateTotals(r.data.summary);
	  renderOrder(r.data)
	})
    .catch(console.error);
}

// Виведення результатів
function updateTotals(summary) {
  if (!summary) return;

  document.getElementById('subtotal').textContent =
    `$${summary.subtotal.toFixed(2)}`;

  document.getElementById('taxAmount').textContent =
    `$${summary.tax.toFixed(2)}`;

  document.getElementById('taxLabel').textContent =
    summary.taxRate.toFixed(3);

  document.getElementById('grand').textContent =
    `$${summary.total.toFixed(2)}`;

	// $zelleData = [
	// 	"name"   => "APOSTIL INC.",
	// 	"action" => "payment",
	// 	"token"  => "apostil",
	// 	"amount" => $calculatedPrice['total']
	// ];


	let zelleData = {
		"name"   : "APOSTIL INC.",
		"action" : "payment",
		"token"  : "apostil",
		"amount" : summary.total.toFixed(2)
	}

	zelleData = JSON.stringify(zelleData);
	zelleData = btoa(zelleData);

	fetch('/?get-stripe=' + summary.total.toFixed(2))
		.then(r => r.text())
		.then(r => {
			document.getElementById('stripePayLink').href = r;
		})
		.catch(console.error);

	document.getElementById('zellePayLink').href = 'https://enroll.zellepay.com/qr-codes?data=' + zelleData;

	document.getElementById('wisePayLink').href = "https://wise.com/pay/business/apostilinc?amount="+summary.total.toFixed(2);

}

// ------------------------------------------------------------------------------- КОД ----------------------------------------------------------------------- //

const qtyInput = document.getElementById('quantity');
const translationInput = document.getElementById('translation');
const docSelect = document.getElementById('documentTypes');
const translationFrom = document.getElementById('translationLanguagesFrom');
const translationTo = document.getElementById('translationLanguagesTo');

// Рахуємо обрані елементи кастомного select
function getSelectedDocumentTypesCount() {
	const selectWrapper = document.querySelector('#documentTypes')?.closest('.select');
	if (!selectWrapper) return 0;

	const selected = selectWrapper.querySelectorAll('.select__option._select-selected');
	return selected.length;
}

// === Универсальна функція для додавання + / - ===
function addPlusMinusToInput(inputId) {
	const input = document.getElementById(inputId);
	if (!input) return;

	if (!input.parentElement.classList.contains('qty-wrapper')) {
		const wrapper = document.createElement('div');
		wrapper.className = 'qty-wrapper';
		input.parentElement.insertBefore(wrapper, input);
		wrapper.appendChild(input);

		const minusBtn = document.createElement('button');
		minusBtn.type = 'button';
		minusBtn.textContent = '–';
		minusBtn.className = 'qty-btn minus page-order-minus';

		const plusBtn = document.createElement('button');
		plusBtn.type = 'button';
		plusBtn.textContent = '+';
		plusBtn.className = 'qty-btn plus page-order-plus';

		wrapper.insertBefore(minusBtn, input);
		wrapper.appendChild(plusBtn);

		// --- кліки по мінусу ---
		minusBtn.addEventListener('click', () => {
			let val = parseInt(input.value) || 0;

			if (inputId === 'quantity') {
				// для quantity враховуємо мінімальне значення = обрані documentTypes
				const minCount = getSelectedDocumentTypesCount();
				val = Math.max(val - 1, minCount);
			} else if (inputId === 'translation') {
				// для translation мінімальне значення = 0
				val = Math.max(val - 1, 0);

				// якщо стало 0 — скидаємо selects
				if (val === 0) resetTranslation();
			}

			input.value = val;
			input.dispatchEvent(new Event('input', { bubbles: true }));

			if (inputId === 'translation') syncTranslation();
			if (typeof triggerCalc === 'function') triggerCalc();
		});

		// --- кліки по плюсу ---
		plusBtn.addEventListener('click', () => {
			let val = parseInt(input.value) || 0;
			val++;

			input.value = val;
			input.dispatchEvent(new Event('input', { bubbles: true }));

			if (inputId === 'translation') syncTranslation(); 
			if (typeof triggerCalc === 'function') triggerCalc();
		});
	}

	// --- тільки цифрм ---
	input._limitHandler = () => {
		input.value = input.value.replace(/\D/g, '');
		if (inputId === 'translation') syncTranslation();
		if (typeof triggerCalc === 'function') triggerCalc();
	};

	// --- мінімальне значення при втраті фокусу ---
	input._blurHandler = () => {
		let val = parseInt(input.value) || 0;

		if (inputId === 'quantity') {
			const minCount = getSelectedDocumentTypesCount();
			if (val < minCount) val = minCount;
		} else if (inputId === 'translation') {
			// для translation min = 0
			if (val < 0) val = 0;
			if (val === 0) resetTranslation();
		}

		input.value = val;
		if (inputId === 'translation') syncTranslation();
		if (typeof triggerCalc === 'function') triggerCalc();
	};

	input.addEventListener('input', input._limitHandler);
	input.addEventListener('blur', input._blurHandler);
}

document.addEventListener('DOMContentLoaded', () => {
	addPlusMinusToInput('translation', 0); 
	addPlusMinusToInput('quantity', 0);
});


// перевіряємо, чи активний кастомный select
function isDocumentTypesActive() {
	const selectWrapper = document.querySelector('#documentTypes')?.closest('.select');
	if (!selectWrapper) return false;

	// якщо є _select-active — знімаємо блокування з quantity
	return selectWrapper.classList.contains('_select-active');
}

// Синхронизація quantity
function syncQuantityWithDocumentTypes() {
	const qty = document.getElementById('quantity');
	if (!qty) return;

	const active = isDocumentTypesActive();

	if (active) {
		// знімаємо блокуванн
		setQuantityLocked(false);

		// quantity >= обраних елементів
		const count = document.querySelectorAll('#documentTypes + .select__body .select__option._select-selected').length;
		let val = parseInt(qty.value) || 0;
		if (val < count) qty.value = count;

		if (typeof triggerCalc === 'function') triggerCalc();
	} else {
		// блокуємо назад
		setQuantityLocked(true);
		qty.value = 0;
	}
}

// Підписка на кастомный event
document.addEventListener('selectCallback', (e) => {
	const { select } = e.detail || {};
	if (!select) return;
	if (select.id !== 'documentTypes') return;

	syncQuantityWithDocumentTypes();
});

// Ініціалізація на старті
document.addEventListener('DOMContentLoaded', () => {
	addPlusMinusToInput('quantity', 0);

	syncQuantityWithDocumentTypes();
});

// Керування блокуванням quantity
function setQuantityLocked(locked) {
	const input = document.getElementById('quantity');
	if (!input) return;

	const wrapper = input.closest('.qty-wrapper');
	const plus = wrapper?.querySelector('.qty-btn.plus');
	const minus = wrapper?.querySelector('.qty-btn.minus');

	input.disabled = locked;
	input.readOnly = locked;

	if (plus) plus.disabled = locked;
	if (minus) minus.disabled = locked;
}

// Пісвітка documentTypes і translations при кліку на quantity i translation, якщо вони заблоковані
document.addEventListener('pointerdown', (e) => {
	const qty = document.getElementById('quantity');
	if (!qty) return;

	const qtyWrapper = qty.closest('.qty-wrapper');

	const clickedQty =
		e.target === qty ||
		qtyWrapper?.contains(e.target);

	if (!clickedQty) return;

	const qtyLocked = qty.disabled || qty.readOnly;

	/* ---------- QUANTITY ---------- */
	if (clickedQty && qtyLocked) {
		const docSelect = document.getElementById('documentTypes');
		const parent = docSelect?.closest('div');

		if (parent && !parent.classList.contains('highlight-missing')) {
			parent.classList.add('highlight-missing');
			setTimeout(() => parent.classList.remove('highlight-missing'), 1400);
		}

		e.preventDefault();
		e.stopPropagation();
		return;
	}

}, true);


// Блокування translation
function setTranslationLocked(locked) {
	const input = document.getElementById('translation');
	if (!input) return;

	const wrapper = input.closest('.qty-wrapper');
	const plus = wrapper?.querySelector('.qty-btn.plus');
	const minus = wrapper?.querySelector('.qty-btn.minus');

	input.disabled = locked;
	input.readOnly = locked;

	if (plus) plus.disabled = locked;
	if (minus) minus.disabled = locked;
}

// Підсвітка при кліку на заблокований translation
document.addEventListener('pointerdown', (e) => {
	const transl = document.getElementById('translation');
	if (!transl) return;

	const translWrapper = transl.closest('.qty-wrapper');
	const clickedTransl = e.target === transl || translWrapper?.contains(e.target);
	if (!clickedTransl) return;

	const translLocked = transl.disabled || transl.readOnly;
	if (!translLocked) return;

	// Підсвітка кастомных select
	const from = document.getElementById('translationLanguagesFrom')?.closest('div');
	const to = document.getElementById('translationLanguagesTo')?.closest('div');

	if (from && !from.classList.contains('highlight-missing')) {
		from.classList.add('highlight-missing');
		setTimeout(() => from.classList.remove('highlight-missing'), 1400);
	}

	if (to && !to.classList.contains('highlight-missing')) {
		to.classList.add('highlight-missing');
		setTimeout(() => to.classList.remove('highlight-missing'), 1400);
	}

	e.preventDefault();
	e.stopPropagation();
});

// Перевіряємо, чи був обраний хоча б один translation select
function syncTranslation() {
	const transl = document.getElementById('translation');
	if (!transl) return;

	const fromActive = document.querySelector('#translationLanguagesFrom')?.closest('.select')?.classList.contains('_select-active');
	const toActive   = document.querySelector('#translationLanguagesTo')?.closest('.select')?.classList.contains('_select-active');

	if (fromActive || toActive) {
		setTranslationLocked(false);

		let val = parseInt(transl.value) || 0;
		if (val < 0) transl.value = 0;

		if (typeof triggerCalc === 'function') triggerCalc();
	} else {
		setTranslationLocked(true);
		transl.value = 0;
	}
}

// Слуїаємо кастомні події selectCallback
document.addEventListener('selectCallback', (e) => {
	const { select } = e.detail || {};
	if (!select) return;

	if (select.id === 'translationLanguagesFrom' || select.id === 'translationLanguagesTo') {
		syncTranslation();
	}
});

// Ініціазація на старті
document.addEventListener('DOMContentLoaded', () => {
	addPlusMinusToInput('translation'); 
	setTranslationLocked(true);       
	syncTranslation();               
});

// Синхронізація атрибутів при зміні translation
function syncTranslation() {
	const transl = document.getElementById('translation');
	if (!transl) return;

	const fromActive = document.querySelector('#translationLanguagesFrom')?.closest('.select')?.classList.contains('_select-active');
	const toActive   = document.querySelector('#translationLanguagesTo')?.closest('.select')?.classList.contains('_select-active');

	if (fromActive || toActive) {
		setTranslationLocked(false);

		let val = parseInt(transl.value) || 0;
		if (val < 1) transl.value = 1; // при выборе select сразу ставим 1

		// додаємо обов'язковість
		const fromSelect = document.getElementById('translationLanguagesFrom');
		const toSelect   = document.getElementById('translationLanguagesTo');

		[fromSelect, toSelect].forEach(sel => {
			if (!sel) return;
			sel.setAttribute('data-required', '');
			sel.setAttribute('data-error', 'Error');
		});

		if (typeof triggerCalc === 'function') triggerCalc();
	} else {
		resetTranslation(); // если selectи не обрані — блокуємо
	}
}


function getServiceSelect() {
  return document.querySelector('.service-wrapper .select');
}

function getDeliverySelect() {
  return document.querySelector('.delivery-wrapper .select');
}

// Підсвітка для редагування
function highlightField(element) {
  if (!element) return;
  element.classList.add('highlight');
  element.scrollIntoView({
    behavior: 'smooth',
    block: 'center'
  });
}

function removeHighlight(element) {
  if (!element) return;
  element.classList.remove('highlight');
}

// Фокус при кліку на редагування
function focusFieldByItem(item) {
  let el = null;

  if (item.type === 'service') {
    el = getServiceSelect();
  }

  if (item.type === 'delivery') {
    el = getDeliverySelect();
  }

  if (item.type === 'translation') {
    el = document.getElementById('translation');
  }

  if (!el) return;

  highlightField(el);
}

// Клік на редагування
document.addEventListener('click', e => {
  const pen = e.target.closest('.order__row .pen');
  if (!pen) return;

  const row = pen.closest('.order__row');

//   console.log('[PEN]', {type: row.dataset.type,id: row.dataset.id });

  focusFieldByItem({
    type: row.dataset.type,
    id: row.dataset.id
  });
});

// Побудова items для renderOrder на основі поточного стану
function buildItemsFromState() {
  const items = [];

 const serviceIds = getMultiSelectValues('serviceTypes');

  serviceIds.forEach(serviceId => {
    const service = allServicesMap[serviceId];
    if (!service) return;

    items.push({
      group: 'service',
      type: 'service',
      serviceId,
      title: service.text,
	  total: 0
    });
  });

  if (selectedDelivery) {
    const delivery = allDeliveryMap[selectedDelivery];
    if (delivery) {
      items.push({
        group: 'delivery',
        type: 'delivery',
        deliveryMethod: selectedDelivery,
        title: delivery.text,
        total: delivery.price
      });
    }
  }
  return items;
}

// Функція зняття сервіса з select
function removeServiceFromSelect(id) {
  const select = document.querySelector('.select[data-id="5"]');
  if (!select) return;

  const option = select.querySelector(
    `.select__option[data-value="${id}"]`
  );

  if (!option) return;

  option.classList.remove('_select-selected');

  updateServiceTypeSelectHeader();

 selectedServices = [...select.querySelectorAll('.select__option._select-selected')]
    .map(o => o.dataset.value);
}


function updateServiceTypeSelectHeader() {
  const select = document.querySelector('.select[data-id="5"]');
  if (!select) return;

  const selected = select.querySelectorAll(
    '.select__option._select-selected'
  );

  const header = select.querySelector('.select__content');

  header.textContent = selected.length
    ? [...selected].map(o => o.textContent.trim()).join(', ')
    : 'Specify required services';
}


// Зберігаємо чистий delivery для того, щоб потім використовувати при видаленні з order
let deliveryInitialHTML = null;
window.addEventListener('DOMContentLoaded', () => {
  const wrapper = document.querySelector('.delivery-wrapper');
  if (wrapper) {
    deliveryInitialHTML = wrapper.innerHTML;
  }
});

function removeRow(row) {
  if (!row) return;

  const type = row.dataset.type;
  const id = row.dataset.id;

	if (type === 'service') {
	  const select = document.getElementById('serviceTypes');
	  if (!select) return;

	  //  Знімаємо selected у потрібного option
	  const option = [...select.options].find(opt => opt.value === id);
	  if (option) {
		option.selected = false;
	  }

	  //  Сповіщаємо кастомный select 
	  document.dispatchEvent(new CustomEvent('selectCallback', {
		detail: { select }
	  }));

	  //  ТРИГГЕРИМ change — наче користувач самостійно клікнув
	  select.dispatchEvent(new Event('change', { bubbles: true }));

	  //  видаляємо рядок
	  row.remove();

	  return;
	}

	if (type === 'delivery') {
	  selectedDelivery = null;

	  const wrapper = document.querySelector('.delivery-wrapper');
	  if (!wrapper || !deliveryInitialHTML) return;

	  //  повертаємо чистий DOM
	  wrapper.innerHTML = deliveryInitialHTML;

	  const select = document.getElementById('deliveryMethod');

	  fetch('/wp-admin/admin-ajax.php?action=get_calculator_config')
		.then(r => r.json())
		.then(r => {
		  if (!r.success) throw new Error('API error');

		  renderSelect('deliveryMethod', r.data.deliveryMethod,	'Return Shipping' );

		  if (window.modules_flsModules?.select) {
			window.modules_flsModules.select.selectInit(select, 1);
		  }

		 calculate({ ignoreServices: true });
		});

	  row.remove();
	  return;
	}

  if (type === 'translation') {
    resetTranslation();
    row.remove();
    calculate();
    return;
  }
}

// Скидання translation

function resetTranslation() {
	const transl = document.getElementById('translation');
	if (!transl) return;

	// Скидання значення і блокування
	transl.value = 0;
	setTranslationLocked(true);

	// Скидання selects
	const fromSelect = document.getElementById('translationLanguagesFrom');
	const toSelect   = document.getElementById('translationLanguagesTo');

	[fromSelect, toSelect].forEach(sel => {
		if (!sel) return;

		// Прибираємо активні класи
		sel.querySelectorAll('._select-selected').forEach(el => el.classList.remove('_select-selected'));
		sel.closest('.select')?.classList.remove('_select-active', '_form-error');

		// Очищвємо візуальний текст и ставимо placeholder
		const content = sel.closest('.select')?.querySelector('.select__content');
		if (content) {
			const placeholder = sel.getAttribute('data-placeholder') || '';
			content.textContent = placeholder;
		}

		// Прибираємо обов'язковість
		sel.removeAttribute('data-required');
		sel.removeAttribute('data-error');
		sel.classList.remove('_form-error');
	});
}

// Клік по "trash"
document.addEventListener('click', e => {
  const trash = e.target.closest('.order__row .trash');
  if (!trash) return;

  const row = trash.closest('.order__row');
  removeRow(row);
});

// ----------------------------------------------------------------  Прийом данних з /prices ----------------------------------------------------------
function waitForCustomSelect(selectId, callback) {
    const select = document.getElementById(selectId);
    if (!select) return;

    let root = select.closest('.select');
    if (root) {
        callback(root);
        return;
    }

    const observer = new MutationObserver(() => {
        root = select.closest('.select');
        if (root) {
            observer.disconnect();
            callback(root);
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
}

 
// МУЛЬТИСЕЛЕКТ (services, documentTypes)
function setMultiSelectValues(selectId, values = []) {
	const select = document.getElementById(selectId);
	if (!select) return;

	const root = select.closest('.select');
	if (!root) return;

	const options = root.querySelectorAll('.select__option');

	options.forEach(opt => {
		const val = opt.dataset.value;
		const shouldBeSelected = values.includes(val);
		const isSelected = opt.classList.contains('_select-selected');

		if (shouldBeSelected && !isSelected) {
			opt.dispatchEvent(new MouseEvent('click', { bubbles: true }));
		}

		if (!shouldBeSelected && isSelected) {
			opt.dispatchEvent(new MouseEvent('click', { bubbles: true }));
		}
	});
}

// ОДИНОЧНИЙ SELECT (processing, delivery)
function setSingleSelectValue(selectId, value) {
	const select = document.getElementById(selectId);
	if (!select || !value) return;

 	const root = select.closest('.select');

	const optionBtn = root.querySelector(`.select__option[data-value="${value}"]`);
	const content = root.querySelector('.select__content');

	// Виставляємо value у реального select
	select.value = value;

	// Оновлюємо текст
	if (optionBtn && content) {
		content.textContent = optionBtn.textContent.trim();
	}

	// робимо селект активним
	root.classList.add('_select-active');

	// прибираємо обраний option
	root.querySelectorAll('.select__option').forEach(opt => {
		opt.hidden = opt.dataset.value === value;
	});

	const event = new CustomEvent('selectCallback', {
		detail: { select: select } 
	});
	document.dispatchEvent(event);

	select.dispatchEvent(new Event('change', { bubbles: true }));
}

document.addEventListener('DOMContentLoaded', () => {
    const state = JSON.parse(localStorage.getItem('orderCalculatorState') || '{}');
    if (!state || !Object.keys(state).length) return;

    // ==== МУЛЬТИСЕЛЕКТИ ====
    waitForCustomSelect('serviceTypes', () => {
        setMultiSelectValues('serviceTypes', state.services);
    });
    waitForCustomSelect('documentTypes', () => {
        setMultiSelectValues('documentTypes', state.documentTypes);
    });

    // ==== ОДИНОЧНІ SELECT ====
    waitForCustomSelect('processingAuthentication', () => {
        if (state.processing) {
            setSingleSelectValue('processingAuthentication', state.processing);
        }
    });
    waitForCustomSelect('deliveryMethod', () => {
        if (state.deliveryMethod) {
            setSingleSelectValue('deliveryMethod', state.deliveryMethod);
        }
    });

    // ==== quantity і translation ====
    const translInput = document.getElementById('translation');
    if (translInput) translInput.value = state.translation;

    const qtyInput = document.getElementById('quantity');
    if (qtyInput) qtyInput.value = state.quantity;

    // ==== запускаем перерахунок ====
	   setTimeout(() => {
		triggerCalc();
	}, 500);
});




