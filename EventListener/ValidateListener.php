<?php

namespace Symfony\Bundle\FrameworkBundle\EventListener;

use Symfony\Component\Validator\ValidatorInterface;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ValidateListener
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     *
     * @param PostExecuteEvent $event
     */
    public function onPostExecute(PostExecuteEvent $event)
    {
        $fixtures = $event->getFixtures();

        foreach ($fixtures as $fixture) {
            $object = $fixture->getObject();
            $this->validator->validate($object);
        }
    }

}
