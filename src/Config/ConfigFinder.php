<?php

namespace Wenprise\Mvc\Config;

use Wenprise\Mvc\Finder\Finder;

class ConfigFinder extends Finder
{
    /**
     * The file extensions.
     *
     * @var array
     */
    protected $extensions = ['config.php', 'php'];
}
