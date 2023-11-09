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
      /*-------- 
      inView
      ---------*/

      const setInView = () => {
        new InView({
          visibleType: "top"
        });
      };
      setInView();

      /*-------- 
      utilMqlの判定
      ---------*/

      const listener = (e) => {
        setInView();
      };
      util.mql.addListener(listener);
    });
  })(document, window);
};
