import { toast } from './flash.js';

/**
 * Cancel (open/unpaid orders) and Refund (completed/paid orders) flows.
 * Shared between Order History and the Receipt page — same two small
 * modals, same short AJAX flow, feature-detected so it's a no-op on any
 * page that doesn't have these modals in the DOM.
 */
export function initOrderActions($) {
    const $cancelModal = $('#cancel-order-modal');
    const $refundModal = $('#refund-order-modal');
    if (!$cancelModal.length && !$refundModal.length) return;

    let targetOrderId = null;

    // --- Cancel -------------------------------------------------------------
    $(document).on('click', '[data-cancel-order]', function () {
        targetOrderId = $(this).data('cancel-order');
        $('#cancel-reason-input').val('');
        $cancelModal.removeClass('hidden');
    });

    $('#close-cancel-modal, #cancel-modal-backdrop').on('click', () => $cancelModal.addClass('hidden'));

    $('#confirm-cancel-btn').on('click', function () {
        if (!targetOrderId) return;
        const $btn = $(this).prop('disabled', true).text('Cancelling...');

        $.ajax({
            url: `/orders/${targetOrderId}/cancel`,
            method: 'POST',
            data: { reason: $('#cancel-reason-input').val() },
            dataType: 'json',
        })
            .done(() => {
                toast($, 'Order cancelled.');
                $cancelModal.addClass('hidden');
                setTimeout(() => window.location.reload(), 500);
            })
            .fail((xhr) => {
                toast($, xhr.responseJSON?.message || 'Could not cancel this order.', 'error');
            })
            .always(() => $btn.prop('disabled', false).text('Cancel Order'));
    });

    // --- Refund ---------------------------------------------------------------
    $(document).on('click', '[data-refund-order]', function () {
        targetOrderId = $(this).data('refund-order');
        const total = $(this).data('total');
        $('#refund-amount-input').val(Number(total).toFixed(2));
        $('#refund-reason-input').val('');
        $('#refund-amount-error').addClass('hidden').text('');
        $refundModal.removeClass('hidden');
    });

    $('#close-refund-modal, #refund-modal-backdrop').on('click', () => $refundModal.addClass('hidden'));

    // Numbers only, in real time
    $('#refund-amount-input').on('keypress', function (e) {
        const char = String.fromCharCode(e.which);
        if (!/[0-9.]/.test(char)) e.preventDefault();
    });

    $('#confirm-refund-btn').on('click', function () {
        if (!targetOrderId) return;

        const amount = parseFloat($('#refund-amount-input').val());
        if (!amount || amount <= 0) {
            $('#refund-amount-error').removeClass('hidden').text('Enter a valid refund amount.');
            return;
        }

        const $btn = $(this).prop('disabled', true).text('Processing...');

        $.ajax({
            url: `/orders/${targetOrderId}/refund`,
            method: 'POST',
            data: { refund_amount: amount, reason: $('#refund-reason-input').val() },
            dataType: 'json',
        })
            .done(() => {
                toast($, 'Refund processed.');
                $refundModal.addClass('hidden');
                setTimeout(() => window.location.reload(), 500);
            })
            .fail((xhr) => {
                const msg = xhr.responseJSON?.errors?.refund_amount?.[0]
                    || xhr.responseJSON?.message
                    || 'Could not process this refund.';
                $('#refund-amount-error').removeClass('hidden').text(msg);
                toast($, msg, 'error');
            })
            .always(() => $btn.prop('disabled', false).text('Confirm Refund'));
    });
}
