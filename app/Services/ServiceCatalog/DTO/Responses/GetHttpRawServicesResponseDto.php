<?php

namespace App\Services\ServiceCatalog\DTO\Responses;

final readonly class GetHttpRawServicesResponseDto
{
    public function __construct(
        private array $data,
        private int $page,
        private int $pageSize,
        private int $totalCount,
    ) {}

    public function getData(): array
    {
        return $this->data;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
