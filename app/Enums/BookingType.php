<?php

namespace App\Enums;

enum BookingType: string
{
    case Tour = 'tour';
    case Attraction = 'attraction';
    case Hotel = 'hotel';
    case Transport = 'transport';
    case TeeTime = 'tee_time';
    case GolfRoom = 'golf_room';
    case Consultation = 'consultation';

    public function label(): string
    {
        return match ($this) {
            self::Tour => 'Tour',
            self::Attraction => 'Vé tham quan',
            self::Hotel => 'Khách sạn',
            self::Transport => 'Thuê xe & đưa đón',
            self::TeeTime => 'Tee time',
            self::GolfRoom => 'Phòng golf 3D',
            self::Consultation => 'Tư vấn',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromLayout(?string $layoutKey): self
    {
        return match ($layoutKey) {
            'tee_time' => self::TeeTime,
            'accommodation' => self::Hotel,
            'transport' => self::Transport,
            'attraction' => self::Attraction,
            'tour' => self::Tour,
            default => self::Consultation,
        };
    }
}
