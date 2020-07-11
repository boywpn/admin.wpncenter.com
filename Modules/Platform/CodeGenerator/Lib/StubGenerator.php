<?php


namespace Modules\Platform\CodeGenerator\Lib;

/**
 * Stub generator
 *
 * Class StubGenerator
 * @package Modules\Platform\CodeGenerator\Lib
 */
class StubGenerator
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $target;

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @param array $replacements
     */
    public function save(array $replacements)
    {
        $contents = file_get_contents($this->source);

        // Standard replacements
        collect($replacements)->each(function (string $replacement, string $tag) use (&$contents) {
            $contents = str_replace($tag, $replacement, $contents);
        });

        $path = pathinfo($this->target, PATHINFO_DIRNAME);

        if (!file_exists($path)) {
            mkdir($path, 0776, true);
        }

        return file_put_contents($this->target, $contents);
    }

    /**
     * @param array $replacements
     * @return string
     */
    public function render(array $replacements): string
    {
        $contents = file_get_contents($this->source);

        // Standard replacements
        collect($replacements)->each(function (string $replacement, string $tag) use (&$contents) {
            $contents = str_replace($tag, $replacement, $contents);
        });

        return $contents;
    }
}
