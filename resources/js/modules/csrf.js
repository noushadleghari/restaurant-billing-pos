// Central AJAX setup: attaches CSRF token to every jQuery AJAX request.
export function setupAjaxDefaults($) {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': token.getAttribute('content') },
        });
    }
}
