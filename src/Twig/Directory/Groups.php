<?php

namespace Bolt\Extension\RixBeck\Gapps\Twig\Directory;

class Groups
{
    private $groupsServices;

    public function __construct(\Pimple $groupsServices)
    {
        $this->groupsServices = $groupsServices;
    }

    public function getService($serviceName)
    {
        $service = $this->groupsServices[$serviceName];
        $service->initialize();

        return $service;
    }
}
