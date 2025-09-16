// Image Optimization JavaScript

(function() {
    'use strict';
    
    // WebP support detection
    function supportsWebP() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }
    
    // Add WebP support class to document
    if (supportsWebP()) {
        document.documentElement.classList.add('webp');
    } else {
        document.documentElement.classList.add('no-webp');
    }
    
    // Enhanced Intersection Observer for lazy loading
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-lazy');
                    
                    if (src) {
                        img.classList.add('image-loading');
                        
                        // Create a new image to preload
                        const newImg = new Image();
                        newImg.onload = function() {
                            img.src = src;
                            img.classList.remove('image-loading');
                            img.classList.add('image-loaded');
                            img.removeAttribute('data-lazy');
                        };
                        newImg.onerror = function() {
                            img.classList.remove('image-loading');
                            console.warn('Failed to load image:', src);
                        };
                        newImg.src = src;
                        
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });
        
        // Observe all images with data-lazy attribute
        function observeImages() {
            const lazyImages = document.querySelectorAll('img[data-lazy]');
            lazyImages.forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Initial observation
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', observeImages);
        } else {
            observeImages();
        }
        
        // Re-observe after AJAX content loads
        const originalAjax = $.ajax;
        $.ajax = function(options) {
            const originalSuccess = options.success;
            options.success = function(data, textStatus, jqXHR) {
                if (originalSuccess) {
                    originalSuccess.call(this, data, textStatus, jqXHR);
                }
                // Re-observe new images after AJAX
                setTimeout(observeImages, 100);
            };
            return originalAjax.call(this, options);
        };
    }
    
    // Preload critical images
    function preloadCriticalImages() {
        const criticalImages = [
            '/images/icons/hpb2_bg.svg',
            '/images/icons/hpb5_bg.svg'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }
    
    // Image error handling
    function handleImageErrors() {
        document.addEventListener('error', function(e) {
            if (e.target.tagName === 'IMG') {
                const img = e.target;
                const fallback = img.getAttribute('data-fallback');
                
                if (fallback && img.src !== fallback) {
                    img.src = fallback;
                } else {
                    // Use a default placeholder
                    img.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200'%3E%3Crect width='100%25' height='100%25' fill='%23f0f0f0'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' fill='%23999'%3EИзображение недоступно%3C/text%3E%3C/svg%3E";
                }
            }
        }, true);
    }
    
    // Performance monitoring
    function monitorImagePerformance() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    if (entry.initiatorType === 'img' && entry.duration > 1000) {
                        console.warn('Slow image loading detected:', entry.name, 'Duration:', entry.duration + 'ms');
                    }
                });
            });
            
            observer.observe({ entryTypes: ['resource'] });
        }
    }
    
    // Initialize optimizations
    function init() {
        preloadCriticalImages();
        handleImageErrors();
        monitorImagePerformance();
    }
    
    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();