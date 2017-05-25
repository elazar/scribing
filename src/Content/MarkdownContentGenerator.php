<?php

namespace Elazar\Scribing\Content;

use Elazar\Scribing\Filesystem\FileMetadataParser;
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
     * @var FileMetadataParser
     */
    private $parser;

    /**
     * @param Converter $converter
     * @param FileMetadataParser $parser
     */
    public function __construct(
        Converter $converter,
        FileMetadataParser $parser
    ) {
        $this->converter = $converter;
        $this->parser = $parser;
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
            $metadata = $this->parser->parse($content);
            $converted = $this->converter->convertToHtml($content);
            $urlPath = '/' . $pathGenerator->generate($sourceFile->getPathname());
            $filePath = $destinationPath . $urlPath;

            $generated = $engine->render('layout', [
                'title' => $metadata->getTitle(),
                'url' => str_replace('/index.html', '', $urlPath),
                'content' => $converted,
            ]);

            $dirPath = dirname($filePath);
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            file_put_contents($filePath, $generated);
        }
    }    
}
