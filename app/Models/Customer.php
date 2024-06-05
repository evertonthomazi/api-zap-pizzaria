<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $appends = [
        'phone',
        'location',
        'display_created_at',
        'delivery_fee'
    ];
    protected $fillable = [
        'name',
        'jid',
        'zipcode',
        'public_place',
        'neighborhood',
        'city',
        'complement',
        'state',
        'number',
        'created_at',
        'updated_at'
    ];

    protected $googleApiKey;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->googleApiKey = env('GOOGLE_MAPS_API_KEY');
    }


    // Adicione este método ao seu modelo Customer
    public function setJidAttribute($value)
    {
        // Remover todos os caracteres que não sejam números
        $value = preg_replace('/[^0-9]/', '', $value);

        // Verificar se o valor começa com "5511"
        if (strpos($value, '5511') !== 0) {
            $value = '5511' . $value;
        }

        $this->attributes['jid'] = $value;
    }

    // Modifique o método getPhoneAttribute
    public function getPhoneAttribute()
    {
        // Remova os primeiros 4 caracteres ("5511") antes de retornar o número de telefone
        return substr($this->jid, 4);
    }


    public function getDisplayCreatedAtAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function getLocationAttribute()
    {
        return 'CEP: ' . $this->zipcode . " \n " .
            '' . $this->public_place . " \n " .
            'N° : ' . $this->number . " \n " .
            'Bairro: ' . $this->neighborhood . " \n " .
            'Cidade: ' . $this->city . " \n " .
            'Estado: ' . $this->state . " \n ";
    }

    public function getDeliveryFeeAttribute()
    {
        $address1 = 'Rua Nova Providência, 593, Parque Bologne, SP';
        $address2 = "{$this->number} {$this->public_place}, {$this->neighborhood}, {$this->city}, {$this->state}";

        $coords1 = $this->getCoordinates($address1);
        $coords2 = $this->getCoordinates($address2);

        if ($coords1 && $coords2) {
            list($distance, $duration) = $this->getDistance($coords1, $coords2);
            return $this->calculateDeliveryFeeAmount($distance);
        } else {
            return null;
        }
    }

    public function getLocationLink()
    {
        $address1 = 'Rua Nova Providência, 593, Parque Bologne, SP';
        $origin = urlencode($address1);
        $destination = urlencode($this->location);
        return "https://www.google.com/maps/dir/?api=1&origin={$origin}&destination={$destination}";
    }

    public function getDistanceInKilometers()
    {
        $address1 = 'Rua Nova Providência, 593, Parque Bologne, SP';
        $address2 = "{$this->number} {$this->public_place}, {$this->neighborhood}, {$this->city}, {$this->state}";

        $coords1 = $this->getCoordinates($address1);
        $coords2 = $this->getCoordinates($address2);
        if ($coords1 && $coords2) {
            list($distance, $duration) = $this->getDistance($coords1, $coords2);

            return intval($distance);
        } else {
            return null;
        }
    }

    private function getCoordinates($address)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
        $response = Http::get($url, [
            'address' => $address,
            'key' => $this->googleApiKey,
        ]);

        $data = $response->json();

        if (!empty($data['results'])) {
            $location = $data['results'][0]['geometry']['location'];
            return [$location['lat'], $location['lng']];
        }

        return null;
    }

    private function getDistance($originCoords, $destinationCoords)
    {
        $origins = implode(',', $originCoords);
        $destinations = implode(',', $destinationCoords);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
        $response = Http::get($url, [
            'origins' => $origins,
            'destinations' => $destinations,
            'key' => $this->googleApiKey,
        ]);

        $data = $response->json();

        if (!empty($data['rows'][0]['elements'][0]['distance']) && !empty($data['rows'][0]['elements'][0]['duration'])) {
            $distanceText = $data['rows'][0]['elements'][0]['distance']['text'];
            $distanceValue = $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // Convert to kilometers
            return [$distanceValue, $data['rows'][0]['elements'][0]['duration']['text']];
        }

        return [null, null];
    }

    private function calculateDeliveryFeeAmount($distance)
    {
        if ($distance <= 1) {
            return 3.00;
        } elseif ($distance > 1 && $distance <= 2) {
            return 4.00;
        } else {
            return 4.00 + (($distance - 2) * 2.00);
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
