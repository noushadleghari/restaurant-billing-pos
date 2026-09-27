import { toast } from './flash.js';
import { renderServerErrors } from './validate.js';

// Handles the "Add Product" / "Edit Product" page: image preview + inline
// category creation via AJAX (no page reload, new category appears instantly).
export function initProductForm($) {
    const $form = $('#product-form');
    if (!$form.length) return;

    // Live image preview
    $('#product-image-input').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => $('#product-image-preview').attr('src', e.target.result).removeClass('hidden');
        reader.readAsDataURL(file);
    });

    // Inline "+ Add Category" flow
    $('#open-add-category').on('click', function () {
        $('#add-category-panel').removeClass('hidden');
        $('#new-category-name').trigger('focus');
    });
    $('#cancel-add-category').on('click', function () {
        $('#add-category-panel').addClass('hidden');
        $('#new-category-name').val('');
    });

    $('#submit-add-category').on('click', function () {
        const name = $('#new-category-name').val().trim();
        if (name.length < 2) {
            toast($, 'Category name must be at least 2 characters.', 'error');
            return;
        }
        const $btn = $(this).prop('disabled', true).text('Adding...');

        $.ajax({
            url: window.routes.categoriesStore,
            method: 'POST',
            data: { name, color: $('#new-category-color').val() || '#17b167' },
            dataType: 'json',
        }).done(function (res) {
            const opt = new Option(res.category.name, res.category.id, true, true);
            $('#category_id').append(opt).trigger('change');
            $('#add-category-panel').addClass('hidden');
            $('#new-category-name').val('');
            toast($, 'Category added.');
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.errors?.name?.[0] || 'Could not add category.';
            toast($, msg, 'error');
        }).always(function () {
            $btn.prop('disabled', false).text('Add');
        });
    });

    // Submit product form via AJAX so validation errors show instantly without losing state.
    $form.on('submit', function (e) {
        if ($form.find('.input-error').length) return; // client validation already stopped it

        e.preventDefault();
        const formData = new FormData(this);
        if ($form.data('method') === 'PUT') formData.append('_method', 'PUT');

        const $submit = $form.find('[type="submit"]').prop('disabled', true).text('Saving...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
        }).done(function (res) {
            toast($, 'Product saved successfully.');
            setTimeout(() => window.location.href = res.redirect || window.routes.productsIndex, 500);
        }).fail(function (xhr) {
            if (xhr.status === 422) {
                renderServerErrors($, '#product-form', xhr.responseJSON.errors);
                toast($, 'Please fix the highlighted fields.', 'error');
            } else {
                toast($, 'Something went wrong. Please try again.', 'error');
            }
        }).always(function () {
            $submit.prop('disabled', false).text($submit.data('label') || 'Save Product');
        });
    });
}

// Product listing page: live search, category filter, and one-click
// "out of stock" toggle — all via AJAX, no full page reloads.
export function initProductIndex($) {
    const $grid = $('#product-grid-wrapper');
    if (!$grid.length) return;

    let timer;
    function fetchProducts() {
        const params = {
            q: $('#product-search').val(),
            category_id: $('#category-filter').val(),
        };
        $.ajax({ url: window.location.pathname, data: params, dataType: 'json' })
            .done((res) => $grid.html(res.html));
    }

    $('#product-search').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(fetchProducts, 350);
    });
    $('#category-filter').on('change', fetchProducts);

    $grid.on('click', '[data-toggle-availability]', function () {
        const id = $(this).data('toggle-availability');
        const $btn = $(this);
        $.ajax({ url: `/products/${id}/toggle-availability`, method: 'PATCH', dataType: 'json' })
            .done((res) => {
                $btn.closest('[data-product-card]').toggleClass('unavailable', !res.is_available);
                $btn.text(res.is_available ? 'Available' : 'Out of stock');
                toast($, res.is_available ? 'Marked available.' : 'Marked out of stock.');
            });
    });

    $grid.on('click', '[data-delete-product]', function (e) {
        e.preventDefault();
        if (!confirm('Delete this product? This cannot be undone.')) return;
        $(this).closest('form').trigger('submit');
    });
}
