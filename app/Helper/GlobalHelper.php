<?php

namespace App\Helper;

class Normalize
{
    public static function normalizeIds(array $ids): array
    {
        return array_values(array_filter(
            array_map('intval', $ids),
            fn($id) => $id > 0
        ));
    }
}
