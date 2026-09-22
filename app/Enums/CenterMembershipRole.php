<?php

namespace App\Enums;

enum CenterMembershipRole: string
{
    case Monitor = 'monitor';
    case Center = 'center';
    case Rider = 'rider';
}
