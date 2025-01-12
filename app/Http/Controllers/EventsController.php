<?php

namespace App\Http\Controllers;

use App\Helpers\Base62Helper;
use App\Models\Avaliacao;
use App\Models\Chat;
use App\Models\Colaborador;
use App\Models\Config;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Messagen;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrdersItens;
use App\Models\Route;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{



    public function carrinhoAbandonado()
    {
        $responseJson = file_get_contents('php://input');
        $responseArray = json_decode($responseJson, true);
        // dd($responseArray);
        $responseArray = $responseArray['abandoned_cart'];

        // Pegando os dados do cliente e carrinho
        $customerName = $responseArray['email']; // Você pode ajustar caso tenha o campo 'name'
        $checkoutUrl = $responseArray['checkout_url'];
        $total = $responseArray['total'];
        $currency = $responseArray['currency'];
        $items = $responseArray['items'];
        $phone = $responseArray['phone'];

        // dd($phone);
        // Gerar lista formatada de itens
        $itemsList = '';
        foreach ($items as $item) {
            $itemsList .= '- ' . $item['name'] . ' (Quantidade: ' . $item['quantity'] . ', Preço: ' . number_format($item['price'], 2, ',', '.') . ' ' . $currency . ')\n';
        }


        // Mensagem personalizada
        $text = 'Olá! 👋 É sempre um prazer ter você com a gente! 😊\n\n'
            . 'Notamos que você deixou alguns produtos no carrinho e não queremos que você perca essas ofertas incríveis! 🛒\n\n'
            . '📋 *Resumo do seu carrinho:*\n'
            . $itemsList . '\n'
            . '💰 *Total:* ' . number_format($total, 2, ',', '.') . ' ' . $currency . '\n'
            . '🛍️ Para finalizar sua compra, é só clicar no link abaixo:\n'
            . '🔗 ' . $checkoutUrl . '\n'
            . 'Fácil, rápido e prático! 🚀 Não perca essa chance de garantir seus produtos favoritos! 😊';

        $session = Device::first();
        $this->sendMessagem($session->session, $phone, $text);
    }

    public function index()
    {
        $reponseJson = file_get_contents('php://input');

        // file_put_contents(Utils::createCode()."-audio.txt",$reponseJson);
        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();
        $config = Config::firstOrFail();
        if ($reponseArray['data']['event'] == "DISCONNECTED") {
            $session->status = "DISCONNECTED";
            $session->update();
            exit;
        }

        // Configurar o Carbon para usar o fuso horário de São Paulo
        $now = Carbon::now('America/Sao_Paulo');

        $daysOfWeek = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sábado',
        ];

        $dayOfWeek =  $daysOfWeek[$now->dayOfWeek];
        // Obter a hora e minutos atuais
        $currentTime = $now->format('H:i:s');

        // Verifique se existe um slot disponível com os parâmetros fornecidos
        $exists = DB::table('available_slots_config')
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->exists();



        $jid = $reponseArray['data']['message']['from'];
        // Remover o texto antes do '@'
        $numero_sem_arroba = substr($jid, 0, strpos($jid, '@'));
        // Extrair apenas os últimos 9 dígitos (número de celular)
        $jid = $numero_sem_arroba;

        // Se não houver slot disponível, enviar mensagem fora do horário
        if ($exists) {
            $this->verifyService($reponseArray, $session);
        } else {
            // Montar a lista de horários de funcionamento
            $operatingHours = [];
            foreach ($daysOfWeek as $index => $day) {
                $slots = DB::table('available_slots_config')
                    ->where('day_of_week', $day)
                    ->select('start_time', 'end_time')
                    ->get();

                if ($slots->isEmpty()) {
                    $operatingHours[$day] = 'Fechado';
                } else {
                    $hours = [];
                    foreach ($slots as $slot) {
                        $hours[] = $slot->start_time . ' às ' . $slot->end_time;
                    }
                    $operatingHours[$day] = implode(', ', $hours);
                }
            }

            // Construir a mensagem com os horários de funcionamento
            $message = 'Desculpe, estamos fora do horário de atendimento. Os nossos horários de funcionamento são:\n\n';
            foreach ($operatingHours as $day => $hours) {
                $message .= ucfirst($day) . ': ' . $hours . '\n';
            }

            $this->sendMessagem($session->session, $jid, $message);
            exit;
        }
    }

    public function verifyService($reponseArray, $session)
    {

        if (!$reponseArray['data']['message']['fromMe'] && !$reponseArray['data']['message']['fromGroup']) {



            $jid = $reponseArray['data']['message']['from'];

            // Remover o texto antes do '@'
            $numero_sem_arroba = substr($jid, 0, strpos($jid, '@'));

            // Extrair apenas os últimos 9 dígitos (número de celular)
            $jid = $numero_sem_arroba;

            $service = Chat::where('session_id',  $session->id)
                ->where('jid', $jid)
                ->where('active', 1)
                ->first();


            $customer = Customer::where('jid',  $jid)
                ->first();

            if (!$service) {
                $service = new Chat();
                $service->jid = $jid;
                $service->session_id = $session->id;
                $service->service_id = Utils::createCode();
                $service->await_answer = "init_chat";
                $service->save();
            }

            // if ($customer && $service->await_answer == null) {

            //     if ($reponseArray['data']['message']['type'] == "audio") {
            //         $service->await_answer = "await_human";
            //         $service->update();
            //         exit;
            //     }

            //     if ($service->await_answe == "await_human" || $service->await_answe == "in_service") {
            //         exit;
            //     }
            //     $service->await_answer = "init_chat";
            // }

            if ($service->await_answer == "init_chat") {

                if ($customer) {
                    $text = 'Olá ' . $customer->name . '! É bom ter você aqui novamente! 😊\n\n'
                        . 'Para Fazer seu pedido clique no link Abaixo \n'
                        . '🔗 ' . 'https://benjamin.enviazap.shop/checkout/?phone=' . $numero_sem_arroba
                        . '\nFácil, rápido e prático! 🚀';
                } else {
                    $customer = new Customer();
                    $customer->jid = $jid;
                    $customer->save();
                    if ($reponseArray['data']['message']['type'] == "audio") {
                        $service->await_answer = "await_human";
                        $service->update();
                        exit;
                    }
                    $text = 'Olá!! Para Fazer seu pedido \nclique no link Abaixo\n'
                        . '🔗 ' . 'https://benjamin.enviazap.shop/checkout/?phone=' . $numero_sem_arroba
                        . '\nFácil, rápido e prático! 🚀';
                }


                $this->sendMessagem($session->session, $customer->jid, $text);
                $text =  "Posso te ajudar em mais alguma coisa?";
                $options = [
                    "Falar com Atendente",
                    "Horário de Funcionamento"
                ];
                $this->sendMessagewithOption($session->session, $customer->jid, $text, $options);
                $service->await_answer = "menu1";
                $service->update();
                exit;
            }

            if ($service->await_answer == "menu1") {
                $response = $reponseArray['data']['message']['text'];

                switch ($response) {

                    case  "1";

                        $service->await_answer = "await_human";
                        $service->update();
                        $text =  "Por favor aguarde ,em instantes você será atendido(a).";
                        $this->sendMessagem($session->session, $customer->jid, $text);
                        exit;
                        break;

                    case '2';
                        $daysOfWeek = [
                            0 => 'domingo',
                            1 => 'segunda',
                            2 => 'terça',
                            3 => 'quarta',
                            4 => 'quinta',
                            5 => 'sexta',
                            6 => 'sábado',
                        ];

                        // Montar a lista de horários de funcionamento
                        $operatingHours = [];
                        foreach ($daysOfWeek as $index => $day) {
                            $slots = DB::table('available_slots_config')
                                ->where('day_of_week', $day)
                                ->select('start_time', 'end_time')
                                ->get();

                            if ($slots->isEmpty()) {
                                $operatingHours[$day] = 'Fechado';
                            } else {
                                $hours = [];
                                foreach ($slots as $slot) {
                                    $hours[] = $slot->start_time . ' às ' . $slot->end_time;
                                }
                                $operatingHours[$day] = implode(', ', $hours);
                            }
                        }

                        // Construir a mensagem com os horários de funcionamento
                        $message = '𝗛𝗢𝗥Á𝗥𝗜𝗢 𝗗𝗘 𝗙𝗨𝗡𝗖𝗜𝗢𝗡𝗔𝗠𝗘𝗡𝗧𝗢  ⏰\n';
                        foreach ($operatingHours as $day => $hours) {
                            $message .= ucfirst($day) . ': ' . $hours . '\n';
                        }

                        $this->sendMessagem($session->session, $jid, $message);
                        $text =  "Posso te ajudar em mais alguma coisa?";
                        $options = [
                            "Falar com Atendente",
                            "Horário de Funcionamento"
                        ];
                        $this->sendMessagewithOption($session->session, $customer->jid, $text, $options);
                        exit;
                        break;


                    default:
                        $service->erro =  $service->erro + 1;
                        $service->update();
                        $text =  "Opção inválida!";
                        $this->sendMessagem($session->session, $customer->jid, $text);
                        if ($service->erro > 0) {
                            $text =  "Por favor aguarde ,em instantes você será atendido(a).";
                            $this->sendMessagem($session->session, $customer->jid, $text);
                            $service->await_answer = "await_human";
                            $service->update();
                        }

                        break;
                }
            }

            // //dd($service);
            // if ($service->await_answer == "name") {
            //     $customer->name = $reponseArray['data']['message']['text'];
            //     $customer->update();
            //     $text = "Por favor " . $customer->name . " Digite seu Cep";
            //     $service->await_answer = "cep";
            //     $service->update();
            //     $this->sendMessagem($session->session, $customer->jid, $text);
            //     exit;
            // }
            // if ($service->await_answer == "cep") {

            //     $cep = $reponseArray['data']['message']['text'];
            //     $cep = Utils::returnCep($cep);


            //     if ($cep) {
            //         $customer->zipcode = $cep['cep'];
            //         $customer->public_place = $cep['logradouro'];
            //         $customer->neighborhood = $cep['bairro'];
            //         $customer->city = $cep['localidade'];
            //         $customer->state = $cep['uf'];
            //         $customer->update();
            //         $service->await_answer = "number";
            //         $service->update();
            //         $text = "Por Favor Digite o Número da residência";
            //     } else {
            //         $service->await_answer = "cep";
            //         $text = "Cep inválido Digite novamente!";
            //     }
            //     $this->sendMessagem($session->session, $customer->jid, $text);
            //     exit;
            // }
            // if ($service->await_answer == "number") {

            //     $text = $customer->getDistanceInKilometers();
            //     if ($text > 8) {
            //         $text = 'Infelizmente, não conseguimos fazer entregas na sua área, 🚫\n' .
            //             'pois a distância é maior do que a que costumamos atender. 😔\n' .
            //             'Sentimos muito por isso.\n\n' .
            //             'Se tiver alguma dúvida ou precisar de mais informações, por favor, nos avise. 🤔\n' .
            //             'Obrigado pela compreensão. 🙏';
            //         $this->sendMessagem($session->session, $customer->jid, $text);
            //         $service->active = 0;
            //         $service->update();
            //         exit;
            //     }


            //     $customer->number = $reponseArray['data']['message']['text'];
            //     $customer->update();
            //     $location = $customer->location . " \n  O Endereço está Correto ? ";
            //     $options = [
            //         "Sim",
            //         "Não"
            //     ];
            //     $this->sendMessagewithOption($session->session, $customer->jid, $location, $options);

            //     $service->await_answer = "cep_confirmation";
            //     $service->update();
            //     exit;
            // }
            // if ($service->await_answer == "cep_confirmation") {

            //     $response = $reponseArray['data']['message']['text'];

            //     switch ($response) {
            //         case  "1";

            //             $text = $customer->getDistanceInKilometers();
            //             if ($text > 8) {
            //                 $text = 'Infelizmente, não conseguimos fazer entregas na sua área, 🚫\n' .
            //                     'pois a distância é maior do que a que costumamos atender. 😔\n' .
            //                     'Sentimos muito por isso.\n\n' .
            //                     'Se tiver alguma dúvida ou precisar de mais informações, por favor, nos avise. 🤔\n' .
            //                     'Obrigado pela compreensão. 🙏';
            //                 $this->sendMessagem($session->session, $customer->jid, $text);
            //                 $service->active = 0;
            //                 $service->update();
            //                 exit;
            //             }


            //             $service->await_answer = "init_chat_1";
            //             $service->update();
            //             $text =  $customer->name . " \n  Seu cadastro foi Realizado \n com sucesso ";
            //             $this->sendMessagem($session->session, $customer->jid, $text);

            //             $text = "Por favor " . $customer->name . " Selecione uma das Opções .";
            //             $options = [
            //                 "Novo Pedido",
            //                 "Falar com um Atendente."
            //             ];
            //             $this->sendMessagewithOption($session->session, $customer->jid, $text, $options);
            //             exit;
            //             break;

            //         case "2";
            //             $service->await_answer = "cep";
            //             $service->update();
            //             $text = "Por favor Digite seu cep Novamente.";
            //             $this->sendMessagem($session->session, $customer->jid, $text);
            //             exit;
            //             break;

            //         default:
            //             $service->erro =  $service->erro + 1;
            //             $service->update();
            //             $text =  "Opção inválida!";
            //             $this->sendMessagem($session->session, $customer->jid, $text);
            //             if ($service->erro > 2) {
            //                 $text =  "Por favor aguarde ,em instantes você será atendido(a).";
            //                 $this->sendMessagem($session->session, $customer->jid, $text);
            //                 $service->await_answer = "await_human";
            //                 $service->update();
            //             }
            //             exit;
            //             break;
            //     }
            // }

            // if ($service->await_answer == "address_confirmation") {
            //     $response = $reponseArray['data']['message']['text'];

            //     switch ($response) {
            //         case  "1";

            //             $text = $customer->getDistanceInKilometers();
            //             if ($text > 8) {
            //                 $text = 'Infelizmente, não conseguimos fazer entregas na sua área, 🚫\n' .
            //                     'pois a distância é maior do que a que costumamos atender. 😔\n' .
            //                     'Sentimos muito por isso.\n\n' .
            //                     'Se tiver alguma dúvida ou precisar de mais informações, por favor, nos avise. 🤔\n' .
            //                     'Obrigado pela compreensão. 🙏';
            //                 $this->sendMessagem($session->session, $customer->jid, $text);
            //                 $service->active = 0;
            //                 $service->update();
            //                 exit;
            //             }


            //             $service->await_answer = "welcome";
            //             $service->update();

            //             $service->await_answer = "init_chat_1";
            //             $service->update();
            //             $text = "Por favor " . $customer->name . " Selecione uma das Opções .";
            //             $options = [
            //                 "Novo Pedido",
            //                 "Falar com um Atendente."
            //             ];
            //             $this->sendMessagewithOption($session->session, $customer->jid, $text, $options);
            //             exit;

            //         case '2';
            //             $service->await_answer = "cep";
            //             $service->update();
            //             $text = "Por favor Digite seu cep Novamente.";
            //             $this->sendMessagem($session->session, $customer->jid, $text);
            //             exit;
            //             break;
            //         default:
            //             break;
            //     }
            // }
            // if ($service->await_answer == "welcome") {


            //     $service->await_answer = "init_chat_1";
            //     $service->update();
            //     $text = "Por favor " . $customer->name . " Selecione uma das Opções .";
            //     $options = [
            //         "Novo Pedido",
            //         "Falar com um Atendente."
            //     ];
            //     $this->sendMessagewithOption($session->session, $customer->jid, $text, $options);
            //     exit;
            // }
        }
    }

    public function teste()
    {
        $texto = file_get_contents('php://input');
        $reponseJson = file_get_contents('teste.txt');

        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();

        //  dd($reponseArray['data']['sessionId']);


        // verifica se o serviço está em andamento
        $this->verifyService($reponseArray, $session);
    }
    
    public function mensagemEmMassa()
    {
        $devices = Device::get(); // IDs dos dispositivos
        // Configurar o Carbon para usar o fuso horário de São Paulo
        $now = Carbon::now('America/Sao_Paulo');


        $daysOfWeek = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sábado',
        ];

        $dayOfWeek =  $daysOfWeek[$now->dayOfWeek];
        // Obter a hora e minutos atuais
        $currentTime = $now->format('H:i:s');

        // Verifique se existe um slot disponível com os parâmetros fornecidos
        $exists = DB::table('available_slots')
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->exists();

        // Use dd() para depuração
        if (!$exists) {
            print_r('Fora de Data de Agendamento' . $currentTime);
            exit;
        }

        foreach ($devices as $device) {
            $mensagen = Messagen::where('device_id', null)->whereNot('number', "")->where('number', 'like', '55119%')->limit(1)->get();
            // Obtém o número de mensagens enviadas nas últimas horas
            $messageCount = $device->message_count_last_hour;


            // Verifica se o número de mensagens enviadas nas últimas horas é menor ou igual a 39
            if ($messageCount <= 39 && isset($mensagen)) {

                foreach ($mensagen as $mensage) {

                    $imagen = asset($mensage->imagem->caminho);
                    $mensage->device_id = $device->id;
                    $mensage->update();

                    $this->sendImage($device->session, $mensage->number, $imagen, $mensage->messagem);

                    echo 'enviado : ' . $mensage->number . ' <br>';
                }
            }
        }
    }
    public function storeAvaliacao(Request $request)
    {
        //    dd($request->all());


        // Crie uma nova instância de Avaliacao
        $avaliacao = new Avaliacao();

        // Preencha os campos com os dados do formulário
        $avaliacao->nota = $request->input('rate');
        $avaliacao->comentario = $request->input('comentario');
        $avaliacao->telefone = $request->input('telefone');
        $avaliacao->ip_device = $request->input('ip_device');
        $avaliacao->colaborador_id = $request->input('colaborador_id');
        $avaliacao->nota = $request->input('nota');


        // Salve a avaliação no banco de dados
        $avaliacao->save();

        // Você pode retornar uma resposta ou redirecionar o usuário após salvar a avaliação
        return view("front.avaliacao.obrigado");
    }
    public function sendImage($session, $phone, $nomeImagen, $detalhes)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "image" => array(
                    "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
                ),
                "caption" => $detalhes
            ),
            "delay" => 3
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        //  file_put_contents(Utils::createCode() . ".txt", $response);

        curl_close($curl);
    }
    public function avaliacao(Request $request)
    {


        if ($request->name_rota) {

            // Buscar colaborador com base no colaborador_od associado à rota
            $rota = Route::where("name",  urldecode($request->name_rota))->first();




            if (!isset($rota->colaborador_id)) {
                echo json_encode(array("Mensagem" => "Sem Colaborador Vinculado"));
                exit;
            } else {
                $colaborador = Colaborador::find($rota->colaborador_id);
                return view("front.avaliacao.index", compact('colaborador'));
            }
        }

        $colaborador = Colaborador::find($request->colaborador);

        if (!$colaborador) {
            echo json_encode(array("Mensagem" => "Sem Colaborador Vinculado"));
            exit;
        } else {
            return view("front.avaliacao.index", compact('colaborador'));
        }
    }
    public function sendMessagem($session, $phone, $texto)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }
    public function sendMessagewithOption($session, $phone, $text, $options)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "text" => $text,
                "options" => $options,
            ),
            "delay" => 3
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    public function sendAudio($session, $phone)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "number": "' . $phone . '",
            "message": {
                "audio": {
                    "url" : "http://localhost:3333/static/audio/2F49EE65082AB66116EBFC03DC26C44D.ogg?sessionId=JOSE_1&messageId=2F49EE65082AB66116EBFC03DC26C44D"
                }
            },
            "delay": 0
        }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
