
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
  <link href="css/tablestyle.css" rel="stylesheet">
  <script src="calcButton.js"></script>
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

        //php受け渡し
        $param_json_pSet = json_encode($result_pSet);  //JSONエンコード
?>
<script type="text/javascript">
  let table_pSet=JSON.parse('<?php echo $param_json_pSet; ?>');//javascriptファイルに値を渡す
</script>
<?php


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
        print_pSet_cell($result_pSet,'percentage_of_fee',NULL,"買付手数料(","%)",NULL,"buy","HEADER");
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
        $i=0;$j=0;
        $price_yen = 0;
        $setUp_fee_tmp = 0;
        $setUp_fee_array =[];
        $unitprofit=[];
        $unitShipment=0;
        $unitPfee=0;
        $unitTfee_in_China=0;
        $unitprice=0;
        //resultをrowとして各行処理
        foreach($result as $row){
          echo "<tr>";
          echo "<td>",$row['id'],"</td>";
          echo "<td>",$row['item_id'],"</td>";
          echo "<td>",$row['times_id'],"</td>";
          echo "<td>",$row['price_gen'],"</td>";
          echo "<td>",$row['rate_genToyen'],"</td>";
          //単価(円)を計算
          $price_yen = number_format($row['price_gen']*$row['rate_genToyen'],2);

          echo "<td>",$price_yen,"</td>";

          echo "<td>",$row['amount_Item'],"</td>";
          echo "<td>",$row['total_amount_Items'],"</td>";
          echo "<td>",$row['trans_fee_in_China'],"</td>";
          $unitTfee_in_China = $row['trans_fee_in_China']/$row['amount_Item']; //一つあたりの中国国内送料(元)
          echo "<td>",$row['international_shipment'],"円</td>";//国際送料

          $unitShipment = number_format($row['international_shipment']/$row['total_amount_Items'],2);

          echo "<td>",$unitShipment,"円</td>";//1個あたりの国際送料

          //1種類あたりの買い付け手数料
          foreach ($result_pSet as $pecentage_of_pfee) {
            if($pecentage_of_pfee['type_of_fee']=="buy"){
              $unitPfee = number_format(($row['price_gen']*$row['amount_Item']*($pecentage_of_pfee['percentage_of_fee']/100))*$row['rate_genToyen'],2);
              echo "<td>",$unitPfee,"円</td>";
            }
          }
          //仕入れ原価
          $unitprice=number_format($price_yen+$unitShipment+($unitPfee/$row['amount_Item'])+($unitTfee_in_China*$row['rate_genToyen']),2);
          echo "<td>",$unitprice,"</td>";//仕入れ原価
          //販売予定送料
          echo '<td><input type="number" value="',$row['trans_fee_in_Japan'],'" class="price" id="trans_fee_jp_',$i,'" style="width:70px;"></td>';

          //販売予定価格をインプットフォームに変更(style="width:100px;はCSSに後変更)
          echo '<td><input type="number" value="',$row['selling_price'],'" class="price" id="selling_price_',$i,'" style="width:70px;"></td>';


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
          foreach ($result_pSet as $pecentage_of_pfee) {
            if($pecentage_of_pfee['type_of_fee']=="sell"){
              $setUp_fee_tmp = $row['selling_price']*($pecentage_of_pfee['percentage_of_fee']/100);
              echo "<td>",$setUp_fee_tmp,"円</td>";
              $setUp_fee_array[$j]=$setUp_fee_tmp;

              //単位利益額
              $unitprofit[$j]=$row['selling_price']-($row['trans_fee_in_Japan']+$setUp_fee_tmp+$unitprice);
              $j++;
            }
          }
          //単体利益額(マイナス時赤字表記)
          for($z=0;$z<count($unitprofit);$z++){
              if($unitprofit[$z]>0.0){
                  echo "<td id=unitprofit_",$i+$z,">",$unitprofit[$z],"円</td>";
              }else{
                  echo "<td id=unitprofit_",$i+$z,"><font color='RED'>",$unitprofit[$z],"円 </font></td>";
              }

          }
          //合計利益額(マイナス時赤字表記)
          for($z=0;$z<count($unitprofit);$z++){
            if($unitprofit[$z]>0.0){
              echo "<td id=totalprofit_",$i+$z,">",$unitprofit[$z]*$row['amount_Item'],"円 </td>";
            }else{
              echo "<td id=totalprofit_",$i+$z,"><font color='RED'>",$unitprofit[$z]*$row['amount_Item'],"円 </font></td>";
            }
          }

          //ボタンのidを行数に応じて設定
          echo '<td><button id="calcB_',$i,'" class="calcButton">';
          echo '損益を計算</button></td>';
          echo "</tr>";
          $price_yen = 0;
          $i++;
          $j=0;
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
    <div id="output">デバック用出力</div>
  </div>
  </body>

</html>
