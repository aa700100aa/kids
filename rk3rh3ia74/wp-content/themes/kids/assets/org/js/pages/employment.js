import inView from "../module/inView";
import util from '../module/util';


export const employment = () => {
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
    }
    w.addEventListener('load', () => {
      setTimeout(() => {
        setInView();
      }, util.loadAnimationDuration);
    })
    /**
     * accordionのmax-heightを計算する処理
     */
    const employmentReacruitAccordions = d.querySelectorAll('.js-employmentRecruitAccordion');
    const employmentEntry = d.querySelector('.js-employment-entry');
    const setMaxAccordionHeight = () => {
      const temp = Array.from(employmentReacruitAccordions).map(function (accordion) {
        return accordion.clientHeight + employmentEntry.clientHeight;
      });
      const aryMax = (a, b) => { return Math.max(a, b); };
      const maxHeight = temp.reduce(aryMax);
      d.documentElement.style.setProperty("--maxAccordionHeight", `${maxHeight}px`);
    }
    const employmentRecruitSubTtl = d.getElementById('js-employmentRecruitSubTtl');
    if (!employmentRecruitSubTtl) {
      w.addEventListener('load', () => {
        setMaxAccordionHeight();
      })
      scrollElement.addEventListener('scroll', () => {
        setMaxAccordionHeight();
      });
    }
    /**
 * accordion
 */
    const accordionFunc = (() => {
      const employmentReacruitItems = d.querySelectorAll('.js-employmentRecruitItem');
      const employmentReacruitItemNames = d.querySelectorAll('.js-employmentRecruitItemName');
      const accordionOpen = 'add-accordionOpen';
      employmentReacruitItemNames.forEach((employmentReacruitItemName, index) => {
        employmentReacruitItemName.addEventListener('click', function () {
          if (employmentReacruitItems[index].classList.contains(accordionOpen)) {
            employmentReacruitItems[index].classList.remove(accordionOpen);
          }
          else {
            employmentReacruitItems[index].classList.add(accordionOpen);
          }
        })
      });
    })();
  })(document, window);
};