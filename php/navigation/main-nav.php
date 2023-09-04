<?php
/**
 * Main navigation template
 *
 * This is a starter theme, a boilerplate,
 * so no mobile menu toggle is provided as
 * these can be styled and structured in
 * many ways. This is simply navigation
 * bones upon which to build.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

?>
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
	<ul class="nav-list main-nav-list">
		<?php foreach ( $staticContent as $nav_item ) : ?>
		<li>
			<a href="<?php echo $nav_item->permalink() ?>"><?php echo $nav_item->title() ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</nav>
