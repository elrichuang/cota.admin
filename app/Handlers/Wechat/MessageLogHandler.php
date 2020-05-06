<?php


namespace App\Handlers\Wechat;


use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Illuminate\Support\Facades\Log;

class MessageLogHandler implements EventHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle($payload = null)
    {
        //记录日志
        Log::info($payload);
    }
}
