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
                'label' => 'Quản lý sân golf',
            ],

            // [
            //     'route' => 'doctor.index',
            //     'url'   => '/doctor',
            //     'icon'  => 'tabler-brand-feedly',
            //     'label' => 'Thông Tin Nhân Viên',
            //     'childrens' => [
            //         [
            //             'route' => 'doctor.index',
            //             'url'   => '/doctor',
            //             'icon'  => 'tabler-stethoscope',
            //             'label' => 'Nhân Viên',
            //         ],
            //         [
            //             'route' => 'degree.index',
            //             'url'   => '/degree',
            //             'icon'  => 'tabler-award',
            //             'label' => 'Bằng Cấp',
            //         ],
            //     ],
            // ],

            // [
            //     'route' => 'comment.index',
            //     'url'   => '/comment',
            //     'icon'  => 'tabler-brand-hipchat',
            //     'label' => 'Thông Tin Liên Hệ',
            //     'childrens' => [
            //         [
            //             'route' => 'comment.index',
            //             'url'   => '/comment',
            //             'icon'  => 'tabler-message',
            //             'label' => 'Bình Luận',
            //         ],
            //         [
            //             'route' => 'service-registrations.index',
            //             'url'   => '/service-registrations',
            //             'icon'  => 'tabler-package',
            //             'label' => 'Gói Dịch Vụ',
            //         ],
            //         [
            //             'route' => 'appointment.index',
            //             'url'   => '/appointment',
            //             'icon'  => 'tabler-calendar-time',
            //             'label' => 'Đặt Lịch Hẹn',
            //         ],
            //         [
            //             'route' => 'contact.index',
            //             'url'   => '/contact',
            //             'icon'  => 'tabler-mail',
            //             'label' => 'Liên Hệ',
            //         ],
            //     ],
            // ],

            // [
            //     'route' => 'user.index',
            //     'url'   => '/user',
            //     'icon'  => 'tabler-id',
            //     'label' => 'Quản Lý Tài Khoản',
            //     'roles' => ['admin'],
            //     'childrens' => [
            //         [
            //             'route' => 'user.index',
            //             'url'   => '/user',
            //             'icon'  => 'tabler-users',
            //             'label' => 'Thành Viên',
            //             'roles' => ['admin'],
            //         ],
            //         [
            //             'route' => 'role.index',
            //             'url'   => '/role',
            //             'icon'  => 'tabler-user-check',
            //             'label' => 'Vai Trò',
            //             'roles' => ['admin'],
            //         ],
            //     ],
            // ],

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

            // [
            //     'route' => null,
            //     'url'   => null,
            //     'icon'  => 'tabler-file-description',
            //     'label' => 'Quản Lý Page',
            //     'childrens' => [
            //         [
            //             'route' => 'setting.home',
            //             'url'   => '/setting/home',
            //             'icon'  => 'tabler-home',
            //             'label' => 'Trang Chủ',
            //         ],
            //         [
            //             'route' => 'setting.service',
            //             'url'   => '/setting/service',
            //             'icon'  => 'tabler-server-spark',
            //             'label' => 'Dịch Vụ',
            //         ],
            //         [
            //             'route' => 'setting.contactPage',
            //             'url'   => '/setting/contact-page',
            //             'icon'  => 'tabler-mail',
            //             'label' => 'Liên Hệ',
            //         ],
            //         [
            //             'route' => 'setting.aboutPage',
            //             'url'   => '/setting/about-page',
            //             'icon'  => 'tabler-user-screen',
            //             'label' => 'Giới Thiệu',
            //         ],
            //     ],
            // ],
        ];
    }
}

// 1 baảng role phân quyền in account







// namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\View;
// use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Str;

// class AppServiceProvider extends ServiceProvider
// {
//     public function boot(): void
//     {
//         if (app()->environment('production')) {
//             URL::forceScheme('https');
//         }

//         $host = request()->getHost();

//         if (Str::startsWith($host, 'admin.')) {
//             $base = Str::after($host, 'admin.');
//             URL::defaults(['domain' => $base]);
//         }

//         if (Str::startsWith($host, 'api.')) {
//             $base = Str::after($host, 'api.');
//             URL::defaults(['domain' => $base]);
//         }

//         View::share('menus', $this->menus());
//     }

//     private function menus(): array
//     {
//         return [
//             [
//                 'route' => 'dashboard.index',
//                 'url'   => '/dashboard',
//                 'icon'  => 'tabler-smart-home',
//                 'label' => 'Bảng Điều Khiển',
//             ],
//             [
//                 'route' => 'post.index',
//                 'url'   => '/post',
//                 'icon'  => 'tabler-news',
//                 'label' => 'Bài Viết',
//             ],
//             [
//                 'route' => 'category.index',
//                 'url'   => '/category',
//                 'icon'  => 'tabler-layout-grid',
//                 'label' => 'Danh Mục',
//             ],
//             [
//                 'route' => 'doctor.index',
//                 'url'   => '/doctor',
//                 'icon'  => 'tabler-stethoscope',
//                 'label' => 'Bác Sĩ',
//             ],
//             [
//                 'route' => 'degree.index',
//                 'url'   => '/degree',
//                 'icon'  => 'tabler-medal',
//                 'label' => 'Bằng Cấp',
//             ],
//             [
//                 'route' => 'menu.index',
//                 'url'   => '/menu',
//                 'icon'  => 'tabler-menu-2',
//                 'label' => 'Menu',
//             ],
//             [
//                 'route' => 'comment.index',
//                 'url'   => '/comment',
//                 'icon'  => 'tabler-brand-hipchat',
//                 'label' => 'Bình Luận',
//             ],
//             [
//                 'route' => 'domain.index',
//                 'url'   => '/domain',
//                 'icon'  => 'tabler-world-www',
//                 'label' => 'Domain',
//             ],
//             [
//                 'route' => 'user.index',
//                 'url'   => '/user',
//                 'icon'  => 'tabler-users',
//                 'label' => 'Thành Viên',
//             ],
//             [
//                 'route' => 'setting.index',
//                 'url'   => '/setting',
//                 'icon'  => 'tabler-settings',
//                 'label' => 'Cấu Hình Site',
//                 'childrens' => [
//                     [
//                         'route' => 'setting.index',
//                         'url'   => '/setting',
//                         'icon'  => '',
//                         'label' => 'Cấu Hình Chung',
//                     ],
//                     [
//                         'route' => 'setting.home',
//                         'url'   => '/setting/home',
//                         'icon'  => '',
//                         'label' => 'Cấu Hình Trang Chủ',
//                     ],
//                     [
//                         'route' => 'setting.service',
//                         'url'   => '/setting/service',
//                         'icon'  => '',
//                         'label' => 'Cấu Hình Dịch Vụ',
//                     ],
//                     [
//                         'route' => 'setting.contactPage',
//                         'url'   => '/setting/contact-page',
//                         'icon'  => '',
//                         'label' => 'Cấu Hình Liên Hệ',
//                     ],
//                     [
//                         'route' => 'setting.aboutPage',
//                         'url'   => '/setting/about-page',
//                         'icon'  => '',
//                         'label' => 'Cấu Hình Giới Thiệu',
//                     ],
//                 ]
//             ],
//         ];
//     }
// }
