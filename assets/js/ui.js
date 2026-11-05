/**
 * ui.js — UI helpers for the Student Registration System
 * BWP501 · Section 5: Styling and Responsiveness
 *
 * Presentation only: no AJAX calls, no database logic.
 * Load AFTER bootstrap.bundle.min.js and BEFORE any teammate scripts.
 *
 * Public API (use it from your own scripts):
 *   UI.toast(text, type)                 → small notification (success | danger | warning | info)
 *   UI.alert(text, type, target)         → inline alert inside #pageAlert (or any element)
 *   UI.setFieldError(input, message)     → mark a field red + show message under it
 *   UI.clearFieldError(input, markValid) → remove the error (optionally mark green)
 *   UI.setLoading(button, true|false)    → spinner + disabled state on a button
 *
 * Automatic behaviours (no code needed, just HTML attributes):
 *   ?msg=<code> in the URL            → shows a message (see MESSAGES below)
 *   form.needs-validation             → Bootstrap 5 validation styles on submit
 *   form[data-loading]                → submit button shows a spinner while sending
 *   [data-toggle-password="#id"]      → show / hide password
 *   input[type=file][data-preview="#img"] → live image preview
 *   [data-autosubmit]                 → submits its form when changed (sort select)
 *   [data-tooltip="text"]             → Bootstrap tooltip
 *   #deleteModal + [data-id][data-name] buttons → fills the confirm dialog
 *   [data-year]                       → current year
 */
(function () {
  'use strict';

  /* ------------------------------------------------------------------
   * Messages shown from the ?msg= query parameter.
   * Backend: redirect with e.g. header('Location: view_students.php?msg=added');
   * ------------------------------------------------------------------ */
  const MESSAGES = {
    added:          { type: 'success', text: 'Student added successfully.' },
    updated:        { type: 'success', text: 'Student updated successfully.' },
    deleted:        { type: 'success', text: 'Student deleted successfully.' },
    registered:     { type: 'success', text: 'Account created — you can sign in now.' },
    logout:         { type: 'info',    text: 'You have been signed out.' },
    invalid:        { type: 'danger',  text: 'Incorrect username/email or password.' },
    exists:         { type: 'danger',  text: 'That username or email is already registered.' },
    mismatch:       { type: 'danger',  text: 'Passwords do not match.' },
    login_required: { type: 'warning', text: 'Please sign in to continue.' },
    not_found:      { type: 'warning', text: 'Student not found.' },
    upload_error:   { type: 'danger',  text: 'The image could not be uploaded. Use JPG, PNG, GIF or WEBP up to 2 MB.' },
    error:          { type: 'danger',  text: 'Something went wrong. Please try again.' }
  };

  const ICONS = {
    success: 'bi-check-circle-fill',
    danger:  'bi-exclamation-octagon-fill',
    warning: 'bi-exclamation-triangle-fill',
    info:    'bi-info-circle-fill'
  };

  const MAX_IMAGE_MB = 2;

  /* ------------------------------------------------------------------
   * Helpers
   * ------------------------------------------------------------------ */
  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const resolve = (el) => (typeof el === 'string' ? $(el) : el);

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (c) => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
  }

  /* ------------------------------------------------------------------
   * Notifications
   * ------------------------------------------------------------------ */
  function toast(text, type = 'success') {
    const area = document.getElementById('toastArea');
    if (!area || !window.bootstrap) { return; }

    const el = document.createElement('div');
    el.className = `toast app-toast app-toast-${type} border-0`;
    el.setAttribute('role', type === 'danger' ? 'alert' : 'status');
    el.setAttribute('aria-live', type === 'danger' ? 'assertive' : 'polite');
    el.setAttribute('aria-atomic', 'true');
    el.innerHTML =
      '<div class="d-flex align-items-center">' +
        `<div class="toast-body"><i class="bi ${ICONS[type] || ICONS.info} me-2"></i>${escapeHtml(text)}</div>` +
        '<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
      '</div>';

    area.appendChild(el);
    el.addEventListener('hidden.bs.toast', () => el.remove());
    new bootstrap.Toast(el, { delay: 4500 }).show();
  }

  function alertBox(text, type = 'danger', target = '#pageAlert') {
    const box = resolve(target);
    if (!box) { toast(text, type); return; }
    box.innerHTML =
      `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-start gap-2" role="alert">` +
        `<i class="bi ${ICONS[type] || ICONS.info} mt-1"></i>` +
        `<div>${escapeHtml(text)}</div>` +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
      '</div>';
  }

  /* ------------------------------------------------------------------
   * Field errors — for AJAX / JS validation written by teammates
   * Works together with Bootstrap's .was-validated styles because it
   * also sets the browser's custom validity message.
   * ------------------------------------------------------------------ */
  function feedbackFor(input) {
    const wrap = input.closest('.field') || input.parentElement;
    return wrap ? wrap.querySelector('.invalid-feedback') : null;
  }

  function setFieldError(input, message) {
    input = resolve(input);
    if (!input) { return; }
    input.setCustomValidity(message || 'Invalid value');
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    input.setAttribute('aria-invalid', 'true');

    const fb = feedbackFor(input);
    if (fb && message) {
      if (fb.dataset.defaultText === undefined) { fb.dataset.defaultText = fb.textContent; }
      fb.textContent = message;
    }
  }

  function clearFieldError(input, markValid = false) {
    input = resolve(input);
    if (!input) { return; }
    input.setCustomValidity('');
    input.classList.remove('is-invalid');
    input.classList.toggle('is-valid', Boolean(markValid));
    input.removeAttribute('aria-invalid');

    const fb = feedbackFor(input);
    if (fb && fb.dataset.defaultText !== undefined) { fb.textContent = fb.dataset.defaultText; }
  }

  /* ------------------------------------------------------------------
   * Button loading state
   * ------------------------------------------------------------------ */
  function setLoading(btn, on = true) {
    btn = resolve(btn);
    if (!btn) { return; }
    if (on) {
      if (btn.dataset.busy === '1') { return; }
      btn.dataset.busy = '1';
      btn.dataset.originalHtml = btn.innerHTML;
      btn.disabled = true;
      const label = btn.dataset.loadingText || 'Please wait…';
      btn.innerHTML = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>${escapeHtml(label)}</span>`;
    } else if (btn.dataset.busy === '1') {
      btn.innerHTML = btn.dataset.originalHtml;
      btn.disabled = false;
      delete btn.dataset.busy;
    }
  }

  /* ------------------------------------------------------------------
   * Init
   * ------------------------------------------------------------------ */
  function showQueryMessage() {
    const params = new URLSearchParams(window.location.search);
    const code = params.get('msg');
    if (!code || !MESSAGES[code]) { return; }

    const { type, text } = MESSAGES[code];
    if (document.getElementById('pageAlert')) { alertBox(text, type); } else { toast(text, type); }

    // Remove ?msg= so a refresh doesn't show it again
    params.delete('msg');
    const qs = params.toString();
    window.history.replaceState(null, '', window.location.pathname + (qs ? `?${qs}` : '') + window.location.hash);
  }

  function highlightActiveNav() {
    if ($('.app-navbar .nav-link.active')) { return; }      // PHP header already set it
    const current = window.location.pathname.split('/').pop() || 'index.php';
    $$('.app-navbar .nav-link').forEach((link) => {
      if (link.getAttribute('href') === current) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });
  }

  function initPasswordToggles() {
    $$('[data-toggle-password]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const input = $(btn.dataset.togglePassword);
        if (!input) { return; }
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', String(show));
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        const icon = btn.querySelector('i');
        if (icon) { icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye'; }
      });
    });
  }

  function initImagePreviews() {
    $$('input[type="file"][data-preview]').forEach((input) => {
      const img = $(input.dataset.preview);
      const nameEl = input.closest('.field') ? input.closest('.field').querySelector('[data-file-name]') : null;
      const original = img ? img.getAttribute('src') : '';

      input.addEventListener('change', () => {
        const file = input.files && input.files[0];
        clearFieldError(input);

        if (!file) {
          if (img) { img.src = original; }
          if (nameEl) { nameEl.textContent = 'No file selected'; }
          return;
        }
        if (!file.type.startsWith('image/')) {
          setFieldError(input, 'Please choose an image file (JPG, PNG, GIF or WEBP).');
          input.value = '';
          return;
        }
        if (file.size > MAX_IMAGE_MB * 1024 * 1024) {
          setFieldError(input, `Image must be ${MAX_IMAGE_MB} MB or smaller.`);
          input.value = '';
          return;
        }
        if (img) { img.src = URL.createObjectURL(file); }
        if (nameEl) { nameEl.textContent = file.name; }
      });
    });
  }

  function initFormValidationStyles() {
    $$('form.needs-validation').forEach((form) => {
      form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          const firstInvalid = form.querySelector(':invalid');
          if (firstInvalid) { firstInvalid.focus({ preventScroll: false }); }
        }
        form.classList.add('was-validated');
      });
    });

    // Errors printed by PHP (class="is-invalid" data-clear-on-input) disappear once the user edits the field
    document.addEventListener('input', (event) => {
      const el = event.target;
      if (el && el.hasAttribute && el.hasAttribute('data-clear-on-input')) {
        el.classList.remove('is-invalid');
        el.removeAttribute('data-clear-on-input');
      }
    });
  }

  function initLoadingButtons() {
    // Runs after the form's own listeners: if nobody cancelled the submit, show the spinner
    document.addEventListener('submit', (event) => {
      const form = event.target;
      if (!form.hasAttribute('data-loading') || event.defaultPrevented) { return; }
      setLoading(form.querySelector('[type="submit"]'), true);
    });
    // Back/forward cache: restore buttons if the page is shown again
    window.addEventListener('pageshow', (event) => {
      if (event.persisted) { $$('[data-busy="1"]').forEach((btn) => setLoading(btn, false)); }
    });
  }

  function initAutoSubmit() {
    $$('[data-autosubmit]').forEach((el) => {
      el.addEventListener('change', () => {
        const form = el.form;
        if (!form) { return; }
        const page = form.querySelector('input[name="page"]');
        if (page) { page.value = '1'; }                       // new sort → back to first page
        if (form.requestSubmit) { form.requestSubmit(); } else { form.submit(); }
      });
    });
  }

  function initDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (!modal) { return; }
    modal.addEventListener('show.bs.modal', (event) => {
      const trigger = event.relatedTarget;
      if (!trigger) { return; }
      const idInput = modal.querySelector('input[name="id"]');
      const nameEl  = modal.querySelector('[data-delete-name]');
      if (idInput) { idInput.value = trigger.dataset.id || ''; }
      if (nameEl)  { nameEl.textContent = trigger.dataset.name || 'this student'; }
    });
  }

  function initTooltips() {
    if (!window.bootstrap) { return; }
    $$('[data-tooltip]').forEach((el) => {
      new bootstrap.Tooltip(el, { title: el.dataset.tooltip, trigger: 'hover focus' });
    });
  }

  function initYear() {
    $$('[data-year]').forEach((el) => { el.textContent = new Date().getFullYear(); });
  }

  document.addEventListener('DOMContentLoaded', () => {
    highlightActiveNav();
    initPasswordToggles();
    initImagePreviews();
    initFormValidationStyles();
    initLoadingButtons();
    initAutoSubmit();
    initDeleteModal();
    initTooltips();
    initYear();
    showQueryMessage();
  });

  window.UI = { toast, alert: alertBox, setFieldError, clearFieldError, setLoading };
})();

