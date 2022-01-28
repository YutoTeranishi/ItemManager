//tableの数値をint化
function intTable(table){
  let colN=0;
  let txtFId="";
  const rows_arr=new Array();
  const cells_arr=new Array();

  for(var i=0;i<table.rows.length-1;i++){

      //セルの中身をテスト出力
      for(let cell of table.rows[i+1].cells){
        if(colN==13||colN==14){
          if(colN==13){
            txtFId = 'trans_fee_jp_'+i;
          }else{
            txtFId = 'selling_price_'+i;
          }
          let element = document.getElementById(txtFId);
          //数値に変換
          let addelement = parseInt(element.value);
          cells_arr.push(addelement);
          //console.log(element.value);
          //console.log(addelement);
        }else{
            if(colN!=21){
              //console.log(parseFloat(cell.innerText));
              cells_arr.push(parseFloat(cell.innerText));
            }
        }
        colN++;
     }
    rows_arr[i]=cells_arr.slice((colN-1)*i,cells_arr.length);//参照渡し??

    colN=0;
    //i++;
  }
  //console.log(rows_arr);
  return rows_arr;
}

//販売手数料の計算
function calc_selling_setUp_fee(table,arr_setUp_table){
 //const cost_pSet = new Array(arr_setUp_table.length);
 let n =0;
 let m =0;
 let sCounter = 0;//販売手数料の個数
 const cost_pSets = new Array(table.length);

 for(let x = 0;x<arr_setUp_table.length;x++){
     if(arr_setUp_table[x]['type_of_fee']=="sell"){
      sCounter++;
     }
 }
 //cost_pSetを二次元配列化
 for(let y=0;y<cost_pSets.length;y++){
   cost_pSets[y] = new Array(sCounter).fill(0);
 }

 //販売手数料の計算と格納
 for(let i = 0;i<table.length;i++){
   for(let j = 0;j<arr_setUp_table.length;j++){
       if(arr_setUp_table[j]['type_of_fee']=="sell"){
        cost_pSets[i][m] = table[i][14] * (arr_setUp_table[j]['percentage_of_fee']/100);
        cost_pSets[i][m]=cost_pSets[i][m].toFixed(1);//有効数字小数点以下1桁
        m++;
       }
   }
   m=0;
 }
 return cost_pSets;
}

//単体利益額の計算
function calc_uniProfits(table,arr_cost_pSets){

  const uniProfits = new Array(arr_cost_pSets.length);

  for(let y=0;y<uniProfits.length;y++){
    uniProfits[y] = new Array(arr_cost_pSets[y].length).fill(0);
  }

  for(let i=0;i<arr_cost_pSets.length;i++){
    for(let j=0;j<arr_cost_pSets[i].length;j++){
        uniProfits[i][j]=table[i][14]-(table[i][12]+table[i][13]+parseFloat(arr_cost_pSets[i][j]));
        uniProfits[i][j]=uniProfits[i][j].toFixed(1);

    }
  }

 return uniProfits;
}
//合計利益額の計算
function calc_totalProfits(table,uniProfits){
  const totalProfits=new Array(uniProfits.length);

  for(let y=0;y<totalProfits.length;y++){
    totalProfits[y] = new Array(uniProfits[y].length).fill(0);
  }

  for(let i=0;i<uniProfits.length;i++){
    for(let j=0;j<uniProfits[i].length;j++){
        totalProfits[i][j]=parseFloat(uniProfits[i][j])*table[i][6];
        totalProfits[i][j]=totalProfits[i][j].toFixed(1);

    }
  }

  return totalProfits;
}

//販売時手数料、単位利益額、合計利益額の表示
function print_sSet_cells(arr_pSet,html_id){
  let html = arr_pSet[0][0]+'円';
  let id_tmp = "#"+html_id;
  let id;

  for(let i =0;i<arr_pSet.length;i++){
    for(let j =0;j<arr_pSet[i].length;j++){
      id=id_tmp+i+"_"+j;
      html=arr_pSet[i][j]+"円";
      document.querySelector(id).innerHTML = html;
    }
  }
}

function clickMe(){
var result ='<form method="post" action="pdo_itemManagerDB.php"><input type="hidden" name="example" value="サンプル"></form>';
//document.write(result);
//console.log(result);
//let html = '合計金額:<strong>' + priceSum + '</strong>円</div>';

document.querySelector('#output').innerHTML = result;
}

//DOMの読み込み終了時の処理を登録
window.addEventListener('DOMContentLoaded',function(){
  //"cssの#calcが適応されている最初のボタンを登録"のボタンを登録
  let table = document.getElementById('priceTable');
  const table_int =intTable(table);

  let bId="";
  let sSetUp_arr = new Array();
  let arr_uniProfits =new Array();
  let arr_totalProfits =new Array();

  for(var i=0;i<table.rows.length-1;i++){
      txtFId = 'trans_fee_jp_'+i;
      txtFId2 = 'selling_price_'+i;
    //販売時予定送料変更時の処理
    document.getElementById(txtFId).addEventListener('change', function(){
      //table_int =intTable(table);
      table = document.getElementById('priceTable');
      const table_tmp =intTable(table);
      const table_int=table_tmp;

      //販売時手数料の計算
      sSetUp_arr=calc_selling_setUp_fee(table_int,table_pSet);
      //販売時手数料の表示
      print_sSet_cells(sSetUp_arr,"sSetUp_fee_");

      //単体利益額の計算
      arr_uniProfits=calc_uniProfits(table_int,sSetUp_arr);
      //単体利益額の表示
      print_sSet_cells(arr_uniProfits,"unitprofit_");

      //合計利益額の計算
      arr_totalProfits=calc_totalProfits(table_int,arr_uniProfits);
      //合計利益額の用事
      print_sSet_cells(arr_totalProfits,"totalprofit_");

    });
    //販売予定価格変更時の処理
    document.getElementById(txtFId2).addEventListener('change', function(){
      const table_tmp =intTable(table);
      const table_int=table_tmp;

      //console.log(table_int);

      sSetUp_arr=calc_selling_setUp_fee(table_int,table_pSet);
      print_sSet_cells(sSetUp_arr,"sSetUp_fee_");

      console.log(sSetUp_arr);
      const arr_uniProfits=calc_uniProfits(table_int,sSetUp_arr);
      //console.log(arr_uniProfits);
      print_sSet_cells(arr_uniProfits,"unitprofit_");


      arr_totalProfits=calc_totalProfits(table_int,arr_uniProfits);
      //console.log(arr_totalProfits);
      print_sSet_cells(arr_totalProfits,"totalprofit_");

    });
    /*
    //ボタン押した後の処理
    //textformのid
    bId = "calcB_"+i;
      document.getElementById(bId).addEventListener('click', function(){
        console.log('id名「' + this.id + '」のボタンを押しました。');
        console.log(table_int);
        console.log(calc_selling_setUp_fee(table_int,table_pSet));

        sSetUp_arr=calc_selling_setUp_fee(table_int,table_pSet);
        print_sSet_cells(sSetUp_arr,"sSetUp_fee_");
      });
      */
  }

});
