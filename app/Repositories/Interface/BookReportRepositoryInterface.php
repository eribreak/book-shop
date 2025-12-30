<?php

namespace App\Repositories\Interface;

interface BookReportRepositoryInterface
{
    public function getBorrowedQuantityByMonth(int $year): array;

    public function getTopBorrowers(int $limit = 10): array;

    public function getTopBorrowedBooks(int $limit = 10): array;

    public function getBooksCountByCategory(): array;

    public function getTopWishlistedBooks(int $limit = 30): array;
}
