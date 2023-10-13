import { common } from "../pages/common";
import { top } from "../pages/top";
import { about } from "../pages/about";
import { posts } from "../pages/posts";
import { post } from "../pages/post";
import { contact } from "../pages/contact";
import { confirm } from "../pages/confirm";
import { company } from "../pages/company";
import { employment } from "../pages/employment";
import { service } from "../pages/service";

((d, w) => {
  common();
  console.log('aaaaaa');

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
    case "company":
      company();
      break;
    case "employment":
      employment();
      break;
    case "service":
      service();
      break;
  }
})(document, window);
