<?php

namespace Elazar\Scribing\Configuration;

use Elazar\Auryn\Configuration\ConfigurationSet;

class Set extends ConfigurationSet
{
    public function __construct()
    {
        parent::__construct([
            Application::class, 
            CommonMark::class,
        ]);
    }
}
