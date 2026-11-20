/**
 * Quantity choices: hides the quantity field on add-to-cart forms and shows radio
 * buttons for 1–4 items with the price for each (data from inc/woocommerce.php).
 *
 * The real `quantity` input stays in the form (just hidden) and is updated when a
 * radio is picked, so cart validation, Min/Max Quantities and the Extra Product
 * Options quantity conditions keep working unchanged.
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
			return;
		}

		const current = parseInt( input.value, 10 );
		const selected = quantities.includes( current ) ? current : quantities[ 0 ];

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
			radio.addEventListener( 'change', () => setQuantity( qty ) );
			list.appendChild( li );
		} );

		list.hidden = false;
		wrapper.dataset.hbQtyHidden = '1';
		wrapper.style.setProperty( 'display', 'none', 'important' );
		setQuantity( selected );
	};

	// Typed/changed elsewhere (e.g. WooCommerce clamping to a variation's max): reflect it.
	input.addEventListener( 'change', () => {
		const radio = list.querySelector( `input[value="${ parseInt( input.value, 10 ) }"]` );
		if ( radio ) {
			radio.checked = true;
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
