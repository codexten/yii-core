<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 9/17/18
 * Time: 4:17 PM
 */

namespace codexten\yii\components;


use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;

class EventManager extends Component implements BootstrapInterface
{
    /**
     * ```
     * [
     *      ['class' => ActiveRecord::class, 'event' => ActiveRecord::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onActiveRecordDelete']],
     *      ['class' => IntegrityController::class, 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => [Events::class, 'onIntegrityCheck']],
     *      ['class' => CronController::class, 'event' => CronController::EVENT_ON_HOURLY_RUN, 'callback' => [Events::class, 'onCronRun']],
     *      ['class' => CronController::class, 'event' => CronController::EVENT_ON_DAILY_RUN, 'callback' => [Events::class, 'onCronRun']],
     * ]
     * ```
     *
     * @var array
     */
    public $events = [];

    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $this->register();
    }

    protected function register()
    {
        // Register Event Handlers
        foreach ($this->events as $event) {
            if (isset($event['class'])) {
                Event::on($event['class'], $event['event'], $event['callback']);
            } else {
                Event::on($event[0], $event[1], $event[2]);
            }
        }
    }

}