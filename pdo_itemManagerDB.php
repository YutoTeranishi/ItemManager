<?php
  //require_once("../../lib/util.php");
  $user = 'root';
  $password = '';
  $dbName = 'ItemManager';
  $host = 'localhost:3306';
  $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
  <meta charset="utf-8">
  <title>PDOでデータベースに接続する</title>
  <link herf="./css/style.css" rel="stylesheet">
  </head>

  <body>
  <div>

<?php
      try {
        //サーバ接続
        $pdo = new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        //例外をスルーする設定
        $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        echo "データベース{$dbName}に接続しました。";


        $sql0 = "USE ItemManager;";
        $stm0 = $pdo->prepare($sql0);
        $stm0->execute();

        $sql = "SELECT * FROM price_table;";
        //プリペアドステートメント作成
        $stm = $pdo->prepare($sql);

        $stm->execute();

        //結果の取得(連想配列)
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        echo '<h1>'.$result['id'].'<-result[0]','</h1>';

        //テーブルのタイトル行
        echo "<table>";
        echo "<thead><tr>";
        echo "<th>", "ID","</th>";
        echo "<th>", "購入回","</th>";
        echo "<th>","購入金額(元)","</th>";
        echo "<th>","変換レート","</th>";
        echo "<th>","購入個数","</th>";
        echo "<th>","同時発送個数","</th>";
        echo "<th>","中国国内送料","</th>";
        echo "<th>","販売時予定送料","</th>";
        echo "<th>","販売予定価格","</th>";
        echo "<th>","販売手数料","</th>";
        echo "</tr></thead>";

        //値を取り出して行に表示する
        echo "<tbody>";
        //resultをrowとして各行処理
        foreach($result as $row){
          echo "<tr>";
          /*
          echo "<td>",es($row['id']),"</td>";
          echo "<td>",es($row['item_id']),"</td>";
          echo "<td>",es($row['times_id']),"</td>";
          echo "<td>",es($row['price_gen']),"</td>";
          echo "<td>",es($row['rate_genToyen']),"</td>";
          echo "<td>",es($row['amount_Item']),"</td>";
          echo "<td>",es($row['total_amount_Items']),"</td>";
          echo "<td>",es($row['trans_fee_in_China']),"</td>";
          echo "<td>",es($row['trans_fee_in_Japan']),"</td>";
          echo "<td>",es($row['selling_price']),"</td>";
          echo "<td>",es($row['purchase_setUp_fee']),"</td>";
          */
          echo "<td>","A","</td>";
          echo "<td>","B","</td>";
          echo "<td>","C","</td>";
          echo "<td>","D","</td>";
          echo "<td>","E","</td>";
          echo "<td>","F","</td>";
          echo "<td>","G","</td>";
          echo "<td>","H","</td>";
          echo "<td>","I","</td>";
          echo "<td>","J","</td>";
          echo "<td>","K","</td>";

          echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

      } catch (\Exception $e) {
        echo '<span class="error">エラーがありました。</span><br>';
        echo $e->getMessage();
        exit();
      }
      //接続を閉じる
      $pdo = NULL;

     ?>
  </div>
  </body>

</html>
