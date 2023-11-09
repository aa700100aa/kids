import InView from "../module/inView";

export const flow = () => {
  ((d, w) => {
    w.addEventListener("load", () => {
      new InView({
        visibleType: "top"
      });
    });
  })(document, window);
};
