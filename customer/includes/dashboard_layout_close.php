<?php
/**
 * Closes the wrappers opened by dashboard_layout.php and wires up the
 * scroll-reveal animation shared by every dashboard page.
 */
?>
    </div><!-- /.main-content -->
</div><!-- /.dash-layout -->

<script>
    // Staggered scroll-reveal for any element with .reveal
    (function () {
        var items = document.querySelectorAll('.reveal');
        if (!items.length) return;
        if (!('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    var d = e.target.getAttribute('data-delay');
                    if (d) e.target.style.transitionDelay = d + 'ms';
                    e.target.classList.add('in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        items.forEach(function (el) { io.observe(el); });
    })();

    // Animated count-up for stat values marked with .countup
    (function () {
        var nums = document.querySelectorAll('.countup');
        if (!nums.length) return;
        var run = function (el) {
            var target = parseFloat(el.getAttribute('data-count')) || 0;
            var start = null;
            var step = function (ts) {
                if (!start) start = ts;
                var p = Math.min((ts - start) / 900, 1);
                el.textContent = Math.floor(p * target);
                if (p < 1) requestAnimationFrame(step); else el.textContent = target;
            };
            requestAnimationFrame(step);
        };
        if (!('IntersectionObserver' in window)) { nums.forEach(run); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
        }, { threshold: 0.5 });
        nums.forEach(function (el) { io.observe(el); });
    })();
</script>
