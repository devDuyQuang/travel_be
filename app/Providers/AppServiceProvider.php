<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $host = request()->getHost();


        // if (Str::startsWith($host, 'admin.')) { // đổi router từ admin qua cms
        //     $base = Str::after($host, 'admin.');
        //     URL::defaults(['domain' => $base]);
        // }

        if (Str::startsWith($host, 'cms.')) { // đổi router từ admin qua cms
            $base = Str::after($host, 'cms.');
            URL::defaults(['domain' => $base]);
        }

        if (Str::startsWith($host, 'api.')) {
            $base = Str::after($host, 'api.');
            URL::defaults(['domain' => $base]);
        }

        View::share('menus', $this->menus());
    }

    private function menus(): array
    {
        return [
            [
                'route' => 'dashboard.index',
                'url'   => '/dashboard',
                'icon'  => 'tabler-smart-home',
                'label' => 'Bảng Điều Khiển',
            ],

            [
                'route' => 'post.index',
                'url'   => '/post',
                'icon'  => 'tabler-news',
                'label' => 'Quản Lý Bài Viết',
                'childrens' => [
                    [
                        'route' => 'post.index',
                        'url'   => '/post',
                        'icon'  => 'tabler-table',
                        'label' => 'Bảng Dữ Liệu',
                    ],
                    [
                        'route' => 'tag.index',
                        'url'   => '/tag',
                        'icon'  => 'tabler-category',
                        'label' => 'Thẻ bài viết',
                    ],
                ],
            ],
            [
                        'route' => 'category.index',
                        'url'   => '/category',
                        'icon'  => 'tabler-category',
                        'label' => 'Danh Mục',
                    ],
            [
                'route' => 'product.index',
                'url'   => '/product',
                'icon'  => 'tabler-package',
                'label' => 'Quản Lý Sản Phẩm',
            ],
            [
                'route' => 'booking.index',
                'url'   => '/booking',
                'icon'  => 'tabler-calendar-time',
                'label' => 'Quản lý Booking',
            ],
            [
                'route' => 'order.index',
                'url'   => '/order',
                'icon'  => 'tabler-package',
                'label' => 'Quản lý Đơn hàng',
            ],
            [
                'route' => 'team-member.index',
                'url'   => '/team-member',
                'icon'  => 'tabler-users',
                'label' => 'Quản Lý Nhân Viên',
            ],
            [
                'route' => 'faq.index',
                'url'   => '/faq',
                'icon'  => 'tabler-message',
                'label' => 'Quản Lý FAQ',
            ],
              [
                'route' => 'user.index',
                'url'   => '/user',
                'icon'  => 'tabler-id',
                'label' => 'Quản Lý Tài Khoản',
                'childrens' => [
                    [
                        'route' => 'user.index',
                        'url'   => '/user',
                        'icon'  => 'tabler-users',
                        'label' => 'Thành Viên',
                    ],
                    [
                        'route' => 'role.index',
                        'url'   => '/role',
                        'icon'  => 'tabler-user-check',
                        'label' => 'Vai Trò',
                    ],
                ],
            ],
            [
                'route' => null,
                'url'   => null,
                'icon'  => 'tabler-assembly',
                'label' => 'Cấu Hình Site',
                'childrens' => [
                    [
                        'route' => 'setting.index',
                        'url'   => '/setting',
                        'icon'  => 'tabler-settings',
                        'label' => 'Cấu Hình Chung',
                    ],
                    [
                        'route' => 'menu.index',
                        'url'   => '/menu',
                        'icon'  => 'tabler-menu-2',
                        'label' => 'Menu',
                    ],
                ],
            ],

            [
                'route' => null,
                'url'   => null,
                'icon'  => 'tabler-file-description',
                'label' => 'Quản Lý Page',
                'childrens' => [
                    [
                        'route' => 'setting.home',
                        'url'   => '/setting/home',
                        'icon'  => 'tabler-home',
                        'label' => 'Trang Chủ',
                    ],
                    [
                        'route' => 'setting.service',
                        'url'   => '/setting/service',
                        'icon'  => 'tabler-server-spark',
                        'label' => 'Dịch Vụ',
                    ],
                    [
                        'route' => 'setting.contactPage',
                        'url'   => '/setting/contact-page',
                        'icon'  => 'tabler-mail',
                        'label' => 'Liên Hệ',
                    ],
                    [
                        'route' => 'setting.aboutPage',
                        'url'   => '/setting/about-page',
                        'icon'  => 'tabler-user-screen',
                        'label' => 'Giới Thiệu',
                    ],
                    [
                        'route' => null,
                        'url'   => '/setting/page/blog',
                        'icon'  => 'tabler-news',
                        'label' => 'Tin tức',
                    ],
                    [
                        'route' => null,
                        'url'   => '/setting/page/shop',
                        'icon'  => 'tabler-package',
                        'label' => 'Cửa hàng',
                    ],
                    [
                        'route' => null,
                        'url'   => '/setting/page/faq',
                        'icon'  => 'tabler-message',
                        'label' => 'Câu hỏi thường gặp',
                    ],
                ],
            ],
        ];
    }
}
