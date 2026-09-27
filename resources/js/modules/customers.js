import { toast } from './flash.js';

export function initCustomerIndex($) {
    const $wrap = $('#customer-table-wrapper');
    if (!$wrap.length) return;

    let timer;
    $('#customer-search').on('input', function () {
        clearTimeout(timer);
        const q = $(this).val();
        timer = setTimeout(() => {
            $.ajax({ url: window.location.pathname, data: { q }, dataType: 'json' })
                .done((res) => $wrap.html(res.html));
        }, 300);
    });

    $wrap.on('click', '[data-delete-customer]', function (e) {
        e.preventDefault();
        if (!confirm('Remove this customer?')) return;
        $(this).closest('form').trigger('submit');
    });
}
