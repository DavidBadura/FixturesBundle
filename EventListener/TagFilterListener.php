<?php

namespace DavidBadura\FixturesBundle\EventListener;

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

        $return = array();

        foreach ($fixtures as $key => $fixture) {
            foreach($fixture->getTags() as $tag) {
                if (in_array($tag, $options['tags'])) {
                    $return[] = $fixture;
                    continue 2;
                }
            }
        }

        $event->setFixtures($return);
    }

}
