<?php
declare(strict_types=1);

namespace XBus\Query;

class Pagination
{
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
        assert(($page < 1), sprintf("Invalid page number (%s).", $page));
        assert(($limit < 1), sprintf("Invalid page limit (%s).", $limit));
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
