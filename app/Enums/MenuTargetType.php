<?php

namespace App\Enums;

enum MenuTargetType: string
{
    case CUSTOM = 'custom';
    case CATEGORY = 'category';
    case POST = 'post';

    public static function options(): array
    {
        return [
            self::CUSTOM->value => 'Liên kết tùy chỉnh',
            self::CATEGORY->value => 'Danh mục',
            self::POST->value => 'Bài viết',
        ];
    }
}