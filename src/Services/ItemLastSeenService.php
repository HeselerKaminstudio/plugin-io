<?php

namespace IO\Services;

use IO\Constants\SessionStorageKeys;
use IO\Helper\RuntimeTracker;
use IO\Services\SessionStorageService;

/**
 * Class ItemLastSeenService
 * @package IO\Services
 */
class ItemLastSeenService
{
    use RuntimeTracker;

    const MAX_COUNT = 9;
    private $sessionStorage;
    
    /**
     * ItemLastSeenService constructor.
     * @param \IO\Services\SessionStorageService $sessionStorage
     */
    public function __construct(SessionStorageService $sessionStorage)
    {
        $this->start("constructor");
        $this->sessionStorage = $sessionStorage;
        $this->track("constructor");
    }
    
    /**
     * @param int $maxCount
     */
    public function setLastSeenMaxCount(int $maxCount)
    {
        $this->start("setLastSeenMaxCount");
        $this->sessionStorage->setSessionValue(SessionStorageKeys::LAST_SEEN_MAX_COUNT, $maxCount);
        $this->track("setLastSeenMaxCount");
    }
    
    /**
     * @param int $variationId
     */
    public function setLastSeenItem(int $variationId)
    {
        $this->start("setLastSeenItem");
        $maxCount = $this->sessionStorage->getSessionValue(SessionStorageKeys::LAST_SEEN_MAX_COUNT);
        if(is_null($maxCount))
        {
            $maxCount = self::MAX_COUNT;
        }
        
        $lastSeenItems = $this->sessionStorage->getSessionValue(SessionStorageKeys::LAST_SEEN_ITEMS);
    
        if(is_null($lastSeenItems))
        {
            $lastSeenItems = [];
        }
        
        if(!in_array($variationId, $lastSeenItems))
        {
            if(count($lastSeenItems) >= $maxCount)
            {
                array_pop($lastSeenItems);
            }
            
            array_unshift($lastSeenItems, $variationId);
            $this->sessionStorage->setSessionValue(SessionStorageKeys::LAST_SEEN_ITEMS, $lastSeenItems);
        }
        $this->track("setLastSeenItem");

    }
}