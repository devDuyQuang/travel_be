<script>
(function () {
  function initDoctorUI() {
    if (typeof bootstrap === 'undefined') return;

    document.querySelectorAll('.doctor-meta-trigger').forEach(function(el) {
      const oldPopover = bootstrap.Popover.getInstance(el);
      if (oldPopover) oldPopover.dispose();

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: 'left',
        container: 'body',
        sanitize: false
      });
    });

    document.querySelectorAll('.js-doctor-tooltip').forEach(function(el) {
      const oldTooltip = bootstrap.Tooltip.getInstance(el);
      if (oldTooltip) oldTooltip.dispose();

      new bootstrap.Tooltip(el, {
        trigger: 'hover',
        placement: 'top',
        container: 'body'
      });
    });
  }

  document.addEventListener('DOMContentLoaded', initDoctorUI);

  if (window.jQuery) {
    jQuery(document).on('draw.dt', function () {
      initDoctorUI();
    });
  }
})();
</script>