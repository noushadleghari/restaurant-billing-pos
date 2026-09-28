{{-- Cancel modal: for OPEN (unpaid) orders only. Reason is optional. --}}
<div id="cancel-order-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 no-print">
    <div id="cancel-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="card relative w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Cancel Order</h3>
        <p class="text-sm text-gray-400 mb-4">This order hasn't been paid yet. Cancelling it frees up the table and removes it from active orders.</p>

        <div class="mb-5">
            <label class="label">Reason <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea id="cancel-reason-input" rows="2" class="input" placeholder="e.g. Guest left, order entered by mistake..." maxlength="255"></textarea>
        </div>

        <div class="flex gap-3">
            <button id="confirm-cancel-btn" type="button" class="btn-danger flex-1">Cancel Order</button>
            <button id="close-cancel-modal" type="button" class="btn-secondary flex-1">Keep Order</button>
        </div>
    </div>
</div>
