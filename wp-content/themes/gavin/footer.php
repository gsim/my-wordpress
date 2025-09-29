<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="footer-container">
		
		<!-- Left Column: Logo + Footer Menu -->
		<div class="footer-col footer-col-left">
			<nav class="footer-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-links',
					'container'      => false,
				) );
				?>
			</nav>
		</div>

		<!-- Middle Column: Payments + Company Info -->
		<div class="footer-col footer-col-middle">
			<h3>Secure Payments with Stripe</h3>
			<div class="payment-icons">
				<img src="<?php echo WC()->plugin_url(); ?>/assets/images/payment-methods/amex.svg" alt="Amex">
				<img src="<?php echo WC()->plugin_url(); ?>/assets/images/payment-methods/discover.svg" alt="Discover">
				<img src="<?php echo WC()->plugin_url(); ?>/assets/images/payment-methods/mastercard.svg" alt="Mastercard">
				<img src="<?php echo WC()->plugin_url(); ?>/assets/images/payment-methods/visa.svg" alt="Visa">
			</div>
<ul class="footer-social">
  <li>
    <a href="https://www.instagram.com/royalgiftinguk/" target="_blank">
      <i class="fab fa-instagram"></i>
    </a>
  </li>
  <li>
    <a href="https://www.facebook.com/profile.php?id=61577228583277" target="_blank">
      <i class="fab fa-facebook-f"></i>
    </a>
  </li>
  <li>
    <a href="https://za.pinterest.com/royalgifting/" target="_blank">
      <i class="fab fa-pinterest-p"></i>
    </a>
  </li>
</ul>

		</div>

		<!-- Right Column: Search + Social -->
		<div class="footer-col footer-col-right">
			<?php get_search_form(); ?>
			<div class="company-info">
				<p>Powered by <a href="https://retailwebworks.com/" target="_blank" rel="nofollow">Retail Web Works Limited</a></p>
				<p>Company number 16465992</p><p>VAT Number : In progress</p>
				<p>2nd Floor College House</p><p>17 King Edwards Road,</p><p>RUISLIP, London, HA4 7AE</p><p>UNITED KINGDOM</p>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
