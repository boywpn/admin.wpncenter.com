<?php
namespace Eusonlito\LaravelPacker\Providers;

use JSMin;
use Eusonlito\LaravelPacker\Packer;

class JS extends ProviderBase implements ProviderInterface
{
    /**
     * @param  string $file
     * @param  string $public
     * @return string
     */
    public function pack($file, $public)
    {
        if (!Packer::isRemote($file) && !is_file($file)) {
            return sprintf('/* File %s not exists */', $file);
        }

        $contents = file_get_contents($file);

        if ($this->settings['minify']) {
            $contents = JSMin::minify($contents);
        }

        return ';'.$contents;
    }

    /**
     * @param  mixed  $file
     * @return string
     */
    public function tag($file)
    {
        if (is_array($file)) {
            return $this->tags($file);
        }

        $attributes = $this->settings['attributes'];
        $attributes['src'] = $this->path($this->settings['asset'].$file);

        return '<script '.$this->attributes($attributes).'></script>'.PHP_EOL;
    }
}
