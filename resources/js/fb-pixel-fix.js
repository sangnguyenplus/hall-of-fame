// Facebook Pixel duplicate prevention
(function () {
    // Track initialized pixels
    window._fbPixelInit = window._fbPixelInit || {
        pixels: new Set(),
        originalFbq: null,
        wrapped: false
    };

    function wrapFbq(newFbq) {
        if (typeof newFbq !== 'function' || window._fbPixelInit.wrapped) {
            return newFbq;
        }

        var wrappedFbq = function () {
            if (arguments[0] === 'init') {
                var pixelId = arguments[1];
                if (window._fbPixelInit.pixels.has(pixelId)) {
                    console.log('[FB Pixel] Preventing duplicate initialization:', pixelId);
                    return;
                }
                window._fbPixelInit.pixels.add(pixelId);
            }
            return newFbq.apply(this, arguments);
        };

        // Copy all properties
        for (var prop in newFbq) {
            if (newFbq.hasOwnProperty(prop)) {
                wrappedFbq[prop] = newFbq[prop];
            }
        }

        window._fbPixelInit.wrapped = true;
        return wrappedFbq;
    }

    // Define property before any scripts load
    Object.defineProperty(window, 'fbq', {
        configurable: true,
        enumerable: true,
        get: function () {
            return window._fbPixelInit.originalFbq;
        },
        set: function (newFbq) {
            window._fbPixelInit.originalFbq = wrapFbq(newFbq);
        }
    });

    // Handle if fbq is already defined
    if (window.fbq) {
        var currentFbq = window.fbq;
        window.fbq = undefined; // Trigger setter
        window.fbq = currentFbq; // Wrap existing
    }
})();
