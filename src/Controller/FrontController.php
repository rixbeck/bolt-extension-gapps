<?php
namespace Bolt\Extension\RixBeck\Gapps\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Bolt\Extension\RixBeck\Gapps\Extension;

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

    /**
     * Route controller entry point. In routing.yml can define how Bolt can display a calendar event directly from GCalendar.
     * Sample definition could be:
     * event:
     *   path: /event/{calname}/{eventid}
     *   defaults: { _controller: 'Bolt\Extension\RixBeck\Gapps\Controller\FrontController::event', template: 'calendareventrecord.twig' }
     *
     * @param Request $request
     * @param \Silex\Application $app
     * @param string $calname Name of calendar in config.yml
     * @param string $eventid Id of an event in calendar
     * @param string $template Template to be rendered
     */
    public static function event(Request $request, \Silex\Application $app, $calname, $eventid, $template = 'calendareventrecord.twig')
    {
        /* @var $service \Bolt\Extension\RixBeck\Gapps\Service\CalendarService */
        $service = $app[Extension::getProviderId('calendar')][$calname];
        $service->initialize();
        $event = $service->getEvent($eventid);

        $app['twig']->addGlobal('event', $event);
        $app['twig']->addGlobal('calendar', $calname);

        return $app['twig']->render($template);
    }

    /**
     * Route controller entry point for lising events in GCalendar direcctly.
     * Sample definition could be:
     * events:
     *   path: /event/{calname}
     *   defaults: { _controller: 'Bolt\Extension\RixBeck\Gapps\Controller\FrontController::events', template: 'calendarlisting.twig' }
     *
     * @param Request $request
     * @param \Silex\Application $app
     * @param string $calname Calendar name in config.yml
     * @param string $template Template to berendered with
     */
    public static function events(Request $request, \Silex\Application $app, $calname, $template = 'calendarlisting.twig')
    {
        $app['twig']->addGlobal('calendar', $calname);

        return $app['twig']->render($template);
    }
}