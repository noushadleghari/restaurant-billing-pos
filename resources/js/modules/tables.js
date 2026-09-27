import { toast } from './flash.js';

// The tile-grid table system on /tables (search + status refresh).
export function initTableGrid($) {
    const $wrap = $('#table-grid-wrapper');
    if (!$wrap.length) return;

    let timer;
    $('#table-search').on('input', function () {
        clearTimeout(timer);
        const q = $(this).val();
        timer = setTimeout(() => {
            $.ajax({ url: window.location.pathname, data: { q }, dataType: 'json' })
                .done((res) => $wrap.html(res.html));
        }, 250);
    });

    $wrap.on('click', '[data-table-tile]', function () {
        const id = $(this).data('table-tile');
        window.location.href = `/pos?table=${id}`;
    });
}

// The admin "Manage Tables" CRUD screen.
export function initTableManage($) {
    const $form = $('#table-form');
    if (!$form.length) return;

    $('#add-table-btn').on('click', () => $('#table-modal').removeClass('hidden'));
    $('#close-table-modal, #table-modal-backdrop').on('click', () => $('#table-modal').addClass('hidden'));

    $(document).on('click', '[data-edit-table]', function () {
        const data = $(this).data();
        $('#table-form').attr('action', `/tables/${data.editTable}`);
        $('#table-form input[name=_method]').val('PUT');
        $('#table-name').val(data.name);
        $('#table-capacity').val(data.capacity);
        $('#table-modal-title').text('Edit Table');
        $('#table-modal').removeClass('hidden');
    });

    $(document).on('click', '[data-delete-table]', function (e) {
        e.preventDefault();
        if (!confirm('Remove this table?')) return;
        $(this).closest('form').trigger('submit');
    });
}
