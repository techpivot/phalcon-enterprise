<?php

namespace TechPivot\Phalcon\Enterprise\Dispatcher\Plugins;

use Phalcon\Text;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

/**
 * CamelizeActionName.
 *
 * Typically URL routing uses hyphenated lowercase letters which do not directly map to equivalently
 * named controller actions. In order to fix this the action name is converted into camel case prior to
 * dispatching the request.
 *
 * For example, if the original URL is: http://example.com/admin/products/show-latest-products,
 * and you want to name your action ‘showLatestProducts’, then this plugin will automatically handle
 * converting the uncamelized URL action such that the handler's action method will be properly
 * executed when dispatched.
 *
 * Note: This will handle camelizing everything except not found routes in the dispatcher.
 *       The router will need to explicitly use the uncamelized form within
 *       $router::notFound().
 */
class CamelizeActionName extends Plugin
{
    /**
     * Specifies the delimiter to use at the conclusion of all dispatching to ensure view paths properly resolve.
     *
     * @var string
     */
    private $uncamelizeDelimiter;

    /**
     * CamelizeActionName constructor.
     *
     * @param string $uncamelizeDelimiter  Specifies the delimiter to use at the conclusion of all dispatching
     *                                     to ensure view paths properly resolve.
     */
    public function __construct($uncamelizeDelimiter = '-')
    {
        $this->uncamelizeDelimiter = $uncamelizeDelimiter;
    }

    /**
     * Triggered before the dispatch loop begins.
     *
     * Note: The default value for the dispatcher action name is an empty string. We no longer need to
     * check for cases where the action could be <tt>null</tt>.
     *
     * @param \Phalcon\Events\Event   $event       The beforeDispatchLoop event.
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $dispatcher->setActionName(Text::camelize($dispatcher->getActionName()));
    }

    /**
     * In order for views to respect the uncamelized directory structure the action name must
     * be converted back into an uncamelized form.
     *
     * Note: Until \Phalcon\Text::uncamelize() supports variable delimiter we manually use hyphens.
     *
     * @link https://github.com/phalcon/cphalcon/issues/10396
     *
     * @param \Phalcon\Events\Event   $event       The beforeDispatchLoop event.
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     */
    public function afterDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $dispatcher->setActionName(Text::uncamelize($dispatcher->getActionName(), $this->uncamelizeDelimiter));
    }
}
