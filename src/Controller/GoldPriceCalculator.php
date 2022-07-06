<?php

class GoldPriceCalculator{

    public static function calculateAveragePrice($start,$stop){
        $format = 'Y-m-d\TH:i:sP';


        $dStart = DateTime::createFromFormat($format, $start);
        $dStop = DateTime::createFromFormat($format, $stop);

        if($dStart && $dStart->format($format) == $start && $dStop && $dStop->format($format) == $stop ){
            $start_date = date('Y-m-d', strtotime($start));
            $stop_date = date('Y-m-d', strtotime($stop));

            $url = "http://api.nbp.pl/api/cenyzlota/".$start_date."/".$stop_date."/";

            $response = file_get_contents($url);
            $json_array=json_decode($response,true); 

            //print_r($json_array);

            $avg = round(array_sum(array_column($json_array, 'cena'))/count($json_array), 2);
            $real_start = $json_array[0]['data'];
            $real_stop = $json_array[count($json_array)-1]['data'];

            return [
                'from' => $real_start,
                'to' => $real_stop,
                'avg' => $avg
            ];
        }
        else{
            return [];
        }
    }
}