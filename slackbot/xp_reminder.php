<?php
$webhook_url = "https://hooks.slack.com/services/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";

if ($_POST["user_name"] != "slackbot") {
  $remind_datetime = date("H:i", time() + 60 * 60 * 8);
  $text = '<@' . $_POST["user_name"] . '> 次は' . $remind_datetime . 'にもらえるよ';
  $payload = array("text" => $text, "link_names" => 1);

  $json_string = json_encode($payload);

  $slack_call = curl_init($webhook_url);
  curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
  curl_setopt($slack_call, CURLOPT_CRLF, true);
  curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "Content-Length: " . strlen($json_string))
  );
  
  $result = curl_exec($slack_call);
  curl_close($slack_call);
}
?>