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
    PC時の画面幅によって、vhやvwを切り替える
    ---------*/
    const add_vw = "add-vw";
    const add_vh = "add-vh";

    // 画面幅が768px以下のときに実行される関数を定義
    function checkScreenSize() {
      const { innerWidth, innerHeight } = w;
      const screenWidth = innerWidth; // 現在のウィンドウの幅を取得

      if (screenWidth <= 767) {
        // ウィンドウの幅が768px以下の場合に実行される処理
        body.classList.remove(add_vh, add_vw);
      } else {
        const calculatedHeight = innerHeight * 1.8;
        const bodyClass = calculatedHeight > innerWidth ? add_vw : add_vh;
        body.classList.add(bodyClass);
        body.classList.remove(bodyClass === add_vw ? add_vh : add_vw);
      }
    }

    // ウィンドウのサイズ変更時に関数を呼び出すイベントリスナーを追加
    window.addEventListener("resize", checkScreenSize);

    // 初回読み込み時にも関数を実行して現在の画面サイズに対応するかどうかを確認
    checkScreenSize();

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
      gsap
      ---------*/
      gsap.registerPlugin(ScrollTrigger);

      const scrollContentElm = d.querySelector(".js-scrollContent");
      const scrollItem = d.querySelector(".js-scrollItem");
      let mm = gsap.matchMedia();
      const animateElm_pc = () => {
        gsap.to(scrollContentElm, {
          // x方向にscrollItemが-100%動く
          x: () => -scrollItem.clientWidth * 0.9,
          ease: "power0.inOut",
          scrollTrigger: {
            trigger: scrollContentElm,
            start: "top 80px",
            end: () => `+=${scrollItem.clientWidth}`,
            scrub: true,
            pin: true,
            invalidateOnRefresh: true,
            anticipatePin: 1,
          },
        });

        gsap.to(scrollItem, {
          // x方向にscrollItemが-100%動く
          x: () => -scrollItem.clientWidth,
          ease: "power0.inOut",
          scrollTrigger: {
            trigger: scrollContentElm,
            start: "top 80px",
            // endの位置は、js-scrollItem-90%動いたら
            end: () => `+=${scrollItem.clientWidth}`,
            scrub: true,
            invalidateOnRefresh: true,
            anticipatePin: 1,
          },
        });
      };

      mm.add("(max-width: 767px)", () => {
        gsap.killTweensOf(scrollContentElm);
        gsap.killTweensOf(scrollItem);
        scrollItem.style = "";
        scrollContentElm.style = "";
      });
      mm.add("(min-width: 768px)", () => {
        if (mm && body.classList.contains("add-pc")) {
          animateElm_pc();
        }
      });

      /*-------- 
      別のスクロール方法
      ---------*/

      const scrollElm = d.getElementById("js-scrollElm");
      const pinnedElm = d.getElementById("js-pinnedElm");
      const pinnedElm_child = d.getElementById("js-pinnedElm_child");
      const pinnedElm_static = d.getElementById("js-triggerElm");
      const scrollEndElm = d.getElementById("js-endSpBgScroll");

      let rect_A, rect_B, elemBottom_A, elemBottom_B, scrollBottom, start, end;

      function easeInOutCubic(t) {
        return t < 0.5
          ? 4 * t * t * t
          : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
      }

      const updateRects = () => {
        rect_A = pinnedElm_static.getBoundingClientRect();
        rect_B = scrollEndElm.getBoundingClientRect();
        elemBottom_A = rect_A.bottom + pageYOffset;
        elemBottom_B = rect_B.bottom + pageYOffset;
        scrollBottom = pageYOffset + innerHeight;
        start = elemBottom_A;
        end = elemBottom_B;
      };

      updateRects();

      const eventScroll = () => {
        updateRects();

        const targetX = 2615;
        const screenWidthRatio = innerWidth / 750;
        let xValue = targetX * screenWidthRatio;
        let x = -xValue;

        let prog = Math.min(
          Math.max((scrollBottom - start) / (end - start), 0),
          1
        );
        prog = easeInOutCubic(prog);

        x = x * prog;

        if (x < -targetX) {
          x = -targetX;
        }

        pinnedElm_child.style.transform = `translate3d(${x}px, 0, 0)`;
      };

      const addFixed = "add-fixed";
      const addAbsolute = "add-absolute";

      const addFixScroll = () => {
        updateRects();

        if (scrollBottom > elemBottom_A) {
          pinnedElm.classList.add(addFixed);
        } else {
          pinnedElm.classList.remove(addFixed);
        }

        if (scrollBottom > elemBottom_B) {
          pinnedElm.classList.add(addAbsolute);
        } else {
          pinnedElm.classList.remove(addAbsolute);
        }
      };

      w.addEventListener("resize", () => {
        if (!util.mql.matches) {
          updateRects();
        }
      });

      scrollElm.addEventListener("scroll", () => {
        if (!util.mql.matches) {
          eventScroll();
          addFixScroll();
        }
      });

      /*-------- 
      kvで使うintersection
      ---------*/
      let kv_observer;

      const kv_options = {
        root: null,
        rootMargin: "0px",
        threshold: getThresholdValue(),
      };

      const handleIntersection = (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const target = entry.target;
            target.classList.add("add-inView");
            const targetChildren = Array.from(target.children);
            targetChildren.forEach((elm) => {
              if (elm.classList.contains("js-stickOut")) {
                setTimeout(() => {
                  elm.style.overflow = "visible";
                }, 150);
              }
            });
          }
        });
      };

      const observeElements = () => {
        kv_observer = new IntersectionObserver(handleIntersection, kv_options);
        const Elms = d.querySelectorAll(".js-inView_top");
        Elms.forEach((elm) => {
          kv_observer.observe(elm);
        });
      };

      observeElements();

      function getThresholdValue() {
        return util.mql.matches ? 1 : 0.8;
      }

      const kv_observerResize = () => {
        kv_observer.disconnect();
        kv_options.threshold = getThresholdValue();
        observeElements();
      };

      /*-------- 
      pcの時にテキストをフェードイン
      ---------*/
      const kvTxt_options = {
        root: null,
        rootMargin: "0px",
        threshold: getThresholdValue_txt(),
      };
      const handleTxtIntersection = (entries, kvTxt_observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const target = entry.target;
            target.classList.add("add-inView");
          }
        });
      };
      const kvTxt_observer = new IntersectionObserver(
        handleTxtIntersection,
        kvTxt_options
      );

      const Elms_txt = d.querySelectorAll(".js-inView_topTxt"); // js-inView_topをjs-inView_topTxtに変更

      function getThresholdValue_txt() {
        return 0.35; // 要素が20%画面に入ったら発火するように
      }

      if (util.mql.matches) {
        Elms_txt.forEach((elm) => {
          kvTxt_observer.observe(elm);
        });
      }

      w.addEventListener("resize", () => {
        if (util.mql.matches) {
          Elms_txt.forEach((elm) => {
            kvTxt_observer.observe(elm);
          });
        }
      });

      /*-------- 
      サービス画像の切り替え
      ---------*/

      const service_interval = 4000;
      const changeImgElms = d.querySelectorAll(".js-changeImg");
      const addVisible = "add-visible";
      changeImgElms.forEach((elm) => {
        const images = elm.querySelectorAll(".js-img");
        const imageCount = images.length;
        let currentIndex = 0;
        const changeImage = () => {
          if (elm.classList.contains("add-inView")) {
            images[currentIndex].classList.remove(addVisible);
            currentIndex = (currentIndex + 1) % imageCount;
            images[currentIndex].classList.add(addVisible);
          }
        };
        images[currentIndex].classList.add(addVisible);
        setInterval(changeImage, service_interval);
      });

      /*-------- 
      kv画像の切り替え
      ---------*/
      const kv_interval = 3000;
      const kv_changeImgElms = d.querySelectorAll(".js-kvChangeImg");

      kv_changeImgElms.forEach((elm) => {
        const images = elm.querySelectorAll(".js-kvImg");
        const imageCount = images.length;
        let currentIndex = 0;

        const kv_changeImage = () => {
          if (body.classList.contains("add-change")) {
            images[currentIndex].classList.remove(addVisible);
            currentIndex = (currentIndex + 1) % imageCount;
            images[currentIndex].classList.add(addVisible);
          }
        };
        images[currentIndex].classList.add(addVisible);
        setInterval(kv_changeImage, kv_interval);
      });

      // ３枚目のイラストのクラスを監視
      const checkVisibleElm = d.querySelector(".js-checkVisible");
      const sHouseElm = d.querySelector(".js-sHouse");
      const observer = new MutationObserver((mutationsList) => {
        const mutation = mutationsList[0];
        if (mutation.attributeName === "class") {
          const checkVisibleClass =
            checkVisibleElm.classList.contains(addVisible);
          sHouseElm.classList.toggle(addVisible, checkVisibleClass);
        }
      });
      observer.observe(checkVisibleElm, { attributes: true });

      /*-------- 
      slider
      ---------*/

      const slideParent = d.getElementById("js-slideParent");

      let moveX;
      let prevInnerWidth = innerWidth;

      const setMoveX = () => {
        if (innerWidth <= 768) {
          moveX = (innerWidth / 750) * -2128;
        } else {
          moveX = -3962;
        }
      };

      const startAnimation = () => {
        gsap.to(slideParent, {
          x: () => moveX,
          ease: "linear",
          duration: 50,
          repeat: -1,
          repeatDelay: 0,
        });
      };

      gsap.set(slideParent, { x: 0 });
      setMoveX();
      startAnimation();

      const resetGsap = () => {
        gsap.killTweensOf(slideParent);
        slideParent.style = "";
        setMoveX();
        gsap.set(slideParent, { x: 0 });
        startAnimation();
      };

      w.addEventListener("resize", () => {
        const currentInnerWidth = innerWidth;
        if (prevInnerWidth !== currentInnerWidth) {
          if (
            currentInnerWidth <= 768 ||
            (prevInnerWidth <= 768 && currentInnerWidth > 768)
          ) {
            resetGsap();
          }
          prevInnerWidth = currentInnerWidth;
        }
      });

      /*-------- 
      衛星軌道
      ---------*/

      const satelliteWrap = ".js-satelliteWrap";
      const satelliteWrapElm = d.querySelector(satelliteWrap);
      const itemNodes = d.querySelectorAll(".js-satelliteItem");
      const movingElms = d.querySelectorAll(".js-movingElm");

      const itemOptions = Array.from(itemNodes).map((item) => {
        const [dist, first, speed] = item.dataset.opt.split(",").map(Number);
        return { dist, first, speed };
      });

      let animationFrameId, animationFrameIdCollision;
      let count = 0;

      const moveItem = (count) => {
        itemNodes.forEach((item, index) => {
          const { speed, first, dist } = itemOptions[index];
          const deg = speed * count + first;
          const rad = (deg * Math.PI) / 180;
          const x = Math.cos(rad),
            y = Math.sin(rad);

          item.style.transform = `translate3D(${
            ((x * satelliteWrapElm.clientWidth) / 2) * dist
          }px, ${((y * satelliteWrapElm.clientHeight) / 2) * dist}px, ${
            -100 * dist
          }px)`;
        });
      };

      const animationLoop = () => {
        moveItem(count++);
        animationFrameId = requestAnimationFrame(animationLoop);
      };

      const checkCollisions = () => {
        const staticRect = staticElm.getBoundingClientRect();

        movingElms.forEach((elm) => {
          const movingRect = elm.getBoundingClientRect();

          if (
            movingRect.right >= staticRect.left &&
            movingRect.left <= staticRect.right &&
            movingRect.bottom >= staticRect.top &&
            movingRect.top <= staticRect.bottom
          ) {
            elm.closest(satelliteWrap).classList.add("add-back");
          } else {
            elm.closest(satelliteWrap).classList.remove("add-back");
          }
        });

        animationFrameIdCollision = requestAnimationFrame(checkCollisions);
      };

      const observerOptions = { root: null, rootMargin: "0px", threshold: 0 };
      const satelliteObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animationFrameId = requestAnimationFrame(animationLoop);
          } else {
            cancelAnimationFrame(animationFrameId);
          }
        });
      }, observerOptions);

      const collisionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animationFrameIdCollision = requestAnimationFrame(checkCollisions);
          } else {
            cancelAnimationFrame(animationFrameIdCollision);
          }
        });
      }, observerOptions);

      const staticElm = d.getElementById("js-staticElm");

      satelliteObserver.observe(satelliteWrapElm);
      collisionObserver.observe(staticElm);

      w.addEventListener("resize", () => {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = requestAnimationFrame(animationLoop);
      });

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
          kv_observerResize();
          observeHeaderArchi();
        } else {
          setInView();
          kv_observerResize();
        }
      };
      util.mql.addListener(listener);

      /*-------- 
      アンカースクロール
      ---------*/
      d.getElementById("js-ancScroll").addEventListener("click", (event) => {
        const targetElement = d.getElementById("js-ancTarget");
        const paddingRatio = 25 / 750;
        const viewportHeight = innerHeight;
        const padding = viewportHeight * paddingRatio;
        const targetOffset = targetElement.offsetTop - padding;
        scrollElm.scrollTo({
          top: targetOffset,
          behavior: "smooth",
        });
      });
    });
  })(document, window);
};
