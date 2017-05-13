<?php

namespace Elazar\Scribing\Path;

interface PathGenerator
{
    /**
     * @param string $sourceFile
     * @return string Generated path
     */
    public function generate($sourceFile);
}
