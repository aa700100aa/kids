import { common } from "../pages/common";
import { top } from "../pages/top";
import { posts } from "../pages/posts";
import { post } from "../pages/post";
import { contact } from "../pages/contact";

((d, w) => {
  common();

  switch (d.body.id) {
    case "top":
      top();
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
  }
})(document, window);
