<?php

namespace TechPivot\Phalcon\Enterprise\Dispatcher\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * AjaxRouteHandler.
 *
 * This plugin provides consistent behavior for handling return content for AJAX requests by using
 * the return value from the route's action.
 *
 * Routes simply need to return data that should be returned as json. For example:
 *
 *  <code>
 *      public function ajaxAction()
 *      {
 *          return [
 *              'ok' => true
 *          ];
 *      }
 *  </code>
 *
 * Will result in the actual response that contains the formatted JSON:
 *
 *  <pre>{"ok":true}</pre>
 *
 * Note: Automatic view disabling is appropriately handled.
 *
 * Note: If the response is AJAX and the return value is <tt>null</tt>, the HTTP response will be an empty
 *       response with status code 204.
 */
class AjaxRouteHandler extends Plugin
{
    /**
     * Automatically handle ajax return data.
     *
     * @param \Phalcon\Events\Event   $event       The afterExecuteRoute event.
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     *
     * @return void
     */
    public function afterDispatchLoop(Event $event, Dispatcher $dispatcher): void
    {
        /** @var \Phalcon\Http\Request $request */
        $request = $this->getDI()->getShared('request');

        if ($request->isAjax()) {
            /** @var \Phalcon\Mvc\View $view */
            $view = $this->getDI()->getShared('view');
            $view->disable();

            /** @var \Phalcon\Http\Response $response */
            $response = $this->getDI()->getShared('response');
            if ($response->isSent() === false && $response->getStatusCode() === null) {
                $data = $dispatcher->getReturnedValue();

                // Note: If we set the content/status code we set the response back into the dispatcher
                // returned value which allows the \Phalcon\Mvc\Application to properly handle the response
                // without calling into the view and overwriting the response output.
                // @see https://github.com/phalcon/cphalcon/blob/44ce3c6d5d00cfe0626ff09c0ce4b825e39389d0/phalcon/mvc/application.zep#L316

                if ($data === null) {
                    $response->setStatusCode(204);
                    $dispatcher->setReturnedValue($response);
                } elseif (is_array($data)) {
                    $response->setJsonContent($data);
                    $dispatcher->setReturnedValue($response);
                }

                // The string case is already handled inside the the \Phalcon\Mvc\Application::handle()
            }
        }
    }
}
