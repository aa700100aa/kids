import extend from "./extend";

export default class modalStopScroll {
  constructor(config) {
    this.config = {
      element: config.element,
      callback: () => {},
    };
    if (config) extend(this.config, config);
  }

  /* ========================================================
メソッド実行
=========================================================*/

  execute() {
    this._execute();
  }

  _execute() {
    for (let index = 0; index < this._getElementLength(); index++) {
      this._modalOpen(this.config.element[index]);
    }
  }

  /* ========================================================
  対象クラス
  =========================================================*/

  // 指定したクラス
  _targetElement() {
    return document.getElementsByClassName(this.config.element);
  }

  // 要素の数を取得
  _getElementLength() {
    return this.config.element.length;
  }

  /* ========================================================
  付与されるクラス
  =========================================================*/

  // addClass
  _modalCloseAddClass(thisObject) {
    thisObject.classList.add("is-fixedModal");
  }

  //removeClass
  _modalCloseRemoveClass(thisObject) {
    thisObject.classList.remove("is-fixedModal");
  }

  // hasClass
  _checkOpenModal(thisObject) {
    return thisObject.classList.contains("is-fixedModal");
  }

  /* ========================================================
    html,body AddStyle
    =========================================================*/
  // 開いた時
  _htmlAddOpenStyle() {
    document.querySelector("html").style.overflow = "hidden";
    document.querySelector("body").style.overflow = "hidden";
  }

  // 閉じた時
  _htmlRemoveOpenStyle() {
    document.querySelector("html").style.overflow = "visible";
    document.querySelector("body").style.overflow = "visible";
  }

  /* ========================================================
    ios検索バー
    =========================================================*/

  _iosSearchBar() {
    document.documentElement.style.setProperty(
      "--window-inner-height",
      `${innerHeight}px`
    );
    document.querySelector("html").style.height = "var(--window-inner-height)";
  }

  /* ========================================================
    ios スクロール制御
    =========================================================*/

  _iosTouchControl(thisObject) {
    var touch_start_y;


    window.addEventListener(
      "touchmove.noscroll",
      function (event) {
        var current_y = event.originalEvent.changedTouches[0].screenY,
          height = thisObject.clientHeight,
          is_top = touch_start_y <= current_y && thisObject.scrollTop === 0,
          is_bottom =
            touch_start_y >= current_y &&
            thisObject.scrollHeight - thisObject.scrollTop === height;

        // スクロール対応モーダルの上端または下端のとき
        if (is_top || is_bottom) {
          // スクロール禁止
          event.preventDefault();
        }
      },
      { passive: false }
    );

    this._iosSearchBar();
  }

  /* ========================================================
callback
=========================================================*/

  _callBack(thisObject) {
    return this.config.callback(thisObject);
  }

  /* ========================================================
    モーダルを開閉時の処理
    =========================================================*/

  _modalOpen(thisObject) {
    this._executeBifurcation(thisObject);
  }

  /* ========================================================
    必ず実行する処理
    =========================================================*/
  _executeBifurcation(thisObject) {
    if (this._checkOpenModal(thisObject)) {
      // 閉じる時
      this._modalCloseRemoveClass(thisObject);
      this._htmlRemoveOpenStyle();
    } else {
      // 開いた時
      this._modalCloseAddClass(thisObject);
      this._htmlAddOpenStyle();
      this._iosTouchControl(thisObject);
      this._callBack(thisObject);
    }
  }

  /* ========================================================
  削除
  =========================================================*/

  destroy() {
    document.querySelector("html").removeAttribute("style");
    document.querySelector("body").removeAttribute("style");
    for (let index = 0; index < this._getElementLength(); index++) {
      this.config.element[index].classList.remove("is-fixedModal");
    }
  }
}
