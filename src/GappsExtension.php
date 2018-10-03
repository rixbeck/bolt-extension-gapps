<?php

namespace Bolt\Extension\RixBeck\Gapps;

use Bolt\Collection\Arr;
use Bolt\Extension\RixBeck\Gapps\Service\AccountsService;
use Bolt\Extension\RixBeck\Gapps\Service\CalendarService;
use Bolt\Extension\RixBeck\Gapps\Service\Directory\GroupsService;
use Bolt\Extension\RixBeck\Gapps\Twig\Calendar;
use Bolt\Extension\RixBeck\Gapps\Twig\Directory\Groups;
use Bolt\Extension\RixBeck\Gapps\Twig\General;
use Bolt\Extension\SimpleExtension;
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
            $configDir = $app['path_resolver']->resolve('extensions_config');

            foreach ($config['accounts'] as $name => $account) {
                $accounts[$name] = $accounts->share(function () use ($configDir, $account, $name) {
                    return new AccountsService($account, $name, $configDir);
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

                    return new CalendarService($app['gapps.accounts'][$accountId], $calendar, $this->getConfig()['recordtypes']);
                });
            }

            return $calendars;
        });

        $groups = new \Pimple();
        $app['gapps.directory.groups'] = $app->share(function ($app) use ($groups) {
            $config = Arr::get($this->getConfig(), 'directory/groups', []);
            foreach ($config as $name => $group) {
                $groups[$name] = $groups->share(function ($groups) use ($app, $group) {
                    $accountId = $group['account'];

                    return new GroupsService($app['gapps.accounts'][$accountId], $group, $this->getConfig()['recordtypes']);
                });
            }

            return $groups;
        });
    }

    protected function registerTwigFunctions()
    {
        $app = $this->getContainer();
        $general = new General();
        $calendar = new Calendar($app['gapps.calendar']);
        $dirGrups = new Groups($app['gapps.directory.groups']);

        return [
            'calendarevents' => [[$calendar, 'getService']],
            'recurrences' => [[$calendar, 'createRecurrence']],
            'eventmatrix' => [[$calendar, 'createEventMatrix']],

            'randomfile' => [[$general, 'randomFile']],

            'directorygroups' => [[$dirGrups, 'getService']],
        ];
    }

    protected function registerTwigFilters()
    {
        // $app = $this->getContainer();
        $general = new General();

        return [
            'localedate' => [[$general, 'dateFormatFilter'], ['needs_environment' => true]],
            'roman' => [[$general, 'romanNumberFilter'], ['needs_environment' => true]],
            'wtrim' => [[$general, 'trim']],
        ];
    }
}
