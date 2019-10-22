<?php

function printB($arr){
    print("<pre>".print_r($arr,true)."</pre>");
}

function mergeResult($arrOrigin, $arrMerge, $keyToValidate, $data){
    $arrResult = [];  
    if(count($data) <= 0) // count: how many element in 1 array
        return $arrResult; // NULL ???
    foreach($data as $e){ 
        $idProduct = $e[$keyToValidate];  

        if(!isset($arrResult[$idProduct])){   //  if empty array don't have current id product
            for($i = 0; $i < count($arrOrigin); $i++){
                $e[$arrMerge[$i]] = [$e[$arrOrigin[$i]]]; //  create empty Caterogy list and add first value 
                $arrResult[$idProduct] = $e;  //  Add to empty array
            }
        }

        else{   //  Exist
            for($i = 0; $i < count($arrOrigin); $i++){
                $categoryName =  $e[$arrOrigin[$i]];  //  get name origin of duplicate product
                if (!in_array($categoryName, $arrResult[$idProduct][$arrMerge[$i]])){   //  Check if have already
                    array_push($arrResult[$idProduct][$arrMerge[$i]], $categoryName);  // If not push to list category
                }
            }
        }
    }

    return $arrResult;
}

function setHTTPCode($code, $message = NULL){
    http_response_code($code);
    if($message != NULL)
        echo $message;
    return;
}

function createRanDomString($length = 24){
    $characters = '_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randString = '';
    for ($i = 0; $i < $length; $i++){
        $randString .= $characters[rand(0, strlen($characters) -1)];
    }
    return $randString;
}

function getExtFile($file){
        $infoFile = pathinfo($file);
        return $infoFile['extension']; 
}

?>