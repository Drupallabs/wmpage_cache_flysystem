<?php

namespace Drupal\wmpage_cache_flysystem\Storage;

use Drupal\wmpage_cache\Cache;
use Drupal\wmpage_cache\Exception\NoSuchCacheEntryException;
use Drupal\wmpage_cache\Storage\StorageInterface;

class FlySystemStorage implements StorageInterface
{
    /** @var StorageInterface */
    protected $storage;
    /** @var ContentStorage */
    protected $contentStorage;

    public function __construct(
        StorageInterface $storage,
        ContentStorage $contentStorage
    ) {
        $this->storage = $storage;
        $this->contentStorage = $contentStorage;
    }

    public function load($id, $includeBody = true)
    {
        /** @var Cache $item */
        $item = $this->loadMultiple([$id], $includeBody)->current();
        if (!$item || empty($item->getBody())) {
            throw new NoSuchCacheEntryException($id);
        }

        return $item;
    }

    public function loadMultiple(array $ids, $includeBody = true): \Iterator
    {
        foreach ($this->storage->loadMultiple($ids, $includeBody) as $item) {
            $item = $this->contentStorage->readBodyOnFileSystem($item);
            if (!$item) {
                continue;
            }

            yield $item;
        }
    }

    public function set(Cache $item, array $tags)
    {
        return $this->storage->set(
            $this->contentStorage->storeBodyOnFileSystem($item),
            $tags
        );
    }

    public function remove(array $ids)
    {
        $this->storage->remove($ids);
        $this->contentStorage->removeFromFileSystem($ids);
    }

    public function flush()
    {
        $this->storage->flush();
        $this->contentStorage->flushFileSystem();
    }

    public function getByTags(array $tags)
    {
        return $this->storage->getByTags($tags);
    }

    public function getExpired($amount)
    {
        return $this->storage->getExpired($amount);
    }
}
