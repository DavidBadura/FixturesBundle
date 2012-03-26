<?php

namespace Symfony\Bundle\FrameworkBundle\EventListener;

use DavidBadura\FixturesBundle\Event\PreExecuteEvent;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TagFilterListener
{

    /**
     *
     * @param PreExecuteEvent $event
     */
    public function onPreExecute(PreExecuteEvent $event)
    {
        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        if (empty($options['tags'])) {
            return;
        }

        if (!is_array($options['tags'])) {
            $options['tags'] = array($options['tags']);
        }

        foreach ($fixtures as $key => $fixture) {
            if (!in_array($options['tags'], $fixture->getTags())) {
                unset($fixtures[$key]);
            }
        }

        $event->setFixtures($fixtures);
    }

}
