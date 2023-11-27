<?php

namespace App\Controllers;


use App\Models\FolhaPontoModel;
use Carbon\Carbon;



class FolhaRegistrosController extends BaseController
{
    public function index( )
    {
        $session = session();

        $model = new FolhaPontoModel();

        $firstFilter = $this->request->getGet('filtro');
        $secondFilter = $this->request->getGet();

        if($firstFilter)
        {
            $itens = $this->getFilterData($firstFilter);

        }else if ($secondFilter)
        {
            $itens = $model->where('entry_date >=', $secondFilter['entry_date'])
                ->where('entry_date <=', $secondFilter['exit_date'])
                ->findAll();
        }else
        {
            $itens = $this->getFilterData();
        }

        $session->set('filtro', $firstFilter);



        if (!empty($itens[0]))
        {

            $date = Carbon::create($itens[0]->entry_date);

            //Caso nao tenha dados
        }else
        {
            $date = null;
        }



        return view('pages/registros_pontos', [
            'itens' => $itens,
            'pager' => $model->pager,
            'cards'=>  $this->getInfoCards(dateRequired: $date),
            'total'=> $this->sumHours($itens)

        ]);
    }

    /**
     * A função preenche dados necessários para cards com base na condição fornecida.
     *
     * @param int $conditions Número da condição a ser processada (de 1 a 5).
     * @param array|null $data Dados adicionais que podem ser fornecidos (opcional).
     * @return array Retorna um array contendo os dados preenchidos para os cards.
     */

    private function getInfoCards($conditions = 1, array &$data = null, Carbon $dateRequired = null) : array
    {

        if (!$dateRequired)
        {
            return [];
        }else
        {
            // Switch para diferentes períodos do ano
            switch ($conditions)
            {
                case 1:
                    $dateCurrent = clone $dateRequired;
                    // Obtendo o início e o fim da semana com base na data atual
                    $startDate = $dateCurrent->startOfWeek()->format('Y-m-d');
                    $endDate = $dateCurrent->endOfWeek()->format('Y-m-d');
                    $title = "Esta Semana";
                    break;
                case 2:
                    $dateCurrent = clone $dateRequired;
                    $dateCurrent->subWeek();
                    // Pegando a data da semana passada
                    $startDate = $dateCurrent->startOfWeek()->format('Y-m-d');
                    $endDate = $dateCurrent->endOfWeek()->format('Y-m-d');
                    $title = "Última Semana";
                break;
                case 3:

                    $dateCurrent = clone $dateRequired;
                    // Obtendo o primeiro e o último dia do mês com base na data atual
                    $startDate = $dateCurrent->startOfMonth()->format('Y-m-d');
                    $endDate = $dateCurrent->endOfMonth()->format('Y-m-d');
                    $title = "Este Mês";

                    break;
                case 4:
                    $dateCurrent = clone $dateRequired;
                    $dateCurrent->subMonth();
                    // Obtendo o primeiro e o último dia do mês com base na data atual
                    $startDate = $dateCurrent->startOfMonth()->format('Y-m-d');
                    $endDate = $dateCurrent->endOfMonth()->format('Y-m-d');
                    $title = "Último Mês";
                    break;
                case 5:
                    $datecurrent = clone $dateRequired ;

                    // Obtendo o primeiro e o último dia do ano com base na data atual
                    $startDate = $datecurrent->firstOfYear()->format('Y-m-d');
                    $endDate = $datecurrent->lastOfYear()->format('Y-m-d');

                    // Título para o card
                    $title = "Total";
                    break;

            }
            $model = new FolhaPontoModel();

            // Use o Query Builder do modelo
            $period = $model->where('entry_date >=', $startDate)
                ->where('entry_date <=', $endDate)
                ->findAll();


            $formatedHours =  $this->sumHours($period);

            //Obtem as horas requeridas daquele periodo
            $hoursRequired = $this->getHoursRequired($startDate,$endDate);



            //Array de dados a serem utilizados na view
            $data[] = ['title'=> $title, 'hours'=> $formatedHours,'hoursrequired'=> $hoursRequired ];


            // Se o número de condições ainda for menor que 5 (1 a 4), então há mais condições para processar
            if ($conditions < 5)
            {
                // Incrementa o valor de $conditions para avançar para a próxima condição
                $conditions ++;

                // Chama recursivamente a função atual, passando a próxima condição e os dados existentes
                $this->getInfoCards($conditions, $data,$dateRequired);
            }


            return $data;
        }
    }


    /**
     * Calcula as horas requeridas entre duas datas, excluindo finais de semana e feriados.
     *
     * @param string $dateStart Data de início no formato 'YYYY-MM-DD'.
     * @param string $dateEnd Data de término no formato 'YYYY-MM-DD'.
     *
     * @return string Retorna o total de horas requeridas no formato 'HH:MM'.
     */
    private function getHoursRequired(string $startDate, string $endDate) : string
    {
        // Obtém o ano da data de início
        $year = substr($startDate, 0, 4);

        // Obtém a lista de feriados do ano
        $hollidays = config('HollidaysOfYear')->getHollidays($year);

        // Cria um objeto Carbon a partir da data de início
        $dateCurrent = Carbon::createFromDate($startDate) ;

        // Inicializa o contador de dias úteis
        $numberOfDays = 0;

        // Loop até a data atual ser menor ou igual à data de término
        while ($dateCurrent->lessThanOrEqualTo(Carbon::createFromDate($endDate)))
        {
            if(!$dateCurrent->isWeekend() && !in_array($dateCurrent->format('Y-m-d'), $hollidays))
            {
                $numberOfDays ++;
            }
            $dateCurrent->addDay();
        }

        // Calcula o número total de horas
        $numberOfHors = $numberOfDays * 8;

        // Divide as horas em horas e minutos
        $hours = floor($numberOfHors);
        $minutes = ($numberOfHors - $hours) * 60;

        // Formata o resultado como 'HH:MM'
        $stringFormated = sprintf("%02d:%02d",$hours, $minutes);

        return $stringFormated;

    }

    private function sumHours(array $period)
    {
        //declaracao variavel
        $totralMinutesOfDay = 0;

        foreach ($period as $day)
        {
            //obtem a horas feitas no dia
            $stringHoursOfDay = $day->getHoursOfDay();

            //separa as horas e minutos
            list($hour, $minutes) = explode(':', $stringHoursOfDay);

            //transforma hora em minutos e soma aos minutos
            $totalMinutes = $hour * 60 + $minutes;

            //incrementa a variavel
            $totralMinutesOfDay += $totalMinutes;

        }
        //convertendo minutos em horas
        $hours = floor($totralMinutesOfDay / 60);
        //obetend apenas minutos
        $minutes = $totralMinutesOfDay % 60;

        //formatando a string
        $formatedHours = sprintf( "%02d:%02d",$hours, $minutes);

        //retorna o total
        return $formatedHours;
    }

    /**
     * Filtra os dados do tipo Folha de Ponto com base na string fornecida.
     *
     * @param string $filtro Parâmetro para filtragem.
     *
     * @return array dados filtrados do tipo FolhaPonto.
     */
    private function getFilterData(string $filter = '') : array
    {
        $dateCurrent = Carbon::now();

        switch ($filter)
        {
            //dados desta semana
            case '1':
                $startDate = $dateCurrent->startOfWeek()->format('Y-m-d');
                $endDate = $dateCurrent->endOfWeek()->format('Y-m-d');
                break;
            //dados da semana anterior
            case '2':
                $dateCurrent->subWeek();
                $startDate = $dateCurrent->startOfWeek()->format('Y-m-d');
                $endDate = $dateCurrent->endOfWeek()->format('Y-m-d');
                break;
            //dados deste mes
            case '3':
                $startDate = $dateCurrent->firstOfMonth()->format('Y-m-d');
                $endDate = $dateCurrent->lastOfMonth()->format('Y-m-d');
                break;
            //dados mes anterior
            case '4':
                $dateCurrent = Carbon::now()->subMonth();

                $startDate = $dateCurrent->firstOfMonth()->format('Y-m-d');
                $endDate = $dateCurrent->lastOfMonth()->format('Y-m-d');
                break;
            //dados deste ano
            case '5':
                $startDate = $dateCurrent->firstOfYear()->format('Y-m-d');
                $endDate = $dateCurrent->lastOfYear()->format('Y-m-d');
                break;
            //dados por padrao desta semana
            default:
                $startDate = $dateCurrent->startOfWeek()->format('Y-m-d');
                $endDate = $dateCurrent->endOfWeek()->format('Y-m-d');

                break;
        }

        //obetem dados pela filtragem
        $model = new FolhaPontoModel();
        $period = $model->where('entry_date >=', $startDate)
            ->where('entry_date <=', $endDate)
            ->findAll();

        return $period; //retorna Collection de Folha de pontos
    }

}
