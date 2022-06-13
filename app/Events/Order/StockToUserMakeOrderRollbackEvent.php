<?php

namespace App\Events\Order;

use Illuminate\Support\Facades\Log;

class StockToUserMakeOrderRollbackEvent
{
    public array $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */


    public function __construct(array $data)
    {
        Log::info('Rollback Event Called stock to user');
        $this->data = $data;
    }
}
