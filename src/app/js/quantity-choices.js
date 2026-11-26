/**
 * Quantity choices: hides the quantity field on add-to-cart forms and shows radio
 * buttons for 1–4 items with the price for each (data from inc/woocommerce.php).
 *
 * The real `quantity` input stays in the form (just hidden) and is updated when a
 * radio is picked, so cart validation, Min/Max Quantities and the Extra Product
 * Options quantity conditions keep working unchanged.
 *
 * No quantity is pre-selected: the input is held at 0 and the add-to-cart button
 * is disabled until the customer picks one of the radios.
 */

const getProductId = ( form ) =>
	form.dataset.product_id ||
	form.querySelector( '[name="add-to-cart"]' )?.value ||
	null;

const setup = ( form, data, index ) => {
	const input = form.querySelector( 'input[name="quantity"]' );
	if ( ! input || input.type === 'hidden' ) {
		return;
	}
	const wrapper = input.closest( '.quantity' ) || input;
	const isVariable = form.classList.contains( 'variations_form' );

	const list = document.createElement( 'ul' );
	list.className = 'hb-qty-choices';
	list.setAttribute( 'role', 'radiogroup' );
	list.setAttribute( 'aria-label', 'Quantity' );
	wrapper.parentNode.insertBefore( list, wrapper );

	const name = `hb_qty_choice_${ index }`;
	const button = form.querySelector( '.single_add_to_cart_button, button[type="submit"]' );

	// Has the customer picked a quantity yet? Kept separately from input.value because
	// WooCommerce resets the input to the variation's minimum whenever one is chosen.
	let chosen = false;

	// Block adding to cart until a quantity is picked (only while the radios are shown).
	const updateButton = () => {
		const needed = ! list.hidden && ! chosen;
		if ( button ) {
			button.disabled = needed;
			button.classList.toggle( 'hb-qty-choice-needed', needed );
		}
	};

	const setQuantity = ( qty ) => {
		if ( String( input.value ) === String( qty ) ) {
			return;
		}
		input.value = qty;
		input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	};

	// Which quantities are allowed, read from the input (WooCommerce updates these per variation).
	const allowed = () => {
		const min = Math.max( 1, parseFloat( input.min ) || 1 );
		const max = parseFloat( input.max ) > 0 ? parseFloat( input.max ) : Infinity;
		const step = parseFloat( input.step ) > 0 ? parseFloat( input.step ) : 1;
		const quantities = [];
		for ( let qty = 1; qty <= data.max; qty++ ) {
			if ( qty >= min && qty <= max && ( qty - min ) % step === 0 ) {
				quantities.push( qty );
			}
		}
		return quantities;
	};

	const render = ( variationId ) => {
		const prices = data.prices[ variationId ] || null;
		const quantities = allowed();

		// Nothing to choose between: fall back to the normal field (WooCommerce may
		// still hide it itself, e.g. for a variation limited to 1).
		if ( quantities.length < 2 ) {
			list.hidden = true;
			if ( wrapper.dataset.hbQtyHidden ) {
				delete wrapper.dataset.hbQtyHidden;
				wrapper.style.removeProperty( 'display' );
			}
			// Don't leave the visible field on the placeholder 0.
			if ( ! ( parseFloat( input.value ) > 0 ) ) {
				setQuantity( quantities[ 0 ] || Math.max( 1, parseFloat( input.min ) || 1 ) );
			}
			updateButton();
			return;
		}

		const current = parseInt( input.value, 10 );
		let selected = null;
		if ( chosen ) {
			selected = quantities.includes( current ) ? current : quantities[ 0 ];
		}

		list.innerHTML = '';
		quantities.forEach( ( qty ) => {
			const id = `${ name }_${ qty }`;
			const li = document.createElement( 'li' );
			li.innerHTML = `
				<input type="radio" id="${ id }" name="${ name }" value="${ qty }">
				<label for="${ id }">
					<span class="hb-qty-choices__label"></span>
					<span class="hb-qty-choices__price price"></span>
				</label>`;
			li.querySelector( '.hb-qty-choices__label' ).textContent = data.labels[ qty ];
			// Price HTML is generated server-side by wc_price().
			li.querySelector( '.hb-qty-choices__price' ).innerHTML = prices ? prices[ qty ] : '';
			const radio = li.querySelector( 'input' );
			radio.checked = qty === selected;
			radio.addEventListener( 'change', () => {
				chosen = true;
				setQuantity( qty );
				updateButton();
			} );
			list.appendChild( li );
		} );

		list.hidden = false;
		wrapper.dataset.hbQtyHidden = '1';
		wrapper.style.setProperty( 'display', 'none', 'important' );
		setQuantity( selected ?? 0 );
		updateButton();
	};

	// Typed/changed elsewhere (e.g. WooCommerce clamping to a variation's max): reflect it.
	input.addEventListener( 'change', () => {
		if ( ! chosen ) {
			return;
		}
		const radio = list.querySelector( `input[value="${ parseInt( input.value, 10 ) }"]` );
		if ( radio ) {
			radio.checked = true;
		}
	} );

	// Safety net for submits that skip the button (e.g. pressing Enter in another field).
	form.addEventListener( 'submit', ( e ) => {
		if ( ! list.hidden && ! chosen ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			list.querySelector( 'input' )?.focus();
		}
	} );

	if ( ! isVariable ) {
		render( getProductId( form ) );
		return;
	}

	// Variable products: prices depend on the chosen variation.
	const variationId = () => form.querySelector( 'input[name="variation_id"]' )?.value;
	render( variationId() );

	const $ = window.jQuery;
	if ( $ ) {
		// Defer so WooCommerce has applied the variation's min/max to the input first.
		$( form ).on( 'found_variation', ( e, variation ) => setTimeout( () => render( variation.variation_id ) ) );
		$( form ).on( 'reset_data', () => setTimeout( () => render( null ) ) );
	}
};

export const initQuantityChoices = () => {
	const all = window.homeBaseQtyChoices;
	if ( ! all ) {
		return;
	}
	document.querySelectorAll( 'form.cart' ).forEach( ( form, index ) => {
		const data = all[ getProductId( form ) ];
		if ( data && ! form.dataset.hbQtyChoices ) {
			form.dataset.hbQtyChoices = '1';
			setup( form, data, index );
		}
	} );
};
