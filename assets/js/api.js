// config (select'и, тексти, списки)
fetch('/wp-admin/admin-ajax.php?action=get_calculator_config')
  .then(res => res.json())
  .then(res => {
    if (!res.success) throw new Error('API error');

    const data = res.data;

    renderSelect('destinationCountry', data.destinationCountry, 'Select destination country');
    renderSelect('documentTypes', data.documentTypes, 'Start by selecting the document');
    renderSelect('serviceTypes', data.serviceTypes, 'Specify required services');
	renderSelect('processingAuthentication', data.processingAuthentication, 'How fast do you need it?');
	renderSelect('translationLanguagesFrom', data.translationLanguages, 'Translate from');
	renderSelect('translationLanguagesTo', data.translationLanguages, 'Translate to');
	renderSelect('countryFrom', data.destinationCountry, 'Country');
	renderSelect('countryTo', data.destinationCountry, 'Country');
	renderSelect('usStatesFrom', data.usStates, 'State');
	renderSelect('usStatesTo', data.usStates, 'State');
	renderSelect('deliveryMethod', data.deliveryMethod, 'Return Shipping');
	
	window.initSelects();
  })
  .catch(err => console.error(err));

function renderSelect(id, items, placeholder = 'Select...') {
  const select = document.getElementById(id);
  if (!select) return;

  // додаємо кастомний placeholder
  select.innerHTML = `<option value="">${placeholder}</option>`;

  items.forEach(item => {
    const opt = document.createElement('option');
    opt.value = item.id;
    opt.textContent = item.text;
    select.appendChild(opt);
  });
}
