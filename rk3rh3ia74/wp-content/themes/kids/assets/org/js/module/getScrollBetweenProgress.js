import extend from "modules/extend";

//スクロール要素
const container = document.getElementById('js-container');

//イージングさせたい場合はここに計算式を追加
const Eases = {
  liner: (x) => {
    return x;
  },
  easeOutQuart: (x) => {
    return 1 - Math.pow(1 - x, 4);
  },
  easeInQuad: (x) => {
    return x * x;
  }
}

export default class getScrollBetweenProgress {
  constructor(config) {
    const _t = this;
    _t.config = {
      startOffset: 0,
      endOffset: document.body.offsetHeight - innerHeight,
      reverseFlag: false,//進捗率を逆にするか否かを判定するためのフラグ（要素をマイナス位置から動かしたい場合などで使用）
      eases: 'liner',//デフォルトのイージング関数
      callback: () => { }
    }
    if (config) extend(_t.config, config);
    _t._requestAnimationFrame = _t._requestAnimationFrame.bind(_t)
    _t.cancelFlg = false;
    _t.execute();
  }
  execute() {
    const _t = this;
    _t.cancelFlg = false;
    requestAnimationFrame(_t._requestAnimationFrame);
  }
  _requestAnimationFrame() {
    const _t = this;
    _t.config.callback(_t._getOffsetProgress());
    _t.cancelFlg ? cancelAnimationFrame(_t._requestAnimationFrame) : requestAnimationFrame(_t._requestAnimationFrame);
  }
  _getOffsetProgress() {
    const _t = this;
    let progress = (container.scrollTop - _t.config.startOffset) / (_t.config.endOffset - _t.config.startOffset);
    if (progress > 1) {
      progress = 1;
    }
    else if (progress < 0) {
      progress = 0;
    }
    let easeProgress = Eases[_t.config.eases](progress);
    //進捗率を逆にしたい場合（要素をマイナス位置から動かしたい場合など）
    if (_t.config.reverseFlag) {
      easeProgress = 1 - easeProgress;
    }
    return easeProgress;
  }
  updateConfig(config) {
    const _t = this;
    if (config) extend(_t.config, config);
  }
  dispose() {
    const _t = this;
    _t.cancelFlg = true;
  }
}