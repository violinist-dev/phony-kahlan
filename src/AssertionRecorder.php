<?php

namespace Eloquent\Phony\Kahlan;

use Eloquent\Phony\Assertion\AssertionRecorder as PhonyAssertionRecorder;
use Eloquent\Phony\Assertion\Exception\AssertionException;
use Eloquent\Phony\Call\CallVerifierFactory;
use Eloquent\Phony\Event\Event;
use Eloquent\Phony\Event\EventCollection;
use Eloquent\Phony\Event\EventSequence;
use Exception;
use Kahlan\Suite;

/**
 * An assertion recorder for Kahlan.
 */
class AssertionRecorder implements PhonyAssertionRecorder
{
    /**
     * Set the call verifier factory.
     *
     * @param CallVerifierFactory $callVerifierFactory The call verifier factory to use.
     */
    public function setCallVerifierFactory(
        CallVerifierFactory $callVerifierFactory
    ) {
        $this->callVerifierFactory = $callVerifierFactory;
    }

    /**
     * Record that a successful assertion occurred.
     *
     * @param array<Event> $events The events.
     *
     * @return EventCollection The result.
     */
    public function createSuccess(array $events = [])
    {
        Suite::current()->expectExternal(['callback' => function () {}]);

        return new EventSequence($events, $this->callVerifierFactory);
    }

    /**
     * Record that a successful assertion occurred.
     *
     * @param EventCollection $events The events.
     *
     * @return EventCollection The result.
     */
    public function createSuccessFromEventCollection(EventCollection $events)
    {
        Suite::current()->expectExternal(['callback' => function () {}]);

        return $events;
    }

    /**
     * Create a new assertion failure exception.
     *
     * @param string $description The failure description.
     *
     * @throws Exception If this recorder throws exceptions.
     */
    public function createFailure($description)
    {
        Suite::current()->expectExternal([
            'callback' => function () use ($description) {
                throw new AssertionException($description);
            },
        ]);
    }

    private $callVerifierFactory;
}