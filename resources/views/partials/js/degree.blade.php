<script>
(function () {
  function disposeBootstrapInstance(el, type) {
    if (typeof bootstrap === 'undefined') return;

    const instance = type === 'tooltip'
      ? bootstrap.Tooltip.getInstance(el)
      : bootstrap.Popover.getInstance(el);

    if (instance) instance.dispose();
  }

  function initDegreePopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.degree-meta-trigger').forEach(function(el) {
      disposeBootstrapInstance(el, 'popover');

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'left',
        container: 'body',
        sanitize: false
      });
    });
  }

  function initDegreeTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-degree-tooltip').forEach(function(el) {
      disposeBootstrapInstance(el, 'tooltip');

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

  function initDegreeUI() {
    initDegreePopovers();
    initDegreeTooltips();
  }

  document.addEventListener('DOMContentLoaded', initDegreeUI);

  if (window.jQuery) {
    jQuery(document).on('draw.dt', function () {
      initDegreeUI();
    });
  }
})();
</script>