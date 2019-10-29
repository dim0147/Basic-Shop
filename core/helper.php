<?php

function printB($arr){
    print("<pre>".print_r($arr,true)."</pre>");
}

function mergeResult($arrOrigin, $arrMerge, $keyToValidate, $data, $keyOfElement = false){
    $arrResult = [];  
    if(count($data) <= 0) // count: how many element in 1 array
        return $arrResult; // NULL ???
    foreach($data as $e){ 
        $idProduct = $e[$keyToValidate];  

        if(!isset($arrResult[$idProduct])){   //  if empty arrResult don't have current id product
            for($i = 0; $i < count($arrOrigin); $i++){
                $nameKeyOrigin = $arrOrigin[$i];
                $nameKeyMerge = $arrMerge[$i];
                $valueOfKeyOrigin =  $e[$nameKeyOrigin];  //  get KEY origin of duplicate product, eg: $e['name']
                if($keyOfElement && array_key_exists($nameKeyOrigin, $keyOfElement)){
                    //  Get value from addition field, eg: $e['image_id'] = 3, 3 is assign to keyMergeElement
                    //  $keyOfElement = ['name' => 'image_id'], $nameKeyOrigin = 'name' 
                    // =>  $keyOfElement[$nameKeyOrigin] => $keyOfElement['name'] = 'image_id'
                    // => $e['image_id'] = 1 => $keyMergeElement = 1
                    $keyMergeElement = $e[$keyOfElement[$nameKeyOrigin]]; 
                    //  Add like this $e[listImage][idImage] = nameImage, listImage is arrMerge
                    // ['listImg'] => [
                    //     ['idImage'] => nameImage,
                    //     ['idImage'] => nameImage,
                    // ]
                    $e[$nameKeyMerge][$keyMergeElement] = $valueOfKeyOrigin;    
                }
                else
                    $e[$nameKeyMerge] = [$valueOfKeyOrigin]; //  create empty Caterogy list and add first value 
                $arrResult[$idProduct] = $e;  //  Add to empty array
            }
        }

        else{   //  Exist
            for($i = 0; $i < count($arrOrigin); $i++){
                $nameKeyOrigin = $arrOrigin[$i];
                $nameKeyMerge = $arrMerge[$i];
                $valueOfKeyOrigin =  $e[$nameKeyOrigin];  //  get KEY origin of duplicate product, eg: $e['name']
                if (!in_array($valueOfKeyOrigin, $arrResult[$idProduct][$nameKeyMerge])){   //  Check if have already
                     //  Check if addition key from specific origin field 
                    if($keyOfElement && array_key_exists($nameKeyOrigin, $keyOfElement)){
                        $keyMergeElement = $e[$keyOfElement[$nameKeyOrigin]];
                        $arrResult[$idProduct][$nameKeyMerge][$keyMergeElement] = $valueOfKeyOrigin;
                    }
                    else
                        array_push($arrResult[$idProduct][$nameKeyMerge], $valueOfKeyOrigin);  // If not push to list category
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

function checkEmpty($arr){
    foreach($arr as $val){
        if(empty($val) && $val !== 0)
            return true;
    }
    return false;
}


function removeFiles($arrFileName, $path){
    foreach($arrFileName as $file){
        unlink($path. '/' . $file);
    }
    return true;
}

function createQuery($arrValue, $arrUpdate = false){
    $arr = [];
    if($arrUpdate !== false){    //  QUERY FOR UPDATE FIELD
        foreach ($arrValue as $field => $val){
            if(!is_numeric($val))
                $val = addApostrophe($val);
            $arr[] = $field . " = " . $val;
        }
        $arr = implode(',', $arr);
        return $arr;
    }else{
        foreach($arrValue as $value){   //  QUERY FOR ADD VALUE
                if ($value == 'NULL' || $value == 'DEFAULT' || is_numeric($value))
                    $arr[] =  $value;
                else
                    $arr[] = '"' . $value .'"';
        }
        $arr = implode(',', $arr);
        $arr = str_pad($arr, strlen($arr) + 1, "(", STR_PAD_LEFT);
        $arr = str_pad($arr, strlen($arr) + 1, ")", STR_PAD_BOTH);
        return $arr;
    }
}

function createCheckQuery($arr){
    $values = [];
    foreach($arr as $field => $val){
        if(!is_numeric($val))
            $val = addApostrophe($val);
        $values[] = $field . "=" . $val;
    }
    $values = implode(' AND ', $values);
    return $values;
}

function checkEmptyFile($file, $type){
    if ($type === 1){   //  single
        if($file['error'] === 0)
            return false;
        else
            return true;
    }

    if($type === 2){    //  multipe
        if(empty($file['size'][0]))
            return true;
        else
            return false;
    }
        
}

function addApostrophe($string){
    return '"' . $string . '"';
}

function getFirstKey($arr){
    foreach($arr as $key => $val){
        return $key;
    }
}

function getArrFromJSON($JSON){
    $arr = json_decode($JSON);
    return (array)$arr;
}

?>