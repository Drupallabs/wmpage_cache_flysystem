<?php

namespace Drupal\wmpage_cache_flysystem\Storage;

use Drupal\wmpage_cache\Cache;
use League\Flysystem\FilesystemInterface;

class ContentStorage
{
    /** @var FilesystemInterface */
    protected $fs;
    /** @var string */
    protected $directory;

    public function __construct(
        FilesystemInterface $fs,
        string $directory
    ) {
        $this->fs = $fs;
        $this->directory = trim($directory, "\\/ \t\n\r\0\x0B") . '/';
    }

    public function storeBodyOnFileSystem(Cache $cache): Cache
    {
        if (!$cache->getBody()) {
            return $cache;
        }

        $path = $this->directory . $cache->getId();

        $this->fs->put(
            $path,
            $cache->getBody()
        );

        return new Cache(
            $cache->getId(),
            $cache->getUri(),
            $cache->getMethod(),
            $path,
            $cache->getHeaders(),
            $cache->getExpiry()
        );
    }

    public function readBodyOnFileSystem(Cache $cache): ?Cache
    {
        if (!$cache->getBody()) {
            return $cache;
        }

        try {
            $body = $this->fs->read($cache->getBody());
        } catch (\Exception $e) {
            return null;
        }

        return new Cache(
            $cache->getId(),
            $cache->getUri(),
            $cache->getMethod(),
            $body,
            $cache->getHeaders(),
            $cache->getExpiry()
        );
    }

    public function removeFromFileSystem(array $ids): void
    {
        foreach ($ids as $id) {
            try {
                $this->fs->delete($this->directory . $id);
            } catch (\Exception $e) {
            }
        }
    }

    public function flushFileSystem(): void
    {
        try {
            $this->fs->deleteDir($this->directory);
        } catch (\Exception $e) {
        }
    }
}
