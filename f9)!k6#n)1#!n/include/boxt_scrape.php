<?php
include($app_key.'/model/BoxtQ.php');
include($app_key.'/model/BoxtA.php');
echo "https://www.boxt.co.uk/api/screenings/questions?product_type=boiler";
$data = file_get_contents("https://www.boxt.co.uk/api/screenings/questions?product_type=boiler");
$data = json_decode($data);
// print_r($data->questions[2]);exit;
foreach($data->questions as $k=>$question){
    print_r($question);
    $answers = [];
    foreach($question->answers as $a){
        print_r($a);
        $answers[]=$a->id;
        BoxtA::create(null,[
            "boxt_id"=>$a->id,
            "icon"=>$a->icon,
            "text"=>$a->text,
            "info"=>$a->info,
            "nextStatus"=>$a->nextStatus,
            "queryFragment"=>$a->queryFragment,
            "tag"=>$a->tag,
            "image"=>$a->image,
            "value"=>$a->value,
        ]);
    }
    BoxtQ::create(null,[
        "boxt_id"=>$question->id,
        "text"=>$question->text,
        "subtitle"=>$question->subtitle,
        "additionalInfo"=>$question->additionalInfo,
        "helpText"=>$question->helpText,
        "helpTemplate"=>$question->helpTemplate,
        "template"=>$question->template,
        "tag"=>$question->tag,
        "productType"=>$question->productType,
        "dependentAnswers"=>implode(",",$question->dependentAnswers),
        "ignoreIfAnswered"=>implode(",",$question->ignoreIfAnswered),
        "answers"=>implode(",",$answers),
    ]);
}
// print_r($data);
?>