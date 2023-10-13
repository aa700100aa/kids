export const post = () => {
  ((d, w) => {
    w.addEventListener("load", () => {
      // pタグを取得
      const paragraphs = d.getElementsByTagName("p");

      // 各pタグに対して処理を実行
      for (let i = paragraphs.length - 1; i >= 0; i--) {
        const paragraph = paragraphs[i];
        const content = paragraph.innerHTML.trim(); // pタグ内のテキストを取得して空白を除去

        // pタグの中身が空または&nbsp;だった場合、pタグを削除して内容を<br>に変更
        if (content === "" || content === "&nbsp;") {
          const br = d.createElement("br");
          paragraph.parentNode.replaceChild(br, paragraph);
        }
      }

      // 各pタグに対して処理を実行
      for (const paragraph of paragraphs) {
        // style属性が存在するかチェック
        if (paragraph.hasAttribute("style")) {
          // padding-leftが指定されているかチェック
          if (paragraph.style.paddingLeft) {
            // padding-leftの値を取得
            const paddingLeft = parseInt(paragraph.style.paddingLeft, 10);

            // padding-leftの値を40で割り、emに変換
            const convertedPaddingLeft = `${paddingLeft / 40}em`;
            paragraph.style.paddingLeft = convertedPaddingLeft;
          }
        }
      }

      // pre要素に対して子要素の数に応じたクラスを追加する関数
      const addClassToPre_p = () => {
        const preElements = d.getElementsByTagName("p");

        for (const preElement of preElements) {
          const imgElements = preElement.getElementsByTagName("img");
          const imgCount = imgElements.length;

          if (imgCount === 1) {
            preElement.classList.add("add-one");
          } else if (imgCount === 2) {
            preElement.classList.add("add-two");
          } else if (imgCount === 3) {
            preElement.classList.add("add-three");
          } else if (imgCount >= 4) {
            preElement.classList.add("add-four");
          }
          
        }
      };
      // クラスを追加する関数を呼び出す
      addClassToPre_p();

      // pre要素に対して子要素の数に応じたクラスを追加する関数
      const addClassToPre_a = () => {
        const preElements = d.getElementsByTagName("a");

        for (const preElement of preElements) {
          const imgElements = preElement.getElementsByTagName("img");
          const imgCount = imgElements.length;

          if (imgCount === 1) {
            preElement.classList.add("add-one");
          } else if (imgCount === 2) {
            preElement.classList.add("add-two");
          } else if (imgCount === 3) {
            preElement.classList.add("add-three");
          } else if (imgCount === 4) {
            preElement.classList.add("add-four");
          }
        }
      };
      // クラスを追加する関数を呼び出す
      addClassToPre_a();
    });
  })(document, window);
};
