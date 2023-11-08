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
      if(location.hash == "#about") {
        setTimeout(() => {
          scrollTo({
            headerPadding: d.getElementById("js-header").clientHeight,
            target: d.querySelector('#js-about'),
            durationTime: 500,
          });
        }, "300");
      }
      const smoothScrollFunc = (() => {
        const smoothScrollElements = [].slice.call(
          d.querySelectorAll(".js-smoothScroll")
        );
        smoothScrollElements.forEach((target) => {
          target.addEventListener("click", (event) => {
            event.preventDefault();
            let targetPosition = event.currentTarget.href.substring(event.currentTarget.href.indexOf("#"));
            scrollTo({
              headerPadding: d.getElementById("js-header").clientHeight,
              target: d.querySelector('#js-about'),
              durationTime: 500,
            });
          });
        });
      })();

      /*-------- 
      inView
      ---------*/
      if (ua.isIphone || ua.isIpad) {
        const inViewEl = [].slice.call(d.querySelectorAll(".js-inView"));
        inViewEl.forEach((elm, index) => {
          const offsetBottom =
            elm.getBoundingClientRect().top + pageYOffset + elm.offsetHeight;
          if (offsetBottom < innerHeight) {
            elm.style.transitionDelay = `${1.1 + 0.1 * index}s`;
          }
        });
      }

      const setInView = () => {
        const visibleType = 540 < innerWidth ? 120 : 80;
        const fadeElmTop = d.getElementsByClassName("js-fadeIn_top");
        new InView({
          visibleType,
        });
        new InView({
          visibleType: "bottom",
          elemment: fadeElmTop,
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
