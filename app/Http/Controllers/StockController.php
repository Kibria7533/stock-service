<?php

namespace App\Http\Controllers;


use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{

    public  function  getStocks(){

        $order=Stock::get();
        return response($order,'200');
    }

    public  function  store(Request $request){
        $data=$request->all();
        $order=new Stock();
        $save=[
            'product_code'=>$data['product_code'],
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'status' =>0
        ];
        $order->fill($save);
        $order->save();
        return response($data,'200');
    }

    /**
     * @param int $id
     * @return bool|void
     * @throws \Throwable
     */

    public function delete(int $id){
        $order=Stock::find($id);
        throw_if(!$order->delete($id),'RuntimeException', 'Order has not been deleted.', 500);
        return true;
    }
}
