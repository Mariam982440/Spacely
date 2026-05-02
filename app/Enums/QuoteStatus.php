<?php
namespace App\Enums;

enum QuoteStatus: string
{
    case Draft    = 'draft';
    case Sent     = 'sent';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Draft    => 'Brouillon',
            self::Sent     => 'Envoyé',
            self::Accepted => 'Accepté',
            self::Rejected => 'Refusé',
        };
    }
}