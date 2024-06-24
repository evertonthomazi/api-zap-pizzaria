<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Device;
use App\Models\Order;
use App\Models\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index');
    }
    public function updateNotify(){
        $user = Session::get('userData');
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return json_encode(array('success' => true));
    }

    public function index2()
    {
        $user = Session::get('userData');
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return view('admin.order.index');
    }

    public function getOrders()
    {
        $orders = Order::with(['customer', 'status'])->get();

        // Modificando os dados para incluir o atributo display_data
        foreach ($orders as $order) {
            $order->display_data = $order->display_data;
        }

        return DataTables::of($orders)->make(true);
    }

    public function getOrder(Request $request)
    {
        $order = Order::with(['customer', 'items'])->where('id', $request->id)->first();
        echo json_encode($order);
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input('orderId');
        $newStatus = $request->input('newStatus');
        $device = Device::first();
        // Atualizar o status diretamente
        $order = Order::find($orderId);
        if ($newStatus === '2') {
            $config = Config::first();
            $text = "PEDIDO#" . $order->id . " Veja a rota no Google Maps:" . $order->customer->getLocationLink();
            Whatsapp::sendMessagem($device->session, '55' . $config->motoboy_fone, $text);
        }

        // Verificar se o novo status é "Saiu Para Entrega"
        if ($newStatus === '5') {


            $text2 = "🏍️ Seu pedido acabou de sair para entrega.";
            // dd( $order->customer->jid);
            Whatsapp::sendMessagem($device->session, $order->customer->jid, $text2);
        }


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
