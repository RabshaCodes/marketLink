    <!-- Pre-Order Cart Drawer -->
    <div class="cart-overlay" id="cartOverlay" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="cartDrawerTitle" hidden>
        <aside class="cart-drawer" aria-label="Pre-order cart panel">
            <div class="cart-drawer-header">
                <h3 id="cartDrawerTitle">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Your Pre-Order Cart
                </h3>
                <button class="cart-close" id="cartClose" aria-label="Close cart">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="cart-drawer-body">
                <p>View your active session cart by clicking checkout below.</p>
            </div>
            <div class="cart-drawer-footer">
                <div class="cart-total">
                    <span>Total Estimated:</span>
                    <strong>PKR 0</strong>
                </div>
                <a href="<?php echo isset($base_url) ? $base_url : '/marketlinkh/'; ?>checkout.php" class="btn btn-primary cart-proceed">Proceed to Pickup Pre-Order <span aria-hidden="true">&rarr;</span></a>
            </div>
        </aside>
    </div>

    <script src="<?php echo isset($base_url) ? $base_url : '/marketlinkh/'; ?>assets/js/main.js"></script>
    <?php if(isset($extra_js)) echo $extra_js; ?>
</body>
</html>
