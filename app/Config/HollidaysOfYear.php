<?php

namespace config;

use CodeIgniter\Config\BaseConfig;
// Carregue a classe Time
use CodeIgniter\I18n\Time;
use GuzzleHttp\Client;

class HollidaysOfYear extends BaseConfig
{
    public function getHollidays($year = null)
    {
        // if o ano nao for atribuido passa o ano actual
        if ($year == null){
            $year = Time::now()->getYear();
        }
        //definindo a chave da api
        $apiKey = "ExampleKey";

        //definindo codigo do pais
        $countryCode = 'PT';

        // Criando uma nova instância do cliente Guzzle HTTP
        $client = new Client();

        $reponse = $client->get("https://date.nager.at/Api/v2/PublicHolidays/{$year}/{$countryCode}?apiKey={$apiKey}");

        $hollidays  = json_decode($reponse->getBody(), true);

        $holidaysDates = [];

        foreach ($hollidays as $key => $day)
        {

            $holidaysDates[] = $day['date'];


        }

        return $holidaysDates;
    }
}