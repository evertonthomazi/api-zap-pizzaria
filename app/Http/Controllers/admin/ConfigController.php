<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;

class ConfigController extends Controller
{

    public function index(){
       
        $config = Config::firstOrFail(); 
        return view('admin.config.index', compact('config'));
      
    }
    public function edit()
    {
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config
        return view('config', compact('config'));
    }

    public function update(Request $request)
    {
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config

        $config->motoboy_fone = $request->input('motoboy_fone');
        $config->status = $request->has('status');
        $config->chatbot = $request->has('chatbot');
        $config->save();

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
