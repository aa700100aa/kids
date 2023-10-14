import InView from "../module/inView";
import util from "../module/util";

export const posts = () => {
  ((d, w) => {
    w.addEventListener("load", () => {
      const windowHeight = innerHeight;
      const inViewEl = [].slice.call(d.querySelectorAll(".js-inView"));
      inViewEl.forEach((el, index) => {
        const offsetMiddle =
          el.getBoundingClientRect().top + pageYOffset + el.offsetHeight / 2;
        if (offsetMiddle < windowHeight) {
          el.style.transitionDelay = `${0.1 * index}s`;
        }
      });

      setTimeout(() => {
        new InView({
          visibleType: "middle",
        });
      }, util.loadAnimationDuration);
      
    });
  })(document, window);
};
