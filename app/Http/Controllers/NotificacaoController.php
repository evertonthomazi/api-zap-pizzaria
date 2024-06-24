<?php

namespace App\Http\Controllers;

use App\Notifications\NovaNotificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NotificacaoController extends Controller
{
    public function enviarNotificacao()
    {
        $mensagem = 'Nova notificação!';
        broadcast(new NovaNotificacao($mensagem));
        return response()->json(['message' => 'Notificação enviada']);
    }

    public function check()
    {
        $user = Session::get('userData');
        if ($user) {
            $unreadNotifications = $user->unreadNotifications;
            return response()->json(['notifications' => $unreadNotifications]);
        }
        return response()->json(['notifications' => Session::get('userData')->unreadNotifications]);
    }
}
