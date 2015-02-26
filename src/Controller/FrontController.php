<?php
namespace Bolt\Extension\Rixbeck\Gapps\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Bolt\Extension\Rixbeck\Gapps\Extension;

class FrontController
{

    /*
     * (non-PHPdoc)
     * @see \Silex\ControllerProviderInterface::connect()
     */
    public function connect(\Silex\Application $app)
    {
        // TODO: Auto-generated method stub
    }

    public static function event(Request $request, \Silex\Application $app, $calname, $eventid)
    {
        // $app['twig']->addGlobal('calendar', $calname);
        // $app['twig']->addGlobal('eventid', $eventid);

        /* @var $service \Bolt\Extension\Rixbeck\Gapps\Service\CalendarService */
        $service = $app[Extension::getProviderId('calendar')][$calname];
        $service->initialize();
        $event = $service->getEvent($eventid);

        $app['twig']->addGlobal('event', $event);
        $app['twig']->addGlobal('calendar', $calname);
        $template = 'calendareventrecord.twig';

        return $app['twig']->render($template);
    }

    public static function events(Request $request, \Silex\Application $app, $calname)
    {
        $app['twig']->addGlobal('calendar', $calname);
        $template = 'calendarlisting.twig';

        return $app['twig']->render($template);
    }
}