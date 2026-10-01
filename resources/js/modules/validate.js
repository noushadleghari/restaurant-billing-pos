// Lightweight, dependency-free real-time form validation.
// Usage: add data-validate="required|min:2|max:50" (and similar) to any input,
// wrap the input+error text in a container with [data-field], and give the
// error <p> a [data-error] attribute. Call initValidation($, '#form-id') once.

const RULES = {
    required: (v) => v.trim().length > 0 || 'This field is required.',
    email: (v) => !v || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Enter a valid email address.',
    number: (v) => !v || /^-?\d*\.?\d+$/.test(v) || 'Numbers only.',
    positive: (v) => !v || parseFloat(v) > 0 || 'Must be greater than 0.',
    integer: (v) => !v || /^\d+$/.test(v) || 'Whole numbers only.',
    phone: (v) => !v || /^[0-9+\-\s]{7,20}$/.test(v) || 'Enter a valid phone number.',
};

function ruleMin(n) { return (v) => !v || v.trim().length >= n || `Must be at least ${n} characters.`; }
function ruleMax(n) { return (v) => !v || v.trim().length <= n || `Must be at most ${n} characters.`; }
function ruleMaxVal(n) { return (v) => !v || parseFloat(v) <= n || `Must not exceed ${n}.`; }
function ruleMinVal(n) { return (v) => !v || parseFloat(v) >= n || `Must be at least ${n}.`; }

function buildValidators(ruleStr) {
    return ruleStr.split('|').map((r) => {
        const [name, arg] = r.split(':');
        if (name === 'min') return ruleMin(Number(arg));
        if (name === 'max') return ruleMax(Number(arg));
        if (name === 'maxval') return ruleMaxVal(Number(arg));
        if (name === 'minval') return ruleMinVal(Number(arg));
        return RULES[name] || (() => true);
    });
}

function validateField($, $input) {
    const ruleStr = $input.attr('data-validate');
    if (!ruleStr) return true;

    const validators = buildValidators(ruleStr);
    const value = $input.val() ?? '';
    const $field = $input.closest('[data-field]');
    const $error = $field.find('[data-error]');

    for (const validate of validators) {
        const result = validate(String(value));
        if (result !== true) {
            $input.addClass('input-error');
            $error.text(result).removeClass('hidden');
            return false;
        }
    }

    $input.removeClass('input-error');
    $error.addClass('hidden').text('');
    return true;
}

export function initValidation($, formSelector) {
    const $form = $(formSelector);
    if (!$form.length) return;

    const $inputs = $form.find('[data-validate]');

    $inputs.on('input blur change', function () {
        validateField($, $(this));
    });

    $form.on('submit', function (e) {
        let valid = true;
        $inputs.each(function () {
            if (!validateField($, $(this))) valid = false;
        });
        if (!valid) {
            e.preventDefault();
            const $firstError = $form.find('.input-error').first();
            if ($firstError.length) {
                $('html, body').animate({ scrollTop: $firstError.offset().top - 120 }, 250);
                $firstError.trigger('focus');
            }
        }
    });

    // Block non-numeric input in real time on any [data-numeric-only] field —
    // both the keystroke itself (snappy feel) AND the resulting value (catches
    // paste, autofill, and mobile keyboards that don't fire normal keypresses).
    $form.find('[data-numeric-only]').each(function () {
        const $input = $(this);
        const allowDecimal = $input.attr('data-numeric-only') === 'decimal';
        const keyPattern = allowDecimal ? /[0-9.]/ : /[0-9]/;
        const stripPattern = allowDecimal ? /[^0-9.]/g : /[^0-9]/g;

        $input.on('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            if (!keyPattern.test(char)) e.preventDefault();
        });

        $input.on('input', function () {
            let value = $input.val().replace(stripPattern, '');
            if (allowDecimal) {
                // Keep only the first decimal point if more than one was pasted in.
                const firstDot = value.indexOf('.');
                if (firstDot !== -1) {
                    value = value.slice(0, firstDot + 1) + value.slice(firstDot + 1).replace(/\./g, '');
                }
            }
            if (value !== $input.val()) $input.val(value);
        });

        $input.on('paste', function () {
            setTimeout(() => $input.trigger('input'), 0);
        });
    });
}

// Render server-side (Laravel validation) errors onto the same fields, so
// real-time (client) and server validation always look identical to the user.
export function renderServerErrors($, formSelector, errors) {
    const $form = $(formSelector);
    Object.keys(errors).forEach((key) => {
        const $input = $form.find(`[name="${key}"]`);
        const $field = $input.closest('[data-field]');
        $input.addClass('input-error');
        $field.find('[data-error]').text(errors[key][0]).removeClass('hidden');
    });
    const $firstError = $form.find('.input-error').first();
    if ($firstError.length) {
        $('html, body').animate({ scrollTop: $firstError.offset().top - 120 }, 250);
    }
}