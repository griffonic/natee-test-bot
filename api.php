<?php
$access_token = "EAANPCpYfHBABABrwKYkaYIOfGnM3CZAWQN0cevbfQd2gQ9ZAlG2q3x7yIsBq6YGww1Qy8eAbrsj8ic4Mu22e5eZBtYmCawl1uCuASpP4LnPCecZA8C16cU09OmwRswZBILFlPZB8z4mudQLJG6R1pw9518Aht93KJwdBe8j5TH1wZDZD";
$verify_token = "natee";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
 $challenge = $_REQUEST['hub_challenge'];
 $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
 echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = '';
/**
 * Some Basic rules to validate incoming messages
 */

$api_key="BqFrmw21CCInX98thNpwKnwa0UBTkiyK";
$url = 'https://api.mlab.com/api/1/databases/natee-test-bot1/collections/test1?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/natee-test-bot1/collections/test1?apiKey='.$api_key.'&q={"question":"'.$message.'"}');
$data = json_decode($json);
$isData=sizeof($data);
if (strpos($message, 'สอนเป็ด') !== false) {
  if (strpos($message, 'สอนเป็ด') !== false) {
    $x_tra = str_replace("สอนเป็ด","", $message);
    $pieces = explode("|", $x_tra);
    $_question=str_replace("[","",$pieces[0]);
    $_answer=str_replace("]","",$pieces[1]);
    //Post New Data
    $newData = json_encode(
      array(
        'question' => $_question,
        'answer'=> $_answer
      )
    );
    $opts = array(
      'http' => array(
          'method' => "POST",
          'header' => "Content-type: application/json",
          'content' => $newData
       )
    );
    $context = stream_context_create($opts);
    $returnValue = file_get_contents($url,false,$context);
    $message_to_reply = 'ขอบคุณที่สอนเป็ด';
  }
}else{
  if($isData >0){
   foreach($data as $rec){
     $message_to_reply = $rec->answer;
   }
  }else{
    $message_to_reply = 'ก๊าบบ คุณสามารถสอนให้ฉลาดได้เพียงพิมพ์: สอนเป็ด[คำถาม|คำตอบ]';
  }
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
    "attachment":{
      "type":"image", 
      "payload":{
        "url":"http://static.tvtropes.org/pmwiki/pub/images/anime_font_b_detective_b_font_font_b_conan_b_font_case_closed_font_b_edogawa.jpg", 
        "is_reusable":true
      }
    }
  }
    

}';

// "message":{
//         "text":"'.$message_to_reply.'",
//         "attachment":{
//           "type":"image", 
//           "payload":{
//             "url":"http://static.tvtropes.org/pmwiki/pub/images/anime_font_b_detective_b_font_font_b_conan_b_font_case_closed_font_b_edogawa.jpg", 
//             "is_reusable":true
//           }
//         }
//     },

    
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.

curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}
?>