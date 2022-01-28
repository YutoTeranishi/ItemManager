<?php
  print_r($_POST);
  print_r(is_null($_POST['item_id']));

  if(!empty($_POST)){

    $user = 'root';
    $password = '';
    $dbName = 'ItemManager';
    $host ='localhost:3306';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

    try {
      //サーバ接続
      $pdo = new PDO($dsn,$user,$password);
      $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
      //例外をスルーする設定
      $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      //echo "データベース{$dbName}に接続しました。";


      $sql = "INSERT INTO price_table(item_id,times_id,price_gen,rate_genToyen,amount_Item,total_amount_Items,trans_fee_in_China,international_shipment)
              VALUES(:item_id,:times_id,:price_gen,:rate_genToyen,:amount_Item,:total_amount_Items,:trans_fee_in_China,:international_shipment)";
      //プリペアドステートメント作成

      $stm = $pdo->prepare($sql);
      $stm->bindValue(':item_id',$_POST['item_id']);
      $stm->bindValue(':times_id',$_POST['times_id']);
      $stm->bindValue(':price_gen',$_POST['price_gen']);
      $stm->bindValue(':rate_genToyen',$_POST['rate_genToyen']);
      $stm->bindValue(':amount_Item',$_POST['amount_Item']);
      $stm->bindValue(':total_amount_Items',$_POST['total_amount_Items']);
      $stm->bindValue(':trans_fee_in_China',$_POST['trans_fee_in_China']);
      $stm->bindValue(':international_shipment',$_POST['international_shipment']);


      $stm->execute();

      //結果の取得(連想配列)
      //$result = $stm->fetchAll(PDO::FETCH_ASSOC);

    }catch (\Exception $e) {
      echo '<span class="error">エラーが保存できませんでした。</span><br>';
      echo $e->getMessage();
      exit();
    }
  }


 ?>
<!DOCTYPE html>

<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/style.css">

  </head>

  <body>
    <h1>新規登録画面</h1>
    <form action="newResistration.php" method="post">
    <div class="item">
      <div class="title">第何回購入</div>
      <input type="number" name="times_id" value="" class="price">
    </div>

    <div class="item">
      <div class="title">アイテムID</div>
      <input type="number" name="item_id" value="" class="price">
    </div>

    <div class="item">
      <div class="title">値段(元)</div>
      <input type="number" name="price_gen" value="" class="price">
    </div>

    <div class="item">
      <div class="title">元 ->円変換レート</div>
      <input type="number" name="rate_genToyen" value="" class="price">
    </div>

    <div class="item">
      <div class="title">購入個数</div>
      <input type="number" name="amount_Item" value="" class="price">
    </div>

    <div class="item">
      <div class="title">同時発送個数</div>
      <input type="number" name="total_amount_Items" value="" class="price">
    </div>

    <div class="item">
      <div class="title">中国国内送料</div>
      <input type="number" name = "trans_fee_in_China" value="" class="price">
    </div>

    <div class="item">
      <div class="title">国際送料</div>
      <input type="number" name="international_shipment" value="" class="price">
    </div>

    <input type="submit" value="保存">

  </form>


  </body>
</html>
