(function () {
    let gtConstEvalStartTime = new Date();

    function d(b) {
        let a = document.getElementsByTagName("head")[0];
        a || (a = document.body.parentNode.appendChild(document.createElement("head")));
        a.appendChild(b)
    }

    function _loadJs(b) {
        const a = document.createElement("script");
        a.type = "text/javascript";
        a.charset = "UTF-8";
        a.src = b;
        d(a)
    }

    function _loadCss(b) {
        const a = document.createElement("link");
        a.type = "text/css";
        a.rel = "stylesheet";
        a.charset = "UTF-8";
        a.href = b;
        d(a)
    }

    function _isNS(b) {
        b = b.split(".");
        let a = window, c = 0;
        for (; c < b.length; ++c) if (!(a = a[b[c]])) return !1;
        return !0
    }

    function _setupNS(b) {
        b = b.split(".");
        let a = window;
        b.forEach((v, c) => {
            a.hasOwnProperty ?
                a.hasOwnProperty(b[c]) ?
                    a = a[b[c]] :
                    a = a[b[c]] = {} :
                a = a[b[c]] || (a[b[c]] = {});
        });
        return a
    }

    window.addEventListener && "undefined" == typeof document.readyState && window.addEventListener("DOMContentLoaded", function () {
        document.readyState = "complete"
    }, !1);
    if (_isNS('google.translate.Element')) {
        return
    }
    (function () {
        const c = _setupNS('google.translate._const');
        c._cest = gtConstEvalStartTime;
        gtConstEvalStartTime = undefined;
        c._cl = 'pt-BR';
        c._cuc = 'googleTranslateElementInit';
        c._cac = '';
        c._cam = '';
        c._ctkk = '437235.4164553576';
        const h = 'translate.googleapis.com';
        const s = `https://`;
        const b = s + h;
        c._pah = h;
        c._pas = s;
        c._pbi = b + '/translate_static/img/te_bk.gif';
        c._pci = b + '/translate_static/img/te_ctrl3.gif';
        c._pli = b + '/translate_static/img/loading.gif';
        c._plla = h + '/translate_a/l';
        c._pmi = b + '/translate_static/img/mini_google.png';
        c._ps = b + '/translate_static/css/translateelement.css';
        c._puh = 'translate.google.com';
        _loadCss(c._ps);
        _loadJs(b + '/translate_static/js/element/main_pt-BR.js')
    })();


})();
(($) => {
    $(document).ready(function () {
        function googleTranslateElementInit(target) {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'pt,es,en',
                autoDisplay: false
            }, target);
            const a = document.querySelector(`#${target}`);
            if (a) {
                a.selectedIndex = 1;
                a.dispatchEvent(new Event('change'));
            }
        }

        setTimeout(() => googleTranslateElementInit('ts'), 1500)
    });
})(window.$ || window.jQuery);

