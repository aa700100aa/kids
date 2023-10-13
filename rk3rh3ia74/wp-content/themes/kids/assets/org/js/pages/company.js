import inView from "../module/inView";
import util from '../module/util';


export const company = () => {
  ((d, w) => {
    /**
     * 全体で使うもの
     */
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
        elemment: d.getElementsByClassName('js-inViewTop'),
        reference: scrollElement,
        visibleType: 'top',
      });
    }
    w.addEventListener('load', () => {
      setTimeout(() => {
        setInView();
      }, util.loadAnimationDuration);
    })
  })(document, window);
};