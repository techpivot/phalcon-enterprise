<?php

namespace TechPivot\Phalcon\Enterprise\Dispatcher\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\User\Plugin;

/**
 * IndexRedirector.
 *
 * Ensures that any dispatched route that dynamically matches the router's default action or handler
 * is prohibited. Any explicitly defined route 'action' or 'controller' that matches a default
 * is allowed.
 *
 * Limiting the accessibility of the default routes is important in ensuring accurate Search Engine Optimization
 * as well as providing a level of increased security as default routes can expose information about the underlying
 * system architecture.
 *
 * For example:
 *
 * <code>
 *  $router = new Router(false);
 *  $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
 *  $router->removeExtraSlashes(true);
 *  $router->setDefaultAction('index');
 *  $router->setDefaultController('index');
 *
 *  $router->add('/', []);
 *  $router->add('/:controller/', [
 *       'controller' => 1,
 *  ]);
 *  $router->add('/:controller/:action', [
 *       'controller' => 1,
 *       'action'     => 2
 *  ]);
 *
 *  // Include this plugin
 *  $di->setShared('dispatcher', function () {
 *      $eventsManager = new EventsManager();
 *      $eventsManager->attach('dispatch', new IndexRedirector());
 *
 *      $dispatcher = new Dispatcher();
 *      $dispatcher->setEventsManager($eventsManager);
 *
 *      return $dispatcher;
 *  }
 * </code>
 *
 * The above router and dispatcher setup results in the following:
 *
 * <pre>
 * URL Route                        Result
 * ========================================================
 * /index/index                     Not Matched (Exception)
 * /valid-controller/valid-action   Matched
 * /index                           Not Matched (Exception)
 * /valid-controller                Matched
 * /                                Matched
 * </pre>
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
     * IndexRedirector plugin constructor. Instantiate the default router action and handler
     * values here as they are not publically retrievable from the Router instance.
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

        $matchedRoute =  $router->getMatchedRoute();
        if ($matchedRoute === null) {
            return;
        }

        $paths = $matchedRoute->getPaths();

        // Action Validation
        if ($router->getActionName() === $this->defaultActionIndex &&
            isset($paths['action']) && $paths['action'] !== $this->defaultActionIndex
        ) {
            $exception = new Exception('Action "' . $this->defaultActionIndex . '" was not found on handler "' .
                $dispatcher->getControllerName() . '"', Dispatcher::EXCEPTION_HANDLER_NOT_FOUND);

            $eventsManager = $dispatcher->getEventsManager();
            if ($eventsManager->fire('dispatch:beforeException', $dispatcher, $exception) === false) {
                return false;
            }

            throw $exception;
        }

        // Handler Validation
        if ($router->getControllerName() === $this->defaultHandlerIndex &&
            isset($paths['controller']) && $paths['controller'] !== $this->defaultHandlerIndex
        ) {
            $exception = new Exception('"' . $dispatcher->getNamespaceName() .
                '\\' . ucfirst($this->defaultHandlerIndex) . 'Controller" handler class cannot be loaded',
                Dispatcher::EXCEPTION_HANDLER_NOT_FOUND);

            $eventsManager = $dispatcher->getEventsManager();
            if ($eventsManager->fire('dispatch:beforeException', $dispatcher, $exception) === false) {
                return false;
            }

            throw $exception;
        }
    }
}
