import $ from 'jquery';
window.$ = window.jQuery = $;

import { setupAjaxDefaults } from './modules/csrf.js';
import { initFlash } from './modules/flash.js';
import { initValidation } from './modules/validate.js';
import { initProductForm, initProductIndex } from './modules/products.js';
import { initCustomerIndex } from './modules/customers.js';
import { initTableGrid, initTableManage } from './modules/tables.js';
import { initPOS } from './modules/pos.js';
import { initReportsChart } from './modules/reports.js';
import { initSettingsForm } from './modules/settings.js';

$(function () {
    setupAjaxDefaults($);
    initFlash($);

    // Real-time validation is wired per-form; harmless no-op if form absent.
    initValidation($, '#product-form');
    initValidation($, '#customer-form');
    initValidation($, '#login-form');
    initValidation($, '#settings-form');

    // Feature-detect which page we're on and boot only what's needed.
    initProductForm($);
    initProductIndex($);
    initCustomerIndex($);
    initTableGrid($);
    initTableManage($);
    initPOS($);
    initReportsChart($);
    initSettingsForm($);
});
