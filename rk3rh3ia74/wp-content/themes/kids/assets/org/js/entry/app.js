import { common } from "../pages/common";
import { top } from "../pages/top";
import { about } from "../pages/about";
import { posts } from "../pages/posts";
import { post } from "../pages/post";
import { contact } from "../pages/contact";
import { confirm } from "../pages/confirm";

((d, w) => {
  common();

  switch (d.body.id) {
    case "top":
      top();
      break;
    case "about":
      about();
      break;
    case "posts":
      posts();
      break;
    case "post":
      post();
      break;
    case "contact":
      contact();
      break;
    case "confirm":
      confirm();
      break;
  }
})(document, window);
