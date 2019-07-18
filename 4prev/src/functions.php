<?php
//  エラーの表示
ini_set( 'display_errors', 1 );
/**
 * DB制御クラス
 */
class dbController
{
  private $dsn = 'mysql:dbname=noyatsu_rev;host=localhost';
  private $user = 'noyatsu_rev';
  private $password = 'noyarev';
  public $db;

  public function __constract(){
    $this->connect();
  }

  //DB接続用メソッド
  public function connect(){
    try {
      $this->db = new PDO($this->dsn, $this->user, $this->password);
    } catch (PDOException $e) {
      print('Connection failed: '.$e->getMessage());
      die();
    }
    $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  }

  //DB接続解除メソッド
  public function close(){
    $this->db = null;
  }

  //クエリ実行メソッド
  public function query($sql){
    $stmt = $this->db->query($sql);
    //1つの連想配列ですべての結果を返す
    $res = array();
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $res[$i] = $row;
      $i++;
    }
    return $res;
  }

  public function prepare($sql){
    $stmt = $this->db->prepare($sql);
    return $stmt;
  }


  public function count($sql){
    try {
      $stmt = $this->db->query($sql);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die($e-getMessage());
    }
    return $row["COUNT(*)"];
  }
}
