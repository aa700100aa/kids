import InView from "../module/inView";
import util from "../module/util";
import GetUserAgent from "../module/getUserAgent";

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
      setTimeout(() => {
        body.classList.add("add-start");
      }, 250);
      setTimeout(() => {
        body.classList.add("add-borderEnd");
      }, 1050);
      setTimeout(() => {
        body.classList.add("add-change");
      }, 550);

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
        const reference = 768 > innerWidth ? scrollElm : w;
        const visibleType = 540 < innerWidth ? 120 : 80;
        const fadeElmTop = d.getElementsByClassName("js-fadeIn_top");
        new InView({
          reference,
          visibleType,
        });
        new InView({
          reference,
          visibleType: "bottom",
          elemment: fadeElmTop,
        });
      };
      setInView();

      const topEx = d.getElementById("js-topEx");
      const headerOuter = d.getElementById("js-headerOuter");
      const observeHeaderArchi = () => {
        if (topEx.getBoundingClientRect().top < 160) {
          headerOuter.classList.remove("add-archiInvisible");
        } else {
          headerOuter.classList.add("add-archiInvisible");
        }
      };
      w.addEventListener("scroll", () => {
        observeHeaderArchi();
      });

      /*-------- 
      utilMqlの判定
      ---------*/

      const listener = (e) => {
        if (e.matches) {
          setInView();
          observeHeaderArchi();
        } else {
          setInView();
        }
      };
      util.mql.addListener(listener);
    });
  })(document, window);
};
