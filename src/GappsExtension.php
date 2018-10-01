<?php

namespace Bolt\Extension\Rixbeck\Gapps;

use Bolt\Collection\Arr;
use Bolt\Extension\Rixbeck\Gapps\Service\AccountsService;
use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;
use Bolt\Extension\Rixbeck\Gapps\Service\Directory\GroupsService;
use Bolt\Extension\Rixbeck\Gapps\Twig\FormExtender;
use Bolt\Extension\Rixbeck\Gapps\Twig\General;
use Bolt\Extension\SimpleExtension;
use Cocur\Slugify\Slugify;
use Silex\Application;

/**
 * This extension initializes its modules as services under $app container.
 *
 * @author Rix Beck <rix at neologik.hu>
 * Copyright 2015
 *
 */
class GappsExtension extends SimpleExtension
{
    public function registerServices(Application $app)
    {
        $accounts = new \Pimple();

        $app['gapps.accounts'] = $app->share(function ($app) use ($accounts) {
            $config = $this->getConfig();
            $appId = Slugify::create()->slugify($app['config']->get('general/sitename'));

            foreach ($config['accounts'] as $name => $account) {
                $accounts[$name] = $accounts->share(function () use ($account, $appId, $name) {
                    return new AccountsService($account, $appId, $name);
                });
            }

            return $accounts;
        });

        $calendars = new \Pimple();
        $app['gapps.calendar'] = $app->share(function ($app) use ($calendars) {
            $config = Arr::get($this->getConfig(), 'calendar', []);
            foreach ($config as $name => $calendar) {
                $calendars[$name] = $calendars->share(function () use ($app, $name, $calendar) {
                    $accountId = $calendar['account'];

                    return new CalendarService($app['gapps.accounts'][$accountId], $calendar);
                });
            }

            return $calendars;
        });

        $groups = new \Pimple();
        $app['gapps.directory.groups'] = $app->share(function (Application $app) use ($app, $groups) {
            $config = Arr::get($this->getConfig(), 'directory/groups', []);
            foreach ($config as $name => $group) {
                $groups[$name] = $groups->share(function (Application $groups) use ($app, $group) {
                    $accountId = $group['account'];

                    return new GroupsService($app['gapps.accounts'][$accountId], $group);
                });
            }

            return $groups;
        });
    }

    protected function registerTwigFunctions()
    {
        return (new General())->functions();
    }

    protected function registerTwigFilters()
    {
        $app = $this->getContainer();

        return (new FormExtender($app['formextender'], $this->getConfig()))->filters();
    }

    protected function getDefaultConfig()
    {
        return [
            'accounts'
        ];
    }
}
