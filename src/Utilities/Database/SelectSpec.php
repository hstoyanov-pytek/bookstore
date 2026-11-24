<?php

namespace App\Utilities\Database;

enum SelectSpec : string
{
    case DEFAULT = "";
    case COUNT = " COUNT ";
    case DISTINCT = " DISTINCT ";
}
