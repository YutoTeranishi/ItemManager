
<?php
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
  <link herf="./css/tablestyle.css" rel="stylesheet">
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

        $sql_pSet = "SELECT service_name,percentage_of_fee FROM pSetUp_fee_table";
        //プリペアドステートメント作成
        $stm_pSet = $pdo->prepare($sql_pSet);

        $stm_pSet->execute();

        //結果の取得(連想配列)
        $result_pSet = $stm_pSet->fetchAll(PDO::FETCH_ASSOC);

        //print_r($result_pSet);
        //連想配列を定義
        $result_pSet_array = [];
        //$result_pSet_array= $result_pSet;

        foreach($result_pSet as $row_pSet){
          print_r($row_pSet);
          $result_pSet_array += $row_pSet;
          print_r($result_pSet_array);
        }

        //print_r($result_pSet_array);
        //print($row_pSet['percentage_of_fee']);

        $sql = "SELECT * FROM price_table";
        //プリペアドステートメント作成
        $stm = $pdo->prepare($sql);

        $stm->execute();

        //結果の取得(連想配列)
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        //テーブルのタイトル行
        echo "<table >";
        echo "<thead><tr>";
        echo "<th>", "ID","</th>";
        echo "<th>", "商品ID","</th>";
        echo "<th>", "購入回","</th>";
        echo "<th>","購入金額(元)","</th>";
        echo "<th>","変換レート","</th>";
        echo "<th>","購入個数","</th>";
        echo "<th>","同時発送個数","</th>";
        echo "<th>","中国国内送料","</th>";
        echo "<th>","販売時予定送料","</th>";
        echo "<th>","販売予定価格","</th>";
        echo "<th>","購入手数料","</th>";
        echo "</tr></thead>";

        //値を取り出して行に表示する
        echo "<tbody>";

        //resultをrowとして各行処理
        foreach($result as $row){
          echo "<tr>";
          echo "<td>",$row['id'],"</td>";
          echo "<td>",$row['item_id'],"</td>";
          echo "<td>",$row['times_id'],"</td>";
          echo "<td>",$row['price_gen'],"</td>";
          echo "<td>",$row['rate_genToyen'],"</td>";
          echo "<td>",$row['amount_Item'],"</td>";
          echo "<td>",$row['total_amount_Items'],"</td>";
          echo "<td>",$row['trans_fee_in_China'],"</td>";
          echo "<td>",$row['trans_fee_in_Japan'],"</td>";
          //販売予定価格をインプットフォームに変更
          //echo "<td>",$row['selling_price'],"</td>";
          echo '<td><input type="number" value="',$row['selling_price'],'" class="price"></td>';

          //echo "<td>",$row['purchase_setUp_fee'],"</td>";

          //プルダウン
          echo "<td>";
          echo '<select name="test">';
          echo '<option value="test1">テスト1</option>';
          echo '<option value="test2">テスト2</option>';
          echo '<option value="test3">テスト3</option>';
          echo '</select>';
          echo "</td>";
          //,selected("test1",[$test]),
          echo '<td><button id="calc">再計算を計算</button></td>';
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
