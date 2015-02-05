<?php
namespace Bolt\Extension\Rixbeck\Gapps;

use Bolt\BaseExtension;

class Extension extends BaseExtension
{

    const NAME = 'GApps';

    const CONTAINER_ID = 'extensions.Gapps';

    protected $baseDir;

    public function getName()
    {
        return static::NAME;
    }

    public function initialize()
    {
        $this->baseDir = __DIR__;
        $this->app['twig']->addExtension(new Twig\TwigExtension($this->app));
    }
}
