<script>
document.addEventListener('DOMContentLoaded', function () {
  const topicEl = document.getElementById('menu_topic');
  const partEl = document.getElementById('part_id');
  const targetWrap = document.getElementById('menu-target-wrap');
  const customWrap = document.getElementById('menu-custom-path-wrap');
  const customPathEl = document.getElementById('custom_path');

  if (!topicEl || !partEl) return;

  const oldPartId = partEl.dataset.oldPartId || '';
  const customTopic = 'custom';

  const placeholderMap = {
    category: 'Chọn danh mục',
    post: 'Chọn bài viết',
  };

  const getPlaceholder = (topic) => placeholderMap[topic] || 'Chọn đường dẫn';

  function syncTopicUI(topic) {
    const isCustom = topic === customTopic;

    if (targetWrap) {
      targetWrap.style.display = isCustom ? 'none' : '';
    }

    if (customWrap) {
      customWrap.style.display = isCustom ? '' : 'none';
    }

    partEl.disabled = isCustom || !topic;

    if (customPathEl) {
      customPathEl.disabled = !isCustom;
    }

    if (isCustom) {
      partEl.value = '';
      partEl.innerHTML = '<option value="">Không cần chọn dữ liệu</option>';
    }
  }

  async function loadTargets(topic, selectedId = '') {
    syncTopicUI(topic);

    if (!topic || topic === customTopic) return;

    partEl.disabled = true;
    partEl.innerHTML = `<option value="">Đang tải...</option>`;

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

      partEl.innerHTML = `<option value="">${getPlaceholder(topic)}</option>`;

      if (!result?.success || !Array.isArray(result.data) || result.data.length === 0) {
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

      partEl.disabled = false;
    } catch (error) {
      console.error('Load targets error:', error);
      partEl.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
      partEl.disabled = false;
    }
  }

  topicEl.addEventListener('change', function () {
    loadTargets(this.value);
  });

  loadTargets(topicEl.value, oldPartId);
});
</script>