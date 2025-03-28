import GetUserAgent from "../module/getUserAgent";
import util from "../module/util";
// import scrollTo from "modules/scrollTo";

export const contact = () => {
  ((d, w) => {
    w.addEventListener("load", () => {
      /* ---------------------------------------
      ズーム回避
      --------------------------------------- */
      const ua = new GetUserAgent();
      if (ua.isIphone) {
        d.getElementById("js-viewport").setAttribute(
          "content",
          "width=device-width,initial-scale=1,user-scalable=no"
        );
      }

      /* ---------------------------------------
      内容を選んでください
      --------------------------------------- */
      const addErrorTxt = "add-error";
      const requiredNameTxt = ".js-requiredName";
      const selectTxt = "選択してください";

      /* ---------------------------------------
      名前
      --------------------------------------- */
      // 名字
      const firstNameElm = d.querySelector(
        `${requiredNameTxt} input[name="firstName"]`
      );
      firstNameElm.addEventListener("change", () => {
        if (!firstNameElm.value == "") {
          // 値がなにもない時
          firstNameElm.classList.remove(addErrorTxt);
        }
      });

      // 名前
      const lastNameElm = d.querySelector(
        `${requiredNameTxt} input[name="lastName"]`
      );
      lastNameElm.addEventListener("change", () => {
        if (!lastNameElm.value == "") {
          // 値がなにもない時
          lastNameElm.classList.remove(addErrorTxt);
        }
      });

      /* ---------------------------------------
      電話番号
      --------------------------------------- */
      const telPattern = /^[0-9０-９]{2,4}[-－—ー]?[0-9０-９]{2,4}[-－—ー]?[0-9０-９]{2,3}$/;
      const telElm = d.querySelector(`${requiredNameTxt} input[name="tel"]`);

      telElm.addEventListener("change", () => {
        /*番号の間のハイフン削除*/
        telElm.value = telElm.value.replace(/[-－—ー]/g, "");
        /*全角数字を半角に*/
        telElm.value = telElm.value.replace(/[０-９]/g, function (s) {
          return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        });
        if (telPattern.test(telElm.value)) {
          /*パターンにマッチした場合*/
          telElm.classList.remove(addErrorTxt);
        } else {
          /*パターンにマッチしない場合*/
          telElm.classList.add(addErrorTxt);
        }
        if (telElm.value == "") {
          // 値がなにもない時
          telElm.classList.remove(addErrorTxt);
        }
      });


      /* ---------------------------------------
      メールアドレス
      --------------------------------------- */
      // 入力
      const emailPattern = /^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+\.[A-Za-z0-9]{2,}$/;
      const emailElm = d.querySelector(`${requiredNameTxt} input[name="mail"]`);
      emailElm.addEventListener("change", () => {
        if (emailPattern.test(emailElm.value)) {
          /*パターンにマッチした場合*/
          emailElm.classList.remove(addErrorTxt);
        } else {
          /*パターンにマッチしない場合*/
          emailElm.classList.add(addErrorTxt);
        }
        if (emailElm.value == "") {
          // 値がなにもない時
          emailElm.classList.remove(addErrorTxt);
        }
      });

      // 確認
      const emailElm_check = d.querySelector(
        `${requiredNameTxt} input[name="mailCheck"]`
      );
      emailElm_check.addEventListener("change", () => {
        if (emailElm.value == emailElm_check.value) {
          /*パターンにマッチした場合*/
          emailElm_check.classList.remove(addErrorTxt);
        } else {
          /*パターンにマッチしない場合*/
          emailElm_check.classList.add(addErrorTxt);
        }
        if (emailElm_check.value == "") {
          // 値がなにもない時
          emailElm_check.classList.remove(addErrorTxt);
        }
      });

      /* ---------------------------------------
      内容
      --------------------------------------- */
      const detailElm = d.querySelector(`${requiredNameTxt} textarea`);
      detailElm.addEventListener("change", () => {
        if (detailElm.value == "") {
          // 値がなにもない時
          detailElm.classList.add(addErrorTxt);
        } else {
          detailElm.classList.remove(addErrorTxt);
        }
      });

      /* ---------------------------------------
      個人情報の取り扱い
      --------------------------------------- */
      const personalParentElm = d.querySelector(".js-requiredPersonal");
      const personalElm = d.querySelector(".js-requiredPersonal input");
      personalElm.addEventListener("change", () => {
        if (personalElm.checked == true) {
          personalParentElm.classList.remove("add-error_check");
        }
      });

      /* ---------------------------------------
      確認ボタンを押した時
      --------------------------------------- */
      // 確認ボタン
      const submitBtnElm = d.querySelector(".js-submitBtn");
      submitBtnElm.addEventListener("click", () => {
        // ハイフン削除
        telElm.value = telElm.value.replace(/[ー-]/g, "");
      });

      /* ---------------------------------------
      実行
      --------------------------------------- */
      const valid = (e) => {

        // 名前
        const hasNameValue = firstNameElm.value && lastNameElm.value;
        // 電話番号
        const hasTelValue = telPattern.test(telElm.value);
        // メールアドレス
        const hasEmailValue = emailPattern.test(emailElm.value);
        // メールアドレス（確認）
        const hasEmailCheckValue = emailElm.value == emailElm_check.value;
        // 内容
        const hasDetailValue = detailElm.value;
        // 個人情報
        const checkPrivacy = personalElm.checked;

        let canSubmit;
        // まとめ
        canSubmit =
          checkPrivacy &&
          hasDetailValue &&
          hasTelValue &&
          hasEmailValue &&
          hasEmailCheckValue &&
          hasNameValue;

        /**
         * 必須項目が未入力だったら
         */
        if (!canSubmit) {
          // 送信のイベントキャンセル
          e.preventDefault();
          // 苗字
          if (lastNameElm.value === "") {
            lastNameElm.classList.add(addErrorTxt);
          }

          // 名前
          if (firstNameElm.value === "") {
            firstNameElm.classList.add(addErrorTxt);
          }

          // 電話番号
          if (!hasTelValue) {
            telElm.classList.add(addErrorTxt);
          }

          // メールアドレス
          if (!hasEmailValue || !hasEmailCheckValue) {
            emailElm.classList.add(addErrorTxt);
            emailElm_check.classList.add(addErrorTxt);
          }

          // 内容
          if (detailElm.value === "") {
            detailElm.classList.add(addErrorTxt);
          }

          // 個人情報
          if (personalElm.checked == false) {
            personalParentElm.classList.add("add-error_check");
          }

          /**
           * エラーメッセージ
           */
          const checks = [
            {
              conditions: [
                {
                  check: () =>
                    firstNameElm.value === "" || lastNameElm.value === "",
                  message: "「お名前」を入力してください。",
                },
              ],
            },
            {
              conditions: [
                {
                  check: () => telElm.value === "",
                  message: "「電話番号」を入力してください。",
                },
                {
                  check: () => telElm.value !== "" && !hasTelValue,
                  message: "「電話番号」の形式ではありません。",
                },
              ],
            },
            {
              conditions: [
                {
                  check: () => emailElm.value === "",
                  message: "「メールアドレス」を入力してください。",
                },
                {
                  check: () => !hasEmailValue,
                  message: "「メールアドレス」の形式ではありません。",
                },
                {
                  check: () => emailElm_check.value === "",
                  message: "「メールアドレス」の確認をしてください。",
                },
                {
                  check: () => emailElm.value !== emailElm_check.value,
                  message: "「メールアドレス」が一致しません。",
                },
              ],
            },
            {
              conditions: [
                {
                  check: () => detailElm.value === "",
                  message: "「お問い合わせ内容」を入力してください。",
                },
              ],
            },
            {
              conditions: [
                {
                  check: () => !personalElm.checked,
                  message:
                    "「個人情報の取り扱いについて」にチェックを入れてください。",
                },
              ],
            },
          ];

          const errorMessageArray = [];
          for (let i = 0; i < checks.length; i++) {
            for (let j = 0; j < checks[i].conditions.length; j++) {
              if (checks[i].conditions[j].check()) {
                errorMessageArray.push(checks[i].conditions[j].message);
                break;
              }
            }
          }

          const errorMessage = errorMessageArray.join("\n");

          /**
           * メッセージを結合して、アラートを表示する
           */
          const addErrorElms = d.querySelectorAll(".add-error");
          const headerLogoWrap = d.querySelector(".header-logoWrap");

          let headerHeight_contact;

          const setHeaderHeight = () => {
            headerHeight_contact = util.mql.matches
              ? "105px"
              : `${headerLogoWrap.clientHeight}px`;
          };

          setHeaderHeight();

          if (!alert(errorMessage)) {
            for (const addErrorElm of addErrorElms) {
              const targetElmParent = addErrorElm.closest(".js-scrollTarget");
              const targetOffset =
                targetElmParent.getBoundingClientRect().top +
                w.scrollY -
                parseInt(headerHeight_contact);

              w.scrollTo({
                top: targetOffset,
                behavior: "smooth",
              });

              return;
            }
          }
        }
      };

      submitBtnElm.addEventListener("click", valid);
    });
  })(document, window);
};
