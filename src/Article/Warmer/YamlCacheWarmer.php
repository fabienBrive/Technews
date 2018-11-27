<?php
namespace App\Article\Warmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlCacheWarmer extends CacheWarmer
{
    public function isOptional()
    {
        return false;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        try {
            $article = Yaml::parseFile(__DIR__ . "/../Provider/articles.yaml")['data'];
            $this->writeCacheFile($cacheDir .'/yaml-article.php', serialize($article));
        } catch (ParseException $exception) {
            printf("Unable to pars the YAML string: %s", $exception->getMessage());
        }
    }
}