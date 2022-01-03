
<?php
  $user = 'root';
  $password = '';
  $dbName = 'ItemManager';
  $host = 'localhost:3306';
  $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

  //NULL=配列とtextを結合し表示,HEADER
  function print_pSet_cell($pSetArray,$printId1,$printId2,$headTxt,$midTxt,$tailTxt,$preCond,$typeOfCell){
    //表示が2項目かのチェック
    if($printId1!=NULL&&$printId2!=NULL){
      $twoIds = true;
    }else{
      $twoIds = false;
    }
    //表示するセルの種類を記述
    if($typeOfCell=="HEADER"){
      $hf = "<th>";
      $hl = "</th>";
    }else{
      $hf = "<td>";
      $hl = "</td>";
    }

    if($preCond!=NULL){
      if($twoIds){
        foreach ($pSetArray as $p_sevice_name) {
          if($p_sevice_name['type_of_fee']==$preCond){
              echo $hf,$headTxt,$p_sevice_name[$printId1],$midTxt,$p_sevice_name[$printId2],$tailTxt,$hl;
          }
        }
      }else{
        foreach ($pSetArray as $p_sevice_name) {
          if($p_sevice_name['type_of_fee']==$preCond){
              echo $hf,$headTxt,$p_sevice_name[$printId1],$midTxt,$tailTxt,$hl;
          }
        }
      }
    }else{
      if($twoIds){
        foreach ($pSetArray as $p_sevice_name) {
          echo $hf,$headTxt,$p_sevice_name[$printId1],$midTxt,$p_sevice_name[$printId2],$tailTxt,$hl;
        }
      }else{
        foreach ($pSetArray as $p_sevice_name) {
          echo $hf,$headTxt,$p_sevice_name[$printId1],$midTxt,$tailTxt,$hl;
        }
      }
    }

  }
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
        //echo "データベース{$dbName}に接続しました。";

        //仕入れ、販売手数料のテーブルを取得
        $sql_pSet = "SELECT service_name,percentage_of_fee,type_of_fee FROM pSetUp_fee_table";
        //プリペアドステートメント作成
        $stm_pSet = $pdo->prepare($sql_pSet);

        $stm_pSet->execute();

        //結果の取得(連想配列)
        $result_pSet = $stm_pSet->fetchAll(PDO::FETCH_ASSOC);
        //登録されているサービス数を取得
        $result_pSet_count = count($result_pSet);
        //print($result_pSet_count);

        $sql = "SELECT * FROM price_table";
        //プリペアドステートメント作成
        $stm = $pdo->prepare($sql);

        $stm->execute();

        //結果の取得(連想配列)
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);


        //テーブルのタイトル行
        echo '<table border="1" id="priceTable">';
        echo "<thead><tr>";
        echo "<th>", "ID","</th>";
        echo "<th>", "商品<br>ID","</th>";
        echo "<th>", "購入回","</th>";
        echo "<th>","単価(元)","</th>";
        echo "<th>","変換<br>レート","</th>";
        echo "<th>","単価(円)","</th>";
        echo "<th>","購入<br>個数","</th>";
        echo "<th>","同時<br>発送個数","</th>";
        echo "<th>","中国<br>国内送料","</th>";
        echo "<th>","国際<br>送料","</th>";
        echo "<th>","一個あたりの<br>国際送料","</th>";
        echo "<th>","買付手数料","</th>";
        echo "<th>","仕入れ<br>原価","</th>";
        echo "<th>","販売時<br>予定送料","</th>";
        echo "<th>","販売<br>予定価格","</th>";

        print_pSet_cell($result_pSet,'service_name','percentage_of_fee',NULL,"<br> 手数料(","%)","sell","HEADER");
        print_pSet_cell($result_pSet,'service_name',NULL,NULL,"<br>単体利益額",NULL,"sell","HEADER");
        print_pSet_cell($result_pSet,'service_name',NULL,NULL,"<br>総利益額",NULL,"sell","HEADER");
        echo "</tr></thead>";

        //値を取り出して行に表示する
        echo "<tbody>";
        // カウンター
        $i=0;
        $price_yen = 0;
        //resultをrowとして各行処理
        foreach($result as $row){
          echo "<tr>";
          echo "<td>",$row['id'],"</td>";
          echo "<td>",$row['item_id'],"</td>";
          echo "<td>",$row['times_id'],"</td>";
          echo "<td>",$row['price_gen'],"</td>";
          echo "<td>",$row['rate_genToyen'],"</td>";
          //単価(円)を計算
          $price_yen = $row['price_gen']*$row['rate_genToyen'];
          echo "<td>",$price_yen,"</td>";

          echo "<td>",$row['amount_Item'],"</td>";
          echo "<td>",$row['total_amount_Items'],"</td>";
          echo "<td>",$row['trans_fee_in_China'],"</td>";
          echo "<td>","6500","</td>";
          echo "<td>","200","</td>";
          echo "<td>","100","</td>";
          echo "<td>","350","</td>";
          //echo "<td>",$row['trans_fee_in_Japan'],"</td>";
          echo '<td><input type="number" value="',$row['trans_fee_in_Japan'],'" class="price" style="width:70px;"></td>';
          //販売予定価格をインプットフォームに変更(style="width:100px;はCSSに後変更)
          //echo "<td>",$row['selling_price'],"</td>";
          echo '<td><input type="number" value="',$row['selling_price'],'" class="price" style="width:70px;"></td>';


          //プルダウン
          /*echo "<td>";
          echo '<select name="test">';
          echo '<option value="test1">テスト1</option>';
          echo '<option value="test2">テスト2</option>';
          echo '<option value="test3">テスト3</option>';
          echo '</select>';
          echo "</td>";
          */
          //仕入れ手数料を除き販売手数料を表示
          print_pSet_cell($result_pSet,'percentage_of_fee',NULL,NULL,NULL,"%","sell","CELL");
          /*
          foreach ($result_pSet as $pecentage_of_pfee) {
            if($pecentage_of_pfee['type_of_fee']=="sell"){
              echo "<td>",$pecentage_of_pfee['percentage_of_fee'],"%</td>";
            }
          }
          */
          echo "<td id=uniprice_",$i,">","testpriceA","</td>";
          echo "<td>","testpriceB","</td>";
          echo "<td id=totalprice_",$i,">","testpriceC","</td>";
          echo "<td>","testpriceD","</td>";

          //ボタンのidを行数に応じて設定
          echo '<td><button id="calc_',$i,'">';
          echo '損益を計算</button></td>';
          echo "</tr>";
          $price_yen = 0;
          $i++;
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
