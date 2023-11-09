import InView from "../module/inView";
import util from "../module/util";
import GetUserAgent from "../module/getUserAgent";
import scrollTo from "../module/scrollTo";

export const top = () => {
  ((d, w) => {
    const body = d.querySelector("body");

    /*-------- 
    デバイス判定
    ---------*/
    const ua = new GetUserAgent();
    if (!ua.isIpad) d.body.classList.add("add-pc");

    /*-------- 
    ここからロードイベント
    ---------*/
    w.addEventListener("load", () => {
      //headerとkvのtransitionが無効化されている場合
      if(d.getElementsByClassName('add-transition').length != 0){
        //すぐに発火してよい
        new InView({
          visibleType: "top"
        });
      } else {
        //headerとkvの表示が終わってから発火
        new InView({
          elemment: document.getElementsByClassName('js-firstInView'),
          visibleType: "top"
        });
        setTimeout(()=>{
          new InView({
            visibleType: "top"
          });
        },2000);
      }
    });
  })(document, window);
};
