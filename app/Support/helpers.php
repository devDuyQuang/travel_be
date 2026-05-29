<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route as Router;
use Illuminate\Support\Facades\Lang;

/**
 * Gọi route trong admin; tự thêm 'admin.' nếu tồn tại.
 * Ví dụ: panel_panel_route(module().'.index', ['id' => 1])
 */
if (! function_exists('panel_route')) {
    function panel_route(string $name, $params = [], bool $absolute = true)
    {
        if (!is_array($params)) {
            $params = ['id' => $params];
        }

        $adminName = 'admin.' . $name;

        if (Router::has($adminName)) {
            return route($adminName, $params, $absolute);
        }
        if (Router::has($name)) {
            return route($name, $params, $absolute);
        }

        throw new InvalidArgumentException("Route [$adminName] or [$name] not defined.");
    }
}

if (! function_exists('page_title')) {
    function page_title(): string
    {
        $routeName = optional(request()->route())->getName();
        $key = $routeName ? Str::before($routeName, '.') : request()->segment(1);
        if ($key && $key === 'admin') {
            $rest = Str::after($routeName, 'admin.');
            $key = $rest ? Str::before($rest, '.') : request()->segment(1);
        }

        $key = Str::slug((string) $key);

        $title = __("pages.$key");
        if ($title === "pages.$key") {
            $title = Lang::get("pages.$key", [], 'vi');
        }
        if ($title === "pages.$key" || $title === null) {
            $title = Str::headline($key);
        }

        return (string) $title;
    }
}

if (! function_exists('module')) {
    function module(): ?string {
        return request()->segment(1);
    }
}

if (! function_exists('locations')) {
    function locations(): array {
        return [
            // Admin area
            // 'admin'           => 'Khu vực Admin (tổng quát)',
            // 'admin_sidebar'   => 'Admin Sidebar',
            // 'admin_topbar'    => 'Admin Topbar',
            // 'admin_footer'    => 'Admin Footer',

            // Site header / navigation
            'header'          => 'Header (trên cùng)',
            'header_left'     => 'Header - Trái',
            'header_center'   => 'Header - Giữa',
            'header_right'    => 'Header - Phải',
            'navbar'          => 'Navbar chính',
            'topbar'          => 'Topbar (thanh trên cùng)',

            // Sidebars (frontend)
            'sidebar'         => 'Sidebar (chung)',
            'sidebar_left'    => 'Sidebar Trái',
            'sidebar_right'   => 'Sidebar Phải',

            // Mobile
            'mobile_menu'     => 'Menu Mobile',
            'mobile_bottom'   => 'Thanh đáy Mobile',

            // Footer
            'footer'          => 'Footer (chân trang)',
            'footer_primary'  => 'Footer - Cột chính',
            'footer_secondary'=> 'Footer - Cột phụ',
            'footer_legal'    => 'Footer - Điều khoản/Bản quyền',

            // Account/User
            // 'account_menu'    => 'Menu Tài khoản',
            // 'user_menu'       => 'Menu Người dùng (avatar dropdown)',
            // 'profile_menu'    => 'Menu Hồ sơ',

            // Khác
            // 'breadcrumb'      => 'Breadcrumb',
            // 'quick_links'     => 'Liên kết nhanh',
            // 'cta_bar'         => 'Thanh CTA (kêu gọi hành động)',
        ];
    }
}


if (! function_exists('recursive')) {
    /**
     * Tạo danh sách option (value/label) từ cây category.
     *
     * @param  iterable     $items     Collection/array các item có id, name, parent_id
     * @param  int|null     $selected  Id đang được chọn (tuỳ chọn)
     * @param  int|null     $exclude   Id cần loại trừ (loại cả subtree)
     * @param  int|null     $parent    Gốc cần bắt đầu (mặc định null)
     * @param  int          $level     Độ sâu (đừng truyền khi gọi)
     * @return array<int, array{value:int,label:string,selected?:bool}>
     */
    function recursive(iterable $items, ?int $selected = null, ?int $exclude = null, ?int $parent = null, int $level = 0): array
    {
        // 1) Chuẩn hoá node
        $nodes = [];
        foreach ($items as $it) {
            $nodes[] = [
                'id'        => is_array($it) ? (int)$it['id']        : (int)$it->id,
                'name'      => is_array($it) ? (string)$it['name']   : (string)$it->name,
                'parent_id' => is_array($it)
                    ? ($it['parent_id'] ?? null)
                    : ($it->parent_id   ?? null),
            ];
        }

        // 2) Map parent_id -> children
        $childrenMap = [];
        foreach ($nodes as $n) {
            $pid = $n['parent_id'] ?? null;
            $childrenMap[$pid][] = $n;
        }

        // 3) DFS tạo options
        $options = [];

        $walk = function ($pid, $lvl) use (&$walk, &$options, $childrenMap, $selected, $exclude) {
            if (empty($childrenMap[$pid])) {
                return;
            }

            // Có thể sort theo name nếu muốn (nếu input chưa sort)
            // usort($childrenMap[$pid], fn($a, $b) => strnatcasecmp($a['name'], $b['name']));

            foreach ($childrenMap[$pid] as $node) {
                // Bỏ cả subtree nếu gặp exclude
                if ($exclude !== null && (int)$node['id'] === (int)$exclude) {
                    continue;
                }

                $indent = str_repeat('|-----', max(0, $lvl));
                $label  = ($indent ? $indent.' ' : '') . $node['name'];

                $row = [
                    'value' => $node['id'],
                    'label' => $label,
                ];

                // (tuỳ chọn) đánh dấu selected ở option — component hiện không cần key này
                if ($selected !== null && (int)$node['id'] === (int)$selected) {
                    $row['selected'] = true;
                }

                $options[] = $row;

                // duyệt con
                $walk($node['id'], $lvl + 1);
            }
        };

        $walk($parent, $level);

        return $options;
    }
}


if (! function_exists('build_tree')) {
    function build_tree(iterable $items, $rootParent = null): array
    {
        $byId = [];
        $byParent = [];

        foreach ($items as $it) {
            $isArray  = is_array($it);

            $id       = (int)   ($isArray ? ($it['id']        ?? 0)  : ($it->id        ?? 0));
            $name     = (string)($isArray ? ($it['name']      ?? '') : ($it->name      ?? ''));
            $pid      =          $isArray ? ($it['parent_id'] ?? null) : ($it->parent_id ?? null);
            $status   = (int)   ($isArray ? ($it['status']    ?? 1)  : ($it->status    ?? 1));
            $sort     = (int)   ($isArray ? ($it['sort']      ?? 1)  : ($it->sort      ?? 1));
            $location = (string)($isArray ? ($it['location']  ?? '') : ($it->location  ?? ''));
            $path     = (string)($isArray ? ($it['path']      ?? '') : ($it->path      ?? '')); // ⬅️ thêm path

            $byId[$id] = [
                'id'        => $id,
                'name'      => $name,
                'parent_id' => $pid,
                'status'    => $status,
                'sort'      => $sort,
                'location'  => $location,
                'path'      => $path,      // ⬅️ lưu path
                'children'  => [],
            ];

            $byParent[$pid][] = $id;
        }

        // Gắn children
        foreach ($byId as $id => &$node) {
            foreach ($byParent[$id] ?? [] as $cid) {
                $node['children'][] = &$byId[$cid];
            }
        } unset($node);

        // Lấy các root theo parent_id chỉ định
        $roots = [];
        foreach ($byParent[$rootParent] ?? [] as $rid) {
            $roots[] = $byId[$rid];
        }

        return $roots;
    }
}

if (! function_exists('normalize_image_url')) {
    /**
     * Chuẩn hoá đường dẫn ảnh về dạng absolute URL.
     *
     * @param  string|null $image   Giá trị lưu trong DB (có thể là absolute, storage/..., uploads/..., basename)
     * @param  string      $module  Tên module (để ghép path khi chỉ có basename)
     * @return string|null          Absolute URL hoặc null nếu rỗng
     */
    function normalize_image_url(?string $image, string $module): ?string
    {
        if (empty($image)) {
            return null;
        }

        // Loại bỏ slash đầu để tiện xử lý
        $img = ltrim($image, '/');

        // 1) Đã là absolute URL -> trả luôn
        if (Str::startsWith($img, ['http://', 'https://'])) {
            return $img;
        }

        // 2) Nếu người dùng lỡ đẩy 'public/' vào DB, chuẩn hoá về phần sau
        if (Str::startsWith($img, ['public/'])) {
            $img = Str::after($img, 'public/'); // → 'uploads/...'
        }

        // 3) Nếu là 'storage/...' (public symlink), dùng asset() để ra absolute
        if (Str::startsWith($img, ['storage/'])) {
            return url_to_absolute($img); // asset('storage/...')
        }

        // 4) Nếu là 'uploads/...': kiểm tra public_path trước, nếu có thì dùng asset()
        //    (file được move() vào public/ trực tiếp, không qua Storage disk)
        if (Str::startsWith($img, ['uploads/'])) {
            if (file_exists(public_path($img))) {
                return url_to_absolute(asset($img));
            }
            // fallback: thử qua Storage symlink
            return url_to_absolute(Storage::url($img));
        }

        // 5) Còn lại coi như basename -> ghép 'uploads/{module}/{basename}'
        $path = "uploads/{$module}/{$img}";
        $url  = Storage::url($path); // exists hay không vẫn trả URL
        return url_to_absolute($url);
    }
}

if (! function_exists('url_to_absolute')) {
    /**
     * Đảm bảo URL là absolute (kể cả khi Storage::url() trả '/storage/...').
     */
    function url_to_absolute(string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://', '//'])) {
            return $url;
        }
        return asset(ltrim($url, '/'));
    }
}
