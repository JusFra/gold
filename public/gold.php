<?php

$input_data = '
{
    "from": "2021-01-04T00:00:00+00:00", 
    "to": "2021-01-04T00:00:00+00:00"
}';

$score = getPrice($input_data);
echo $score;






function getPrice($input_data)
{
    $data = json_decode($input_data, true);
    $start = $data['from'];
    $stop = $data['to'];

    if(validateDate($start) AND validateDate($stop) == 1)
    {
        $start_date = date('Y-m-d', strtotime($start));
        $stop_date = date('Y-m-d', strtotime($stop));

        $url = "http://api.nbp.pl/api/cenyzlota/".$start_date."/".$stop_date."/";

        $response = file_get_contents($url);
        $json_array=json_decode($response,true); 

        //print_r($json_array);

        $avg = round(array_sum(array_column($json_array, 'cena'))/count($json_array), 2);
        $real_start = $json_array[0]['data'];
        $real_stop = $json_array[count($json_array)-1]['data'];

        $score = [
            "from" => $real_start,
            "to" => $real_stop,
            "avg" => $avg
        ];

        return json_encode($score);
    }
    else
    {
        return http_response_code(400);
    }
}

function validateDate($date, $format = 'Y-m-d\TH:i:sP')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}