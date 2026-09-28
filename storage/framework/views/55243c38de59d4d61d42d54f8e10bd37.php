
<div id="refund-order-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 no-print">
    <div id="refund-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="card relative w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Refund Order</h3>
        <p class="text-sm text-gray-400 mb-4">Enter how much to give back. Defaults to the full amount — reduce it for a partial refund.</p>

        <div class="mb-4">
            <label class="label">Refund Amount *</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"><?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?></span>
                <input type="text" id="refund-amount-input" class="input !pl-8" inputmode="decimal" placeholder="0.00">
            </div>
            <p id="refund-amount-error" class="error-text hidden"></p>
        </div>

        <div class="mb-5">
            <label class="label">Reason <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea id="refund-reason-input" rows="2" class="input" placeholder="e.g. Wrong item, customer complaint..." maxlength="255"></textarea>
        </div>

        <div class="flex gap-3">
            <button id="confirm-refund-btn" type="button" class="btn-danger flex-1">Confirm Refund</button>
            <button id="close-refund-modal" type="button" class="btn-secondary flex-1">Cancel</button>
        </div>
    </div>
</div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/orders/partials/_refund_modal.blade.php ENDPATH**/ ?>