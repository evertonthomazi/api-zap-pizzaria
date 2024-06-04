<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(){
        return view('admin.order.index');
    }

    public function getOrders()
    {
        $orders = Order::with(['customer', 'status'])->orderBy('id')->get();
    
        // Modificando os dados para incluir o atributo display_data
        foreach ($orders as $order) {
            $order->display_data = $order->display_data;
        }
    
        return DataTables::of($orders)->make(true);
    }

    public function getOrder(Request $request){
        $order = Order::with(['customer', 'items'])->where('id',$request->id)->first();
        echo json_encode($order);
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input('orderId');
        $newStatus = $request->input('newStatus');

        // Verificar se o novo status é "Saiu Para Entrega"
        if ($newStatus === 'Saiu Para Entrega') {
            // Exibir o modal de confirmação
            return response()->json(['confirm_modal' => true, 'order_id' => $orderId]);
        } else {
            // Atualizar o status diretamente
            $order = Order::find($orderId);

            // dd($request->all());
            if ($order) {
                $order->status_id = $newStatus;
                $order->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Pedido não encontrado.']);
            }
        }
    }
}
