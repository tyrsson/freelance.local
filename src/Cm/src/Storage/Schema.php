<?php

declare(strict_types=1);

namespace Cm\Storage;

enum Schema: string
{
    case List        = 'list';
    case ListItems   = 'list_items';
    case Page        = 'page';
    case PageData    = 'page_data';
    case Partial     = 'tpl_partial';
    case PartialData = 'tpl_partial_data';
    case Settings    = 'settings';
    case User        = 'user';
}
