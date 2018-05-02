<?php

namespace Wenprise\Config;

use Wenprise\Finder\Finder;

class ConfigFinder extends Finder
{
    /**
     * The file extensions.
     *
     * @var array
     */
    protected $extensions = ['config.php', 'php'];
}
