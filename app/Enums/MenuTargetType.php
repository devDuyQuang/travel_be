<?php

namespace App\Enums;

enum MenuTargetType: string
{
    case CATEGORY = 'category';
    case POST = 'post';

    public static function options(): array
    {
        return [
            self::CATEGORY->value => 'Danh mục',
            self::POST->value => 'Bài viết',
        ];
    }
}
