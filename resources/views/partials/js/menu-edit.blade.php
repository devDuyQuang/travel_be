<script>
  document.addEventListener('DOMContentLoaded', function () {
    const topicEl = document.getElementById('menu_topic');
    const partEl = document.getElementById('part_id');

    if (!topicEl || !partEl) return;

    const oldPartId = partEl.dataset.oldPartId || '';

    const placeholderMap = {
      category: 'Chọn danh mục',
      post: 'Chọn bài viết',
    };

    const getPlaceholder = (topic) => placeholderMap[topic] || 'Chọn đường dẫn';

    async function loadTargets(topic, selectedId = '') {
      partEl.disabled = !topic;
      partEl.innerHTML = `<option value="">${getPlaceholder(topic)}</option>`;

      if (!topic) return;

      try {
        const url = "{{ panel_route(module().'.targets') }}" + '?topic=' + encodeURIComponent(topic);

        const response = await fetch(url, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();

        if (!result?.success || !Array.isArray(result.data)) {
          partEl.innerHTML = '<option value="">Không có dữ liệu</option>';
          return;
        }

        result.data.forEach(function (target) {
          const option = document.createElement('option');
          option.value = target.id;
          option.textContent = target.name;

          if (String(selectedId) === String(target.id)) {
            option.selected = true;
          }

          partEl.appendChild(option);
        });
      } catch (error) {
        console.error('Load targets error:', error);
        partEl.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
      }
    }

    topicEl.addEventListener('change', function () {
      loadTargets(this.value);
    });

    loadTargets(topicEl.value, oldPartId);
  });
</script>
