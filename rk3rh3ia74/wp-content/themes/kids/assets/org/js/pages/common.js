import scrollTo from "../module/scrollTo";
import { lockModal, releaseModal } from "../module/lockScroll";
import util from "../module/util";
import GetUserAgent from "../module/getUserAgent";

export const common = () => {
  ((d, w) => {
    // // スクロール無効
    // function disableScroll(e) {
    //   e.preventDefault();
    // }
    // const scrollOff = () => {
    //   d.addEventListener("touchmove", disableScroll, {
    //     passive: false,
    //   });
    //   d.addEventListener("mousewheel", disableScroll, {
    //     passive: false,
    //   });
    // };
    // scrollOff();

    // const scrollOn = () => {
    //   d.removeEventListener("touchmove", disableScroll, {
    //     passive: false,
    //   });
    //   d.removeEventListener("mousewheel", disableScroll, {
    //     passive: false,
    //   });
    // };

    w.addEventListener("load", () => {
      d.body.classList.add("add-loaded");
      const loader = d.getElementById("js-loader");
      // setTimeout(() => {
      //   if (d.body.id !== "top") {
      //     scrollOn();
      //   }
      // }, 100);
      // if (d.body.id == "top") {
      //   setTimeout(() => {
      //     scrollOn();
      //   }, 1050);
      // }
    });
    /**
     * 広範囲で使われる変数
     */
    const hmbg = d.getElementById("js-hmbg"); //ハンバーガーボタン
    const headerNav = d.getElementById("js-headerNav"); //ヘッダー内のナビ
    const headerOpen = "add-headerOpen";
    const burgerClose = "add-burgerClose";
    const ua = new GetUserAgent();
    /**
     * ipadの時にクラスを付与
     */
    if (ua.isIpad) d.body.classList.add("add-iPad");
    /**
     * タブレット時にviewportを設定
     */
    if (ua.isTablet)
      d.querySelector('meta[name="viewport"]').setAttribute(
        "content",
        "width=1200"
      );
    /**
     * Androidの時にのみCSSを適用
     */
    if (ua.isAndroidPhone) d.body.classList.add("add-android");
    /**
     * ヘッダーメニュー
     */
    const cancelBurgerMenu = () => {
      d.body.classList.remove(headerOpen);
      hmbg.classList.add(burgerClose);
      releaseModal(headerNav);
    };
    hmbg.addEventListener("click", (e) => {
      e.preventDefault();
      if (!d.body.classList.contains(headerOpen)) {
        headerNav.scrollTop = 0; //ナビ要素のスクロール位置を初期位置に戻す
        hmbg.classList.remove(burgerClose);
        d.body.classList.add(headerOpen);
        lockModal(headerNav);
      } else {
        cancelBurgerMenu();
      }
    });
    if (util.mql.matches) cancelBurgerMenu();
    util.mql.addListener(cancelBurgerMenu);

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
            if (d.body.classList.contains(headerOpen)) {
              cancelBurgerMenu();
            }
            event.preventDefault();
            scrollTo({
              headerPadding: d.getElementById("js-header").clientHeight,
              target: d.querySelector('#js-about'),
              durationTime: 500,
            });
          });
        });
      })();
    });

    const setFillHeight = () => {
      const vh = w.innerHeight * 0.01;
      const vw = w.innerWidth * 0.01;
      d.documentElement.style.setProperty("--vh", `${vh}px`);
      d.documentElement.style.setProperty("--vw", `${vw}px`);
    };
    // 画面のサイズ変動があった時に高さを再計算する
    w.addEventListener("resize", () => {
      setFillHeight();
    });
    // 初期化
    w.addEventListener("load", () => {
      setFillHeight();
      //ロード直後では正しく計算されないため、
      setTimeout(() => {
        setFillHeight();
      }, util.loadAnimationDuration);
      if (ua.isIpad) {
        setInterval(() => {
          setFillHeight();
        }, 500);
      }
    });
    //横スクロール時のheader位置調整
    const header = d.getElementById("js-header");
    w.addEventListener("scroll", () => {
      if (util.mql.matches) {
        header.style.left = `-${pageXOffset}px`;
      } else {
        header.style.left = 0;
      }
    });
  })(document, window);
};
