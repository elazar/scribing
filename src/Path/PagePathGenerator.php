<?php

namespace Elazar\Scribing\Path;

class PagePathGenerator implements PathGenerator
{
    /**
     * @inheritdoc
     */
    public function generate($sourceFile)
    {
        $destinationPath = pathinfo($sourceFile, PATHINFO_FILENAME);
        return $destinationPath . '/index.html';
    }
}
