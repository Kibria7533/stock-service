<?php

namespace App\Listeners\Order;


use App\Events\Order\StockToPaymentMakeOrderEvent;
use App\Events\Order\StockToUserMakeOrderRollbackEvent;
use App\Models\BaseModel;
use App\Models\Stock;
use App\Models\User;
use App\Services\RabbitMQService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserToStockMakeOrderListener implements ShouldQueue
{

    public RabbitMQService $rabbitMQService;

    /**

     * @param RabbitMQService $rabbitMQService
     */
    public function __construct(RabbitMQService $rabbitMQService)
    {

        $this->rabbitMQService = $rabbitMQService;
    }

    /**
     * @param $event
     * @return void
     * @throws Exception|Throwable
     */
    public function handle($event)
    {
        $eventData = json_decode(json_encode($event), true);
        $data = $eventData['data'] ?? [];
        Log::info('me',$data);
        try {
            $this->rabbitMQService->receiveEventSuccessfully(
                BaseModel::SAGA_INSTITUTE_SERVICE,
                BaseModel::SAGA_YOUTH_SERVICE,
                get_class($this),
                json_encode($event)
            );

            $alreadyConsumed = $this->rabbitMQService->checkEventAlreadyConsumed();
            if (!$alreadyConsumed) {
                DB::beginTransaction();

                if (!empty($data['productId'])) {
                    Log::info('hello');
                    $product =Stock::where('product_code','=',$data['productId'])->first();
                    throw_if(!$product,'RuntimeException','Product Not found');

                    Log::info('hjhjjjjhjh',$product->toArray());

                    DB::commit();

                    /** Trigger EVENT to MailSms Service to send Mail via RabbitMQ */
                    //$this->youthService->sendMailCourseEnrollmentSuccess($data);

                    /** Trigger EVENT to Institute Service via RabbitMQ */
                    event(new StockToPaymentMakeOrderEvent($data));

                    /** Store the event as a Success event into Database */
                    $this->rabbitMQService->sagaSuccessEvent(
                        BaseModel::SAGA_INSTITUTE_SERVICE,
                        BaseModel::SAGA_YOUTH_SERVICE,
                        get_class($this),
                        json_encode($data)
                    );
                } else {
                    throw new Exception("youth_id not provided!");
                }
            }
        } catch (Throwable $e) {
            Log::info('I am in exception');
            if ($e instanceof QueryException && $e->getCode() == BaseModel::DATABASE_CONNECTION_ERROR_CODE) {
                /** Technical Recoverable Error Occurred. RETRY mechanism with DLX-DLQ apply now by sending a rejection */
                throw new Exception("Database Connectivity Error");
            } else {
                /** Trigger EVENT to Institute Service via RabbitMQ to Rollback */
                $data['publisher_service'] = BaseModel::SAGA_YOUTH_SERVICE;
               event(new StockToUserMakeOrderRollbackEvent($data));

                /** Technical Non-recoverable Error "OR" Business Rule violation Error Occurred. Compensating Transactions apply now */
                /** Store the event as an Error event into Database */
                $this->rabbitMQService->sagaErrorEvent(
                    BaseModel::SAGA_INSTITUTE_SERVICE,
                    BaseModel::SAGA_YOUTH_SERVICE,
                    get_class($this),
                    json_encode($data),
                    $e
                );
            }
        }
    }
}
