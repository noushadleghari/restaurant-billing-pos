export function initSettingsForm($) {
    const $form = $('#settings-form');
    if (!$form.length) return;

    $('#logo-input').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => $('#logo-preview').attr('src', e.target.result).removeClass('hidden');
        reader.readAsDataURL(file);
    });
}
