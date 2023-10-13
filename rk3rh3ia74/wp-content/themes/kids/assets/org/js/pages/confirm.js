export const confirm = () => {
  ((d, w) => {
    w.addEventListener("load", () => {
      const inquirySelect = d.querySelector(
        'input[name="inquirySelect"]'
      ).value;

      if (inquirySelect === "採用について") {
        d.body.classList.add("add-hidden");
      }
    });

    w.addEventListener('load', () => {
      const recapcha = d.querySelector('.grecaptcha-badge');
      const footer = d.querySelector('.footer');
      const scrollElm = d.getElementById("js-scrollElm");
      if (footer.getBoundingClientRect().bottom < innerHeight * 1.1) {
        recapcha.classList.add('add-invisible');
      }
      else {
        recapcha.classList.remove('add-invisible');
      }
      scrollElm.addEventListener('scroll', () => {
        if (footer.getBoundingClientRect().bottom < innerHeight * 1.1) {
          recapcha.classList.add('add-invisible');
        }
        else {
          recapcha.classList.remove('add-invisible');
        }
      })
    })
  })(document, window);
};
