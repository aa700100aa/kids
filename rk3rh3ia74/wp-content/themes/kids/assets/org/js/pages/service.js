import inView from "../module/inView";
import util from "../module/util";
import scrollTo from "../module/scrollTo";

export const service = () => {
  ((d, w) => {
    const scrollElement = d.getElementById("js-scrollElm");
    const headerOuter = 'js-headerOuter';
    w.addEventListener("load", () => {
      setTimeout(() => {
        scrollElement.scrollTo(0, 0);
      }, 150);
      if (location.hash) {
        // URLのアンカー（#以降の部分）を取得
        const destination = location.hash.slice(1);
        const headerPadding = util.mql.matches ? 0 : d.getElementById(headerOuter).clientHeight - 10;
        setTimeout(() => {
          scrollTo({
            reference: scrollElement,
            target: d.getElementById(destination),
            headerPadding: headerPadding,
            durationTime: 500,
          });
        }, 300);
      }
    });
    /**
     * 事業紹介でのスムーズスクロール
     */
    const headerServiceLinks = d.querySelectorAll('.js-serviceLink');
    headerServiceLinks.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        //バーガーメニューからのスクロールの場合、メニュークローズ
        if (this.classList.contains('js-burgerServiceLink')) {
          d.body.classList.remove("add-headerOpen");
          d.body.classList.add("add-burgerClose");
        }
        //目的地へスムーズスクロール
        const trimmingIndex = this.href.indexOf("#") + 1;
        const destination = this.href.substring(trimmingIndex);
        const headerPadding = util.mql.matches ? 0 : d.getElementById(headerOuter).clientHeight - 10;
        scrollTo({
          reference: scrollElement,
          headerPadding: headerPadding,
          target: d.getElementById(destination),
          durationTime: 500,
        });
      })
    });
    /**
     * inView
     */
    const setInView = () => {
      new inView({
        reference: scrollElement,
        visibleType: 'middle',
      });
      new inView({
        reference: scrollElement,
        visibleType: 'bottom',
        elemment: d.getElementsByClassName('js-inView_illust')
      });
    };
    w.addEventListener('load', () => {
      setTimeout(() => {
        setInView();
      }, util.loadAnimationDuration);
    })

    /*-------- 
      画像の切り替え
      ---------*/
    const addVisible = 'add-visible';
    const changeImgWrap = d.querySelectorAll(".js-changeImgWrap");
    changeImgWrap.forEach((wrap) => {
      const imgs = wrap.querySelectorAll('.js-img');
      let count = 1;
      imgs[0].classList.add(addVisible);
      const chageImg = () => {
        imgs.forEach((target, i) => {
          target.classList.remove(addVisible);
          if (i === count) {
            target.classList.add(addVisible);
          }
        });
        //配列の最後までカウントしたら0に戻す
        count === imgs.length - 1 ? count = 0 : count++;
      }
      setInterval(() => {
        //フェードイン後に実行
        if (wrap.closest('.js-inView').classList.contains("add-inView")) {
          chageImg();
        }
      }, 4000);
    });
    /**
     * accordionのmax-heightを計算する処理
     */
    const serviceLogoAreaLists = d.querySelectorAll('.js-serviceLogoAreaList');
    const serviceLogoAreaAccordionPadding = 200;
    const setMaxAccordionHeight = () => {
      const temp = Array.from(serviceLogoAreaLists).map(function (accordion) {
        return accordion.clientHeight + serviceLogoAreaAccordionPadding;
      });
      const aryMax = (a, b) => { return Math.max(a, b); };
      const maxHeight = temp.reduce(aryMax);
      d.documentElement.style.setProperty("--maxAccordionHeight", `${maxHeight}px`);
    }
    const serviceLogoAreaAccordionButton = d.querySelector('.js-serviceLogoAreaAccordionButton');
    if (serviceLogoAreaAccordionButton) {
      w.addEventListener('load', () => {
        setMaxAccordionHeight();
      })
      w.addEventListener('resize', () => {
        setMaxAccordionHeight();
      });
    }
    /**
 * accordion
 */
    const accordionFunc = (() => {
      const serviceLogoAreaAccordionButtons = d.querySelectorAll('.js-serviceLogoAreaAccordionButton');
      const accordionOpen = 'add-open';
      const unit = 76 / 750;
      serviceLogoAreaAccordionButtons.forEach((serviceLogoAreaAccordionButton, index) => {
        serviceLogoAreaAccordionButton.addEventListener('click', function () {
          if (serviceLogoAreaAccordionButton.closest('.js-serviceLogoAreaAccordion').classList.contains(accordionOpen)) {
            serviceLogoAreaAccordionButton.closest('.js-serviceLogoAreaAccordion').classList.remove(accordionOpen);
            const headerPadding = d.getElementById(headerOuter).clientHeight + unit * innerWidth;
            if (serviceLogoAreaAccordionButton.closest('.js-serviceLogoAreaAccordion').getBoundingClientRect().top < headerPadding) {
              scrollTo({
                reference: scrollElement,
                headerPadding: headerPadding,
                target: serviceLogoAreaAccordionButton.closest('.js-serviceLogoAreaAccordion'),
                durationTime: 300,
              });
            }
          }
          else {
            serviceLogoAreaAccordionButton.closest('.js-serviceLogoAreaAccordion').classList.add(accordionOpen);
          }
        })
      });
    })();

    /**
     * kv animation
     */

    const serviceKvVisualInner = '#js-serviceKvVisualInner';
    const unit = 1579.4 / 750;
    const setServiceKvVisualWidth = () => {
      let temp = Math.round(unit * innerWidth);
      if (temp % 2 != 0) ++temp;
      const serviceKvVisualWidth = Math.round(temp);
      d.documentElement.style.setProperty("--serviceKvVisualWidth", `${serviceKvVisualWidth}px`);
    }
    w.addEventListener('load', () => {
      setServiceKvVisualWidth();
      setTimeout(() => {
        let kvTween = gsap.matchMedia();
        kvTween.add("(max-width: 767.9px)", () => {
          gsap.to(serviceKvVisualInner, {
            x: '-50%',
            duration: 8,
            repeat: -1,
            ease: 'linear',
          })
        });
        kvTween.add("(min-width: 768px)", () => {
          gsap.to(serviceKvVisualInner, {
            y: -688,
            duration: 10,
            repeat: -1,
            ease: 'linear',
          })
        });

      }, util.loadAnimationDuration + 700);
    });
    w.addEventListener('resize', () => { setServiceKvVisualWidth() });

  })(document, window);
};
