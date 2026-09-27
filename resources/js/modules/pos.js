import { toast } from './flash.js';

/**
 * The POS / Billing screen.
 * Cart state lives in memory (window.cart) and mirrors what will be sent to
 * the server on Save / Checkout. Works for BOTH table orders and manual /
 * takeaway orders from the same screen.
 */
export function initPOS($) {
    const $screen = $('#pos-screen');
    if (!$screen.length) return;

    const currency = window.posConfig.currencySymbol || '$';
    let cart = window.posConfig.initialItems || []; // [{product_id,name,price,quantity,note}]
    let currentOrderId = window.posConfig.orderId || null;
    let currentTableId = window.posConfig.tableId || null;

    function fmt(n) { return currency + Number(n || 0).toFixed(2); }

    function calcTotals() {
        const subtotal = cart.reduce((sum, i) => sum + i.price * i.quantity, 0);
        const discount = parseFloat($('#discount-input').val()) || 0;
        const taxPercent = parseFloat($('#tax-input').val()) || 0;
        const taxable = Math.max(subtotal - discount, 0);
        const taxAmount = +(taxable * (taxPercent / 100)).toFixed(2);
        const total = +(taxable + taxAmount).toFixed(2);
        return { subtotal, discount, taxPercent, taxAmount, total };
    }

    function renderCart() {
        const $body = $('#cart-items');
        $body.empty();

        if (cart.length === 0) {
            $body.html('<div class="text-center text-gray-400 text-sm py-10">Cart is empty.<br>Tap a product to add it.</div>');
        } else {
            cart.forEach((item, idx) => {
                $body.append(`
                    <div class="flex items-center gap-2 py-2.5 border-b border-gray-100" data-cart-row="${idx}">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">${item.name}</p>
                            <p class="text-xs text-gray-400">${fmt(item.price)} each</p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button type="button" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold" data-qty-dec="${idx}">−</button>
                            <input type="text" class="w-9 text-center text-sm font-semibold border-0 focus:ring-0" value="${item.quantity}" data-qty-input="${idx}" inputmode="numeric">
                            <button type="button" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold" data-qty-inc="${idx}">+</button>
                        </div>
                        <p class="w-16 text-right text-sm font-semibold text-gray-800">${fmt(item.price * item.quantity)}</p>
                        <button type="button" class="text-red-400 hover:text-red-600 ml-1" data-remove-item="${idx}" title="Remove">✕</button>
                    </div>
                `);
            });
        }

        const t = calcTotals();
        $('#sum-subtotal').text(fmt(t.subtotal));
        $('#sum-tax').text(fmt(t.taxAmount));
        $('#sum-total').text(fmt(t.total));
        $('#cart-count-badge').text(cart.reduce((s, i) => s + i.quantity, 0));

        const cartEmpty = cart.length === 0;
        const isDineIn = $('#order-type-select').val() === 'dine_in';
        const hasTable = !!$('#table-select').val();

        $('#checkout-btn').prop('disabled', cartEmpty);

        $('#save-order-btn').prop(
            'disabled',
            cartEmpty || !isDineIn || !hasTable
        );

    }

    function addToCart(product) {
        const existing = cart.find((i) => i.product_id === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ product_id: product.id, name: product.name, price: parseFloat(product.price), quantity: 1, note: '' });
        }
        renderCart();
    }

    // --- Product grid interactions -----------------------------------------
    $screen.on('click', '[data-add-product]', function () {
        const $tile = $(this);
        addToCart({
            id: $tile.data('id'),
            name: $tile.data('name'),
            price: $tile.data('price'),
        });
    });

    $screen.on('click', '[data-qty-inc]', function () {
        cart[$(this).data('qty-inc')].quantity += 1;
        renderCart();
    });
    $screen.on('click', '[data-qty-dec]', function () {
        const idx = $(this).data('qty-dec');
        cart[idx].quantity -= 1;
        if (cart[idx].quantity <= 0) cart.splice(idx, 1);
        renderCart();
    });
    $screen.on('change', '[data-qty-input]', function () {
        const idx = $(this).data('qty-input');
        let val = parseInt($(this).val(), 10);
        if (isNaN(val) || val < 1) val = 1;
        cart[idx].quantity = val;
        renderCart();
    });
    $screen.on('click', '[data-remove-item]', function () {
        cart.splice($(this).data('remove-item'), 1);
        renderCart();
    });

    $('#discount-input, #tax-input').on('input', renderCart);
    // Restrict to numbers only, in real time
    $('#discount-input, #tax-input').on('keypress', function (e) {
        const char = String.fromCharCode(e.which);
        if (!/[0-9.]/.test(char)) e.preventDefault();
    });

    // --- Product search / category filter (AJAX) ---------------------------
    let searchTimer;
    function fetchProducts() {
        $.ajax({
            url: window.routes.productSearch,
            data: { q: $('#pos-product-search').val(), category_id: $('#pos-active-category').val() },
            dataType: 'json',
        }).done((res) => renderProductGrid(res.products));
    }

    $('#pos-product-search').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchProducts, 300);
    });

    $('#pos-category-pills').on('click', '.category-pill', function () {
        $('.category-pill').removeClass('active');
        $(this).addClass('active');
        $('#pos-active-category').val($(this).data('category-id') || '');
        fetchProducts();
    });

    function renderProductGrid(products) {
        const $grid = $('#pos-product-grid');
        if (!products.length) {
            $grid.html('<div class="col-span-full text-center text-gray-400 text-sm py-10">No products found.</div>');
            return;
        }
        $grid.html(products.map((p) => `
            <div class="product-tile ${p.is_available ? '' : 'unavailable'}" data-add-product data-id="${p.id}" data-name="${escapeHtml(p.name)}" data-price="${p.price}">
                <img src="${p.image_url}" class="w-full h-20 object-cover bg-gray-100" loading="lazy" alt="">
                <div class="p-2 text-center">
                    <p class="text-xs font-semibold text-gray-800 truncate">${escapeHtml(p.name)}</p>
                    <p class="text-xs text-brand-600 font-bold">${fmt(p.price)}</p>
                </div>
            </div>
        `).join(''));
    }

    function escapeHtml(str) {
        return $('<div>').text(str).html();
    }

    // --- Table / order type selection --------------------------------------
    $('#order-type-select').on('change', function () {
        const isDineIn = $(this).val() === 'dine_in';
        $('#table-select-wrapper').toggleClass('hidden', !isDineIn);
        $('#save-order-btn').toggleClass('hidden', !isDineIn);
            renderCart();
        });

        $('#table-select').on('change', function () {
            renderCart();
        });
    // --- Customer search (AJAX autocomplete) --------------------------------
    let custTimer;
    $('#customer-search-input').on('input', function () {
        clearTimeout(custTimer);
        const q = $(this).val();
        if (q.length < 2) { $('#customer-results').addClass('hidden').empty(); return; }
        custTimer = setTimeout(() => {
            $.ajax({ url: window.routes.customerSearch, data: { q }, dataType: 'json' })
                .done((res) => {
                    const $box = $('#customer-results');
                    if (!res.customers.length) {
                        $box.html('<div class="p-3 text-xs text-gray-400">No matches. You can still bill as a walk-in guest.</div>').removeClass('hidden');
                        return;
                    }
                    $box.html(res.customers.map((c) => `
                        <div class="px-3 py-2 hover:bg-brand-50 cursor-pointer text-sm" data-select-customer="${c.id}" data-name="${escapeHtml(c.name)}">
                            <span class="font-medium">${escapeHtml(c.name)}</span>
                            <span class="text-gray-400 text-xs block">${c.phone}</span>
                        </div>
                    `).join('')).removeClass('hidden');
                });
        }, 300);
    });

    $(document).on('click', '[data-select-customer]', function () {
        $('#customer_id').val($(this).data('select-customer'));
        $('#customer-search-input').val($(this).data('name'));
        $('#customer-results').addClass('hidden').empty();
    });

    // --- Save order (hold / send to table without payment) -----------------
    function collectPayload() {
        return {
            order_id: currentOrderId,
            dining_table_id: $('#order-type-select').val() === 'dine_in' ? $('#table-select').val() : null,
            customer_id: $('#customer_id').val() || null,
            order_type: $('#order-type-select').val(),
            discount: $('#discount-input').val() || 0,
            tax_percent: $('#tax-input').val() || 0,
            note: $('#order-note').val(),
            items: cart.map((i) => ({ product_id: i.product_id, quantity: i.quantity, note: i.note || '' })),
        };
    }

    $('#save-order-btn').on('click', function () {
        if (!cart.length) return;

        if (
            $('#order-type-select').val() !== 'dine_in' ||
            !$('#table-select').val()
        ) {
            toast($, 'Please select a table before saving the order.', 'error');
            return;
        }

        const payload = collectPayload();
        const $btn = $(this).prop('disabled', true).text('Saving...');

        $.ajax({
            url: window.routes.orderSave,
            method: 'POST',
            data: payload,
            dataType: 'json'
        })
        .done((res) => {
            currentOrderId = res.order.id;
            toast($, 'Order saved. You can bill this table anytime.');
            setTimeout(() => window.location.href = window.routes.tables, 600);
        })
        .fail((xhr) => {
            toast(
                $,
                xhr.responseJSON?.message || 'Could not save order. Check the items.',
                'error'
            );
        })
        .always(() => $btn.prop('disabled', false).text('Save Order'));
    });

    // --- Checkout modal ------------------------------------------------------
    $('#checkout-btn').on('click', function () {
        if (!cart.length) return;
        const t = calcTotals();
        $('#modal-total').text(fmt(t.total));
        $('#paid-amount-input').val(t.total.toFixed(2)).trigger('input');
        $('#checkout-modal').removeClass('hidden');
    });
    $('#close-checkout-modal, #checkout-modal-backdrop').on('click', () => $('#checkout-modal').addClass('hidden'));

    $('#paid-amount-input').on('input', function () {
        const t = calcTotals();
        const paid = parseFloat($(this).val()) || 0;
        const change = Math.max(paid - t.total, 0);
        $('#modal-change').text(fmt(change));
    });

    $('#confirm-checkout-btn').on('click', async function () {
        const $btn = $(this).prop('disabled', true).text('Processing...');
        const payload = collectPayload();

        try {
            // First ensure the order is saved (creates it if new), then charge it.
            const saveRes = await $.ajax({ url: window.routes.orderSave, method: 'POST', data: payload, dataType: 'json' });
            currentOrderId = saveRes.order.id;

            const paymentMethod = $('#payment-method-select').val();
            const paidAmount = $('#paid-amount-input').val();

            const checkoutRes = await $.ajax({
                url: `/orders/${currentOrderId}/checkout`,
                method: 'POST',
                data: { payment_method: paymentMethod, paid_amount: paidAmount, discount: $('#discount-input').val(), tax_percent: $('#tax-input').val() },
                dataType: 'json',
            });

            // toast($, 'Payment received. Opening receipt...');
            // window.open(checkoutRes.receipt_url);
            // setTimeout(() => window.location.href = window.routes.tables, 700);
            window.location.href = checkoutRes.receipt_url;
        } catch (xhr) {
            toast($, xhr.responseJSON?.message || 'Checkout failed. Please try again.', 'error');
        } finally {
            $btn.prop('disabled', false).text('Confirm Payment');
        }
    });

    renderCart();
}
