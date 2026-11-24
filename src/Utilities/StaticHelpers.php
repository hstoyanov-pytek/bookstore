<?php

namespace App\Utilities;

class StaticHelpers
{
    ///
    /// hrefs = array
    /// [
    ///     'previousHref' => hrefString
    ///     'nextHref' => hrefString
    /// ];
    public static function createPaginationParams(int $page, int $totalPages, array $hrefs) : array
    {
        return array(
            'page' => $page,
            'totalPages' => $totalPages,
            'previousHref'=> $hrefs['previousHref'],
            'nextHref' => $hrefs['nextHref']
        );
    }
}