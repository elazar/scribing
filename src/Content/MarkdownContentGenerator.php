<?php

namespace Elazar\Scribing\Content;

use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use Elazar\Scribing\Path\PathGenerator;
use League\CommonMark\Converter;
use League\Plates\Engine;

class MarkdownContentGenerator
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @param Converter $converter
     */
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param MarkdownFileFilterIterator $sourceFiles
     * @param Engine $engine
     * @param string $destinationPath
     * @param PathGenerator $pathGenerator
     */
    public function generate(
        MarkdownFileFilterIterator $sourceFiles,
        Engine $engine,
        $destinationPath,
        PathGenerator $pathGenerator
    ) {
        foreach ($sourceFiles as $sourceFile) {
            $content = file_get_contents($sourceFile->getPathname());
            $converted = $this->converter->convertToHtml($content);
            $generated = $engine->render('layout', ['content' => $converted]);
            $filePath = $destinationPath . '/' . $pathGenerator->generate($sourceFile->getPathname());
            $dirPath = dirname($filePath);
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            file_put_contents($filePath, $generated);
        }
    }    
}
