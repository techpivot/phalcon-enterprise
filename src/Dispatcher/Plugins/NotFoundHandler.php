<?php

namespace TechPivot\Phalcon\Enterprise\Dispatcher\Plugins;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

/**
 * NotFoundHandler.
 *
 * Automatically forwards the dispatcher to the specified error handler when the dispatched route
 * results in an invalid action or invalid handler.
 */
class NotFoundHandler extends Plugin
{
    /**
     * The dispatch data to forward an invalid handler.
     *
     * @var array|null
     */
    private $forwardHandlerNotFound;

    /**
     * The dispatch data to forward an invalid action.
     *
     * @var array|null
     */
    private $forwardActionNotFound;

    /**
     * The dispatch data to forward an uncaught exception. The dispatcher will have an additional
     * parameter "exception" that contains the handled dispatch <tt>\Exception</tt>.
     *
     * @var array|null
     */
    private $forwardUnhandledException;

    /**
     * NotFoundPlugin constructor.
     *
     * @param array|null $forwardHandlerNotFound     The dispatch data to forward an invalid handler.
     * @param array|null $forwardActionNotFound      The dispatch data to forward an invalid action.
     * @param array|null $forwardUnhandledException  The dispatch data to forward an uncaught exception. The dispatcher
     *                                               will have an additional parameter "exception" that contains the
     *                                               handled dispatch <tt>\Exception</tt>.
     */
    public function __construct(
        array $forwardHandlerNotFound = null,
        array $forwardActionNotFound = null,
        array $forwardUnhandledException = null
    ) {
        $this->forwardHandlerNotFound = $forwardHandlerNotFound;
        $this->forwardActionNotFound = $forwardActionNotFound;
        $this->forwardUnhandledException = $forwardUnhandledException;
    }

    /**
     * Handled when the dispatcher throws an exception of any kind.
     *
     * @param \Phalcon\Events\Event   $event       The beforeDispatchLoop event.
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     * @param \Exception              $exception   The exception being handled.
     *
     * @return void|false  Returns <tt>false</tt> if the exception is dispatched to a specific error handler; otherwise
     *                     returns <tt>null</tt>.
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception)
    {
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    return $this->forward($dispatcher, $exception, $this->forwardHandlerNotFound);

                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    return $this->forward($dispatcher, $exception, $this->forwardActionNotFound);
            }
        }

        return $this->forward($dispatcher, $exception, $this->forwardUnhandledException);
    }

    /**
     * Convenience helper method to ensure that only dispatched exceptions return <tt>false</tt>, which is
     * important as this stops the current dispatch loop.
     *
     * @param \Phalcon\Mvc\Dispatcher $dispatcher  The application dispatcher instance.
     * @param \Exception              $exception   The exception being handled.
     * @param array|null              $forward     Optional dispatch data.
     *
     * @return void|false  Returns <tt>false</tt> if the exception is dispatched to a specific error handler; otherwise
     *                     returns <tt>null</tt>.
     */
    private function forward(Dispatcher $dispatcher, Exception $exception, array $forward = null)
    {
        if ($forward !== null) {
            if (isset($forward['params']) === false) {
                $forward['params'] = [];
            }
            $forward['params'] = array_merge($forward['params'], [
                'exception' => $exception
            ]);
            $dispatcher->forward($forward);

            return false;
        }
    }
}
