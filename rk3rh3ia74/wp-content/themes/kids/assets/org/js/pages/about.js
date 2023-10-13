import inView from "../module/inView";
// import Swiper from 'npms/swiper';
import util from '../module/util';

export const about = () => {
  ((d, w) => {
    /**
     * 全体で使うもの
     */
    const credoTrigger = '.js-credoTrigger';
    const scrollElement = d.getElementById("js-scrollElm");
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
        elemment: d.getElementsByClassName('js-inViewLogo')
      });
    }
    w.addEventListener('load', () => {
      setTimeout(() => {
        setInView();
      }, util.loadAnimationDuration);
    })
    const observeLogoImg = (() => {
      const inViewLogoImgOuter = d.getElementById('js-inViewLogoImgOuter');
      const aboutLogoInner = d.getElementById('js-aboutLogoInner');
      let isInViewAdded = false;
      scrollElement.addEventListener('scroll', () => {
        if (!util.mql.matches && isInViewAdded === false) {
          if (inViewLogoImgOuter.classList.contains('add-inView')) {
            aboutLogoInner.classList.add('add-inView');
            isInViewAdded = true;
          }
        }
      })
    })();
    /**
     * swiper
     */
    const swiper = new Swiper('.js-swiper', {
      loop: true,
      spaceBetween: 105,
      speed: 600,
      slidesPerView: 1,
      navigation: {
        prevEl: '.js-aboutModalNavPrev',
        nextEl: '.js-aboutModalNavNext',
      }
    })
    /**
     * accordion
     */
    const accordionFunc = (() => {
      const credoItems = d.querySelectorAll('.js-credoItem');
      const accordionOpen = 'add-accordionOpen';
      credoItems.forEach((credoItem) => {
        credoItem.querySelector(credoTrigger).addEventListener('click', () => {
          if (!util.mql.matches) {
            if (credoItem.classList.contains(accordionOpen)) {
              credoItem.classList.remove(accordionOpen);
            }
            else {
              credoItem.classList.add(accordionOpen);
            }
          }
        })
      });
    })();

    /**
     * モーダル
     */
    const modalFunc = (() => {
      const modalTrigger = [].slice.call(d.querySelectorAll(credoTrigger));
      const aboutModalOverLay = d.getElementById('js-aboutModalOverLay');
      const closeButtons = d.querySelectorAll('.js-navButton');
      const aboutModalOpen = 'add-aboutModalOpen';
      //modalオープン処理
      modalTrigger.forEach((trigger, index) => {
        trigger.addEventListener('click', () => {
          if (util.mql.matches) {
            swiper.slideTo(index + 1, 0);
            d.body.classList.add(aboutModalOpen);
          }
        });
      });
      //クローズボタンとモーダルのオーバーレイを押した時の処理
      //modalの外側を押した時
      aboutModalOverLay.addEventListener('click', () => {
        if (util.mql.matches) {
          d.body.classList.remove(aboutModalOpen);
        }
      })
      //modalのクローズボタン押した時の処理
      closeButtons.forEach((closeButton) => {
        closeButton.addEventListener('click', (e) => {
          if (util.mql.matches) {
            e.stopPropagation();
            d.body.classList.remove(aboutModalOpen);
          }
        })
      });
      //イベント伝播を停止
      d.getElementById('js-aboutModalListInner').addEventListener('click', (e) => {
        e.stopPropagation();
      });
      aboutModalOverLay.querySelectorAll('.js-aboutModalNav').forEach((nav) => {
        nav.addEventListener('click', (e) => {
          e.stopPropagation();
        });
      })
      //BP跨いだときにモーダル解除
      const closeModalWhenMobile = () => {
        if (!util.mql.matches) {
          d.body.classList.remove(aboutModalOpen);
        }
      }
      !util.mql.addListener(closeModalWhenMobile);
    })();
  })(document, window);
};