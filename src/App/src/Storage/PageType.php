<?php

declare(strict_types=1);

namespace App\Storage;

enum PageType: string
{
    case Page    = 'page';
    case Partial = 'partial';
}
