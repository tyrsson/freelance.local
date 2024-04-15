<?php

declare(strict_types=1);

namespace Cm\Storage;

enum PrimaryKey: string
{
    case List        = 'id';
    case ListItems   = 'id';
    case Page        = 'id';
    case PageData    = 'id';
    case Partial     = 'id';
    case PartialData = 'id';
    case Settings    = 'id';
    case User        = 'id';
}
