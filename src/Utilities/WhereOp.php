<?php

namespace App\Utilities;

enum WhereOp : string
{
    case AND = " AND ";
    case OR = " OR ";
    case NOT = " NOT ";
}