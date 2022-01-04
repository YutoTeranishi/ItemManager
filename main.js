//DOMの読み込み終了時の処理を登録
window.addEventListener('DOMContentLoaded',function(){
  //"cssの#calcが適応されている最初のボタンを登録"のボタンを登録
  let elCalc = document.querySelector('#calc');
  
  elCalc.addEventListener('click',function(){
    let prices = document.querySelectorAll('.price');

    let priceSum = 0;
    for(let i = 0; i < prices.length; i++){
      let priceVal = prices[i].value;
      priceSum = priceSum + parseInt(priceVal);
    }

    let html = '合計金額:<strong>' + priceSum + '</strong>円</div>';

    document.querySelector('#output').innerHTML = html;
  });
});
