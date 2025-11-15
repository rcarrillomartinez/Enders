<?php
class Pagination {
    public static function paginarArray(array $data, int $itemsPerPage, int $page) {
        $start = ($page - 1) * $itemsPerPage;
        return array_slice($data, $start, $itemsPerPage);
    }
}
?>
