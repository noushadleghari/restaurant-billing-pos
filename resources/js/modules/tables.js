import { toast } from './flash.js';
import { renderServerErrors } from './validate.js';

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

// The admin "Manage Tables" CRUD screen — fully AJAX: add/edit/delete all
// show a toast on success or failure, and server-side validation errors are
// rendered on the same fields the real-time validation uses.
export function initTableManage($) {
    const $form = $('#table-form');
    if (!$form.length) return;

    const $modal = $('#table-modal');

    function clearFormErrors() {
        $form.find('.input-error').removeClass('input-error');
        $form.find('[data-error]').addClass('hidden').text('');
    }

    function openModal(title, action, method, values = { name: '', capacity: '' }) {
        clearFormErrors();
        $form.attr('action', action);
        $form.find('[name=_method]').val(method);
        $('#table-modal-title').text(title);
        $('#table-name').val(values.name);
        $('#table-capacity').val(values.capacity);
        $modal.removeClass('hidden');
    }

    $('#add-table-btn').on('click', function () {
        openModal('Add Table', window.routes.tablesStore, 'POST');
    });

    $('#close-table-modal, #table-modal-backdrop').on('click', () => $modal.addClass('hidden'));

    $(document).on('click', '[data-edit-table]', function () {
        const data = $(this).data();
        openModal('Edit Table', `/tables/${data.editTable}`, 'PUT', { name: data.name, capacity: data.capacity });
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        if ($form.find('.input-error').length) return; // client-side validation already stopped it

        const $submitBtn = $form.find('[type="submit"]').prop('disabled', true).text('Saving...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST', // Laravel reads the spoofed _method field for PUT
            data: $form.serialize(),
            dataType: 'json',
        })
            .done(function (res) {
                toast($, res.message || 'Table saved.');
                $modal.addClass('hidden');
                setTimeout(() => window.location.reload(), 500);
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    renderServerErrors($, '#table-form', xhr.responseJSON.errors);
                    toast($, 'Please fix the highlighted fields.', 'error');
                } else {
                    toast($, xhr.responseJSON?.message || 'Could not save this table. Please try again.', 'error');
                }
            })
            .always(function () {
                $submitBtn.prop('disabled', false).text('Save');
            });
    });

    $(document).on('click', '[data-delete-table]', function (e) {
        e.preventDefault();
        if (!confirm('Remove this table?')) return;

        const $row = $(this).closest('tr');
        const action = $(this).closest('form').attr('action');

        $.ajax({ url: action, method: 'POST', data: { _method: 'DELETE' }, dataType: 'json' })
            .done(function (res) {
                toast($, res.message || 'Table removed.');
                $row.fadeOut(200, () => $row.remove());
            })
            .fail(function (xhr) {
                toast($, xhr.responseJSON?.message || 'Could not remove this table.', 'error');
            });
    });
}