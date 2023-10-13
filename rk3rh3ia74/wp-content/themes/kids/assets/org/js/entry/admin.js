((d, w) => {
  w.addEventListener("load", () => {
    // 投稿カテゴリー
    if (
      d.body.classList.contains("post-type-post") &&
      !d.body.classList.contains("taxonomy-category")
    ) {
      function parent_check_script() {
        var categoryInput = document.querySelectorAll(
          "#taxonomy-category .children input"
        );

        function parentNodes(checked, nodes) {
          var parents =
            nodes.parentElement.parentElement.parentElement.previousElementSibling.querySelector(
              "input"
            );
          if (parents) {
            parents.checked = checked;
            parentNodes(checked, parents);
          }
        }

        categoryInput.forEach(function (input) {
          input.addEventListener("change", function () {
            var checked = this.checked;
            var siblingInputs =
              this.parentElement.parentElement.nextElementSibling.querySelectorAll(
                "label input"
              );
            siblingInputs.forEach(function (siblingInput) {
              checked = checked || siblingInput.checked;
            });
            parentNodes(checked, this);
          });
        });
      }

      d.addEventListener("DOMContentLoaded", function () {
        parent_check_script();
      });

      const addButton = d.getElementById("category-add-submit");

      // 初期ロード時にクラスを追加
      d.body.classList.add("add-notSelectParent");

      // ボタンの無効化・有効化を管理する関数
      const toggleButtonState = (disable) => {
        addButton.disabled = disable;
      };

      // 初期状態でボタンを無効化
      toggleButtonState(true);

      const selectElement = d.getElementById("newcategory_parent");
      selectElement.addEventListener("change", () => {
        if (d.body.classList.contains("add-notSelectParent")) {
          toggleButtonState(false); // クラスがある場合はボタンを有効化
        } else {
          toggleButtonState(true); // クラスがない場合はボタンを無効化
        }
      });

      /*-------- 
    カテゴリ追加の項目を消す
    ---------*/
      // postform クラスを持つ要素を取得
      const postForm = d.querySelector(".postform");
      const firstOption = postForm.querySelector("option");
      firstOption.hidden = true;

      /*-------- 
    親要素のチェックボックス
    ---------*/
      const checkboxes = d.querySelectorAll(
        "#categorychecklist > li > label > input"
      );

      function uncheckOtherInputs(event) {
        // クリックされたチェックボックスの要素を取得
        const clickedCheckbox = event.target;

        // #categorychecklistの直下のli要素を全て取得
        const listItems = d.querySelectorAll("#categorychecklist > li");

        // チェックを外す対象のinput要素のリストを作成
        const inputsToUncheck = [];

        listItems.forEach((li) => {
          // クリックされたチェックボックス以外のinput要素を全て取得し、inputsToUncheckに追加
          if (li !== clickedCheckbox.closest("li")) {
            const inputs = li.querySelectorAll("input");
            inputs.forEach((input) => {
              inputsToUncheck.push(input);
            });
          }
        });

        // inputsToUncheckの全てのチェックを外す
        inputsToUncheck.forEach((input) => {
          input.checked = false;
        });
      }

      checkboxes.forEach((checkbox) => {
        checkbox.classList.add("add-parent");
        checkbox.style.display = "none";
        checkbox.parentNode.classList.add("add-parentLabel");
        checkbox.addEventListener("click", uncheckOtherInputs);
      });

      // チェックボックス要素の取得関数
      function getCheckboxElements() {
        return document.querySelectorAll(
          "#categorychecklist > li > ul > li > label > input"
        );
      }

      // チェックボックスの変更イベントを追加
      getCheckboxElements().forEach((checkbox) => {
        checkbox.addEventListener("click", function () {
          // チェックされた要素の値を取得
          const checkedValue = this.value;

          // 他のチェックボックスのチェックを解除
          getCheckboxElements().forEach((otherCheckbox) => {
            if (
              otherCheckbox !== this &&
              otherCheckbox.value !== checkedValue
            ) {
              otherCheckbox.checked = false;
            }
          });
        });
      });

      // チェックボックス要素の変更を監視する
      getCheckboxElements().forEach((checkbox) => {
        checkbox.addEventListener("click", (event) => {
          // チェックボックス要素にチェックが入った場合の処理
          if (checkbox.checked) {
            // checkboxから上位のinput要素を取得
            const parentInput = checkbox.closest("input");
            const parent =
              parentInput.parentElement.parentElement.parentElement
                .parentElement;
            const inputInLabel = parent.querySelector("label > input");

            // 他のチェックボックスのチェックを外す
            const checkboxes = document.querySelectorAll(
              "#categorychecklist > li > label > input"
            );
            checkboxes.forEach((cb) => {
              if (cb !== inputInLabel) {
                cb.checked = false;
              }
            });

            if (inputInLabel) {
              inputInLabel.checked = true;
            }
          }
        });
      });
    }

    // 投稿カテゴリー管理ページ
    if (
      d.body.classList.contains(
        "edit-tags-php" && "post-type-post" && "taxonomy-category"
      )
    ) {
      d.querySelector('option[value="-1"]').remove();
    }
  });
})(document, window);
