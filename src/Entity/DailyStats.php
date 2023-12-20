<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Action\NotFoundAction;
use Symfony\Component\Serializer\Annotation\Groups;
use App\ApiPlatform\DailyStatsDateFilter;

/**
 * @ApiResource(
 *    normalizationContext={"groups"={"daily-stats:read"}},
 *    denormalizationContext={"groups"={"daily-stats:write"}},
 *    paginationItemsPerPage=7,
 *    itemOperations={
 *        "get",
 *        "put",
 *    },
 *    collectionOperations={"get"}
 * )
 * @ApiFilter(DailyStatsDateFilter::class, arguments={"throwOnInvalid"=true})
 */
class DailyStats
{
    /**
     * @Groups({"daily-stats:read"})
     */
    public $date;

    /**
     * @Groups({"daily-stats:read", "daily-stats:write"})
     */    
    public $totalVisitors;

    /**
     * @var array<CheeseListing>|CheeseListing[]
     * @Groups({"daily-stats:read"})
     */    
    public $mostPopularListings;

    public function __construct(\DateTimeInterface $date, int $totalVisitors, array $mostPopularListings)
    {
        $this->date = $date;
        $this->totalVisitors = $totalVisitors;
        $this->mostPopularListings = $mostPopularListings;
    }

    /**
     * @ApiProperty(identifier=true)
     */
    public function getDateString(): string
    {
      return $this->date->format('Y-m-d');
    }
}