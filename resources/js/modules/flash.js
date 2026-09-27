// Auto-dismiss flash/toast messages after a few seconds.
export function initFlash($) {
    const $flash = $('[data-flash]');
    if (!$flash.length) return;

    setTimeout(() => {
        $flash.fadeOut(300, function () { $(this).remove(); });
    }, 3500);

    $flash.on('click', '[data-flash-close]', function () {
        $(this).closest('[data-flash]').fadeOut(200, function () { $(this).remove(); });
    });
}

// Simple reusable toast, used by AJAX success/error handlers across the app.
export function toast($, message, type = 'success') {
    const colors = {
        success: 'bg-brand-600',
        error: 'bg-red-600',
        info: 'bg-gray-800',
    };
    const $el = $(`
        <div class="fixed top-5 right-5 z-[100] ${colors[type]} text-white text-sm font-medium
                    px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 animate-[fadeIn_.2s]">
            <span>${message}</span>
        </div>
    `);
    $('body').append($el);
    setTimeout(() => $el.fadeOut(300, () => $el.remove()), 3000);
}
