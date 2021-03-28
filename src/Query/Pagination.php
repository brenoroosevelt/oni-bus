<?php
declare(strict_types=1);

namespace XBus\Query;

use InvalidArgumentException;

class Pagination
{
    const MIN_PAGE_NUMBER = 1;
    const MIN_PAGE_LIMIT = 1;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $limit;

    public function __construct(int $page, int $limit)
    {
        if ($page < self::MIN_PAGE_NUMBER) {
            throw new InvalidArgumentException(sprintf("Invalid page number (%s).", $page));
        }

        if ($limit < self::MIN_PAGE_LIMIT) {
            throw new InvalidArgumentException(sprintf("Invalid page limit (%s).", $limit));
        }

        $this->page = $page;
        $this->limit = $limit;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
