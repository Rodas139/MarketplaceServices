<?php
namespace App\Models;

use Auth;
use Pusher\Pusher;

class BroadcastPusher {

    /**
     * Pusher instance
     * @var
     */
    private $pusher;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

        $options = array(
            'cluster'   => env('PUSHER_CLUSTER'),
            'encrypted' => env('PUSHER_ENCRYPTION')
        );

        $this->pusher = new Pusher(
            env('PUSHER_KEY'),
            env('PUSHER_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
    }

    /**
     * Return channel name
     * @param int $userId
     * @return string
     */
    public static function getUserChannel($userId = null) {
        $userId = empty($userId) ? Auth::user()->id : $userId;
        return 'channel-user-' . $userId;
    }


    /**
     * Return global channel name
     * @param string $source
     * @return string
     */
    public static function getGlobalChannel($source = null) {
        $source = empty($source) ? config('app.source') : $source;
        return 'channel-' . $source;
    }

    /**
     * Trigger pusher event
     *
     * @param $channel
     * @param $event
     * @param $data
     * @return object
     */
    public function trigger($data, $channel = null, $event = null) {
        $channel = empty($channel) ? self::getGlobalChannel() : $channel;
        $event   = empty($event)   ? 'notifications-event' : $event;

        return $this->pusher->trigger($channel, $event, $data);
    }

}
