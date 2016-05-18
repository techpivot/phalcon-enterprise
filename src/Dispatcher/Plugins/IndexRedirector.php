<?php

namespace TechPivot\Phalcon\Enterprise\Dispatcher\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\User\Plugin;

/**
 * IndexRedirector.
 *
 * Ensures that any dispatched route that includes the explicit default index, typically "index", as the
 * action or controller is prohibited. The default behavior of allowing the explicit default index is
 * a by product of Phalcon's routing system that will allow explicitly matched controllers and actions.
 *
 * Prohibiting this behavior is useful in ensuring accurate Search Engine Optimization as well as
 * providing a level of increased security as default routes can expose information about the underlying
 * system architecture.
 */
class IndexRedirector extends Plugin
{
    /**
     * The default action index. Defaults to "index"
     *
     * @var string
     */
    private $defaultActionIndex;

    /**
     * The default handler index. Defaults to "index"
     *
     * @var string
     */
    private $defaultHandlerIndex;

    /**
     * IndexRedirectorPlugin constructor.
     *
     * @param string $defaultActionIndex   The default action index. Defaults to "index"
     * @param string $defaultHandlerIndex  The default handler index. Defaults to "index"
     */
    public function __construct($defaultActionIndex = 'index', $defaultHandlerIndex = 'index')
    {
        $this->defaultActionIndex = $defaultActionIndex;
        $this->defaultHandlerIndex = $defaultHandlerIndex;
    }

    /**
     * Triggered before the dispatch loop begins.
     *
     * @param \Phalcon\Events\Event   $event       The beforeDispatchLoop event.
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        /** @var \Phalcon\Mvc\RouterInterface $router */
        $router = $this->getDI()->getShared('router');

        // Action Validation
        switch ($router->getActionName()) {
            case $this->defaultActionIndex:
                $exception = new Exception('Action "index" was not found on handler "' .
                    $dispatcher->getControllerName() . '"', Dispatcher::EXCEPTION_HANDLER_NOT_FOUND);

                $eventsManager = $dispatcher->getEventsManager();
                if ($eventsManager->fire('dispatch:beforeException', $dispatcher, $exception) === false) {
                    break;
                }

                throw $exception;
        }

        // Handler Validation
        switch ($router->getControllerName()) {
            case $this->defaultHandlerIndex:
                // Allow the default pattern
                if ($router->getMatchedRoute()->getPattern() === '/') {
                    break;
                }

                $exception = new Exception('"' . $dispatcher->getNamespaceName() .
                    '\IndexController" handler class cannot be loaded', Dispatcher::EXCEPTION_HANDLER_NOT_FOUND);

                $eventsManager = $dispatcher->getEventsManager();
                if ($eventsManager->fire('dispatch:beforeException', $dispatcher, $exception) === false) {
                    break;
                }

                throw $exception;
        }
    }
}
