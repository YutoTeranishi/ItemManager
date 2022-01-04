//DOMの読み込み終了時の処理を登録
window.addEventListener('DOMContentLoaded',function(){
  //"cssの#calcが適応されている最初のボタンを登録"のボタンを登録
  //let elCalc = document.querySelector('#calc');
  let calcBs = document.querySelectorAll('.calcButton');
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
  //複数のボタン反応
  for(var i=0; i< calcBs.length;i++){
    calcBs[i].addEventListener('click',function(){
      console.log("click!!!",i);
       if(i==2){
         let html = "bbb";
         document.querySelector('#output').innerHTML = html;
       }
    });

    //それぞれのボタンをidで識別
    /*
    bId = "calcB_"+i;
    let calcBs = document.querySelectorById(bId);
    */

  }

});
