<?php
require __DIR__.'/../src/functions.php';

//DBController
$db = new dbController();

//GET,SESSION変数からルームID,ユーザIDを受け取り
$room_id = empty($_GET['rn']) ? 0 : (string)$_GET['rn'];
$user_id = 1;

$db->connect();

//ルームに入るのかルームの新規作成か
if($room_id == 0)
{
  //ルームの新規作成の時
  //5桁の乱数
  while(true)
  {
    $room_id = rand(10000, 99999);

    //既に同じ番号のルームが存在しなかったらループを抜ける
    if($db->count("SELECT COUNT(*) FROM room WHERE room_id=".$room_id) == 0)
    {
      break;
    }
  }
  //DBにルーム情報を追加
  $stmt = $db->prepare('INSERT INTO room(room_id, room_men1, room_start_time) VALUES(:id, :men1, :start)');
  $stmt->bindValue(':id', $room_id, PDO::PARAM_INT);
  $stmt->bindValue(':men1', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':start', (int)date("YmdHis"), PDO::PARAM_INT);
  $stmt->execute();
  print("<p>ルームを作成しました。</p>");
}
else
{
  //テスト用
  $user_id=4;
  //既存のルームに入るとき
  if($db->count("SELECT COUNT(*) FROM room WHERE room_id={$room_id}") == 1)
  {
    $row = $db->query("SELECT * FROM room WHERE room_id={$room_id}");

    //何番目の参加者か判定
    if($row[0]['room_men2']==0 || $row[0]['room_men2']==$user_id) $number = 2;
    elseif($row[0]['room_men3']==0 || $row[0]['room_men3']==$user_id) $number = 3;
    elseif($row[0]['room_men4']==0 || $row[0]['room_men4']==$user_id) $number = 4;
    else die("既にルームに4人入っています。");

    print("<p>あなたは{$number}番目のルーム参加者です。</p>");

    //DBにユーザーを追加
    $stmt = $db->prepare("UPDATE room SET room_men{$number}=:men");
    $stmt->bindValue(':men', $user_id, PDO::PARAM_INT);
    $stmt->execute();
  }
  else
  {
    //指定されたIDがDBにないとき
    die("そのようなルームはありません。");
  }
}
print("<p>ルームIDは".$room_id."です。</p>");

$db->close();
