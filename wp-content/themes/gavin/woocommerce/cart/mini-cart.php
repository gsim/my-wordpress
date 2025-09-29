<a class="mini-cart-toggle" href="<?php echo esc_url(wc_get_cart_url()); ?>">
    <i class="fa-solid fa-bag-shopping" style="font-size:24px; color:#555;"></i>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
</a>
<div class="mini-cart-dropdown">
    <?php if (!WC()->cart->is_empty()) : ?>
        <ul class="woocommerce-mini-cart cart_list product_list_widget">
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = $cart_item['data'];
                $product_id = $cart_item['product_id'];
                ?>
                <li class="woocommerce-mini-cart-item">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                        <?php echo $_product->get_image(); ?>
                        <?php echo $_product->get_name(); ?>
                    </a>
                    <span class="quantity"><?php echo $cart_item['quantity']; ?> × <?php echo WC()->cart->get_product_price($_product); ?></span>
                </li>
            <?php } ?>
        </ul>
        <p class="woocommerce-mini-cart__total total">
            <strong>Subtotal:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?>
        </p>
        <p class="woocommerce-mini-cart__buttons buttons">
            <a href="<?php echo wc_get_cart_url(); ?>" class="button wc-forward">View basket</a>
            <a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout wc-forward">Checkout</a>
        </p>
    <?php else: ?>
        <p class="woocommerce-mini-cart__empty-message">No products in the basket.</p>
    <?php endif; ?>
</div>