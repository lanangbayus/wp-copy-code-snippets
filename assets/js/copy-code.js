(function () {
    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }

        // Fallback lama
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'absolute';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        return Promise.resolve();
    }

    function initCopyButtons() {
        var buttons = document.querySelectorAll('.wpcc-copy-button');
        if (!buttons.length) return;

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var wrapper = button.closest('.wpcc-snippet');
                if (!wrapper) return;

                var codeElement = wrapper.querySelector('.wpcc-code');
                if (!codeElement) return;

                var text = codeElement.innerText;

                copyText(text).then(function () {
                    // Ubah label sementara
                    var label = button.querySelector('.wpcc-copy-label');
                    if (!label) return;

                    var originalText = label.textContent;
                    label.textContent = 'Copied!';
                    button.classList.add('wpcc-copied');

                    setTimeout(function () {
                        label.textContent = originalText;
                        button.classList.remove('wpcc-copied');
                    }, 1500);
                });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCopyButtons);
    } else {
        initCopyButtons();
    }
})();
