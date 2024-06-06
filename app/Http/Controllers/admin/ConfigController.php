<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils;
use App\Models\AvailableSlot;
use App\Models\AvailableSlotConfig;
use App\Models\Config;

class ConfigController extends Controller
{

    public function index(){
        $availableSlots = AvailableSlotConfig::all();
        $config = Config::firstOrFail(); 
        return view('admin.config.index', compact('config'))->with('availability', $availableSlots);
      
    }
    public function edit()
    {
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config
        return view('config', compact('config'));
    }

    public function update(Request $request)
    {

        foreach ($request->days as $day => $data) {
            // Ajuste aqui para verificar a existência das horas
            if (!empty($data['start_time']) && !empty($data['end_time'])) {
                $slot = AvailableSlotConfig::where('day_of_week', $day)->first();
    
                if ($slot) {
                    // Atualiza apenas se o checkbox 'active' estiver marcado
                    if (isset($data['active'])) {
                        $slot->update([
                            'start_time' => $data['start_time'],
                            'end_time' => $data['end_time'],
                        ]);
                    } else {
                        $slot->delete();
                    }
                } else {
                    // Cria um novo registro apenas se o checkbox 'active' estiver marcado
                    if (isset($data['active'])) {
                        AvailableSlotConfig::create([
                            'day_of_week' => $day,
                            'start_time' => $data['start_time'],
                            'end_time' => $data['end_time'],
                        ]);
                    }
                }
            }
        }
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config

        $config->motoboy_fone = Utils::sanitizePhone($request->input('motoboy_fone'));
        $config->status = $request->has('status');
        $config->chatbot = $request->has('chatbot');
        $config->resposta = $request->resposta;
        $config->save();

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
