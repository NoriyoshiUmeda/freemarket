<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Extends the \RecursiveDirectoryIterator to support relative paths.
 *
 * @author Victor Berchet <victor@suumit.com>
 */
class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator
{
    /**
     * @var bool
     */
    private $ignoreUnreadableDirs;

    /**
     * @var bool
     */
    private $rewindable;


    private $rootPath;
    private $subPath;
    private $directorySeparator = '/';

    /**
     * @throws \RuntimeException
     */
    public function __construct(string $path, int $flags, bool $ignoreUnreadableDirs = false)
    {
        if ($flags & (self::CURRENT_AS_PATHNAME | self::CURRENT_AS_SELF)) {
            throw new \RuntimeException('This iterator only support returning current as fileinfo.');
        }

        parent::__construct($path, $flags);
        $this->ignoreUnreadableDirs = $ignoreUnreadableDirs;
        $this->rootPath = $path;
        if ('/' !== \DIRECTORY_SEPARATOR && !($flags & self::UNIX_PATHS)) {
            $this->directorySeparator = \DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Return an instance of SplFileInfo with support for relative paths.
     *
     * @return SplFileInfo
     */
    #[\ReturnTypeWillChange]
    public function current()
    {


        if (null === $subPathname = $this->subPath) {
            $subPathname = $this->subPath = $this->getSubPath();
        }
        if ('' !== $subPathname) {
            $subPathname .= $this->directorySeparator;
        }
        $subPathname .= $this->getFilename();

        if ('/' !== $basePath = $this->rootPath) {
            $basePath .= $this->directorySeparator;
        }

        return new SplFileInfo($basePath.$subPathname, $this->subPath, $subPathname);
    }

    /**
     * @param bool $allowLinks
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function hasChildren($allowLinks = false)
    {
        $hasChildren = parent::hasChildren($allowLinks);

        if (!$hasChildren || !$this->ignoreUnreadableDirs) {
            return $hasChildren;
        }

        try {
            parent::getChildren();

            return true;
        } catch (\UnexpectedValueException $e) {

            return false;
        }
    }

    /**
     * @return \RecursiveDirectoryIterator
     *
     * @throws AccessDeniedException
     */
    #[\ReturnTypeWillChange]
    public function getChildren()
    {
        try {
            $children = parent::getChildren();

            if ($children instanceof self) {

                $children->ignoreUnreadableDirs = $this->ignoreUnreadableDirs;


                $children->rewindable = &$this->rewindable;
                $children->rootPath = $this->rootPath;
            }

            return $children;
        } catch (\UnexpectedValueException $e) {
            throw new AccessDeniedException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Do nothing for non rewindable stream.
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        if (false === $this->isRewindable()) {
            return;
        }

        parent::rewind();
    }

    /**
     * Checks if the stream is rewindable.
     *
     * @return bool
     */
    public function isRewindable()
    {
        if (null !== $this->rewindable) {
            return $this->rewindable;
        }

        if (false !== $stream = @opendir($this->getPath())) {
            $infos = stream_get_meta_data($stream);
            closedir($stream);

            if ($infos['seekable']) {
                return $this->rewindable = true;
            }
        }

        return $this->rewindable = false;
    }
}
