
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

//DOMの読み込み終了時の処理を登録
window.addEventListener('DOMContentLoaded',function(){
  //"cssの#calcが適応されている最初のボタンを登録"のボタンを登録
  let table = document.getElementById('priceTable');

  const table_int =intTable(table);
  //phpの値をテスト表示
  console.log(table_pSet);
  /*
  calcBs.addEventListener('click',function(){
    let prices = document.querySelectorAll('.price');
    //let price = <?php $result['trans_fee_in_Japan']?>;
    let priceSum = 0;
    console.log("click!!!");
    for(let i = 0; i < prices.length; i++){
      let priceVal = prices[i].value;
      priceSum = priceSum + parseInt(priceVal);
    }

    //let html = '合計金額:<strong>' + priceSum + '</strong>円</div>';
    let html = "aaa";
    document.querySelector('#output').innerHTML = html;
    //document.querySelector('output').innerHTML = price.length;
  });
  */
  let bId="";

  for(var i=0;i<table.rows.length-1;i++){
      txtFId = 'trans_fee_jp_'+i;
      txtFId2 = 'selling_price_'+i;
    document.getElementById(txtFId).addEventListener('change', function(){
      //table_int =intTable(table);
      table = document.getElementById('priceTable');
      const table_tmp =intTable(table);
      const table_int=table_tmp;

      console.log(table_int);
    });

    document.getElementById(txtFId2).addEventListener('change', function(){
      const table_tmp =intTable(table);
      const table_int=table_tmp;

      console.log(table_int);
    });

    //textformのid
    bId = "calcB_"+i;
      document.getElementById(bId).addEventListener('click', function(){
        console.log('id名「' + this.id + '」のボタンを押しました。');
        console.log(table_int);
      });

  }

  console.log(table_int);
});
