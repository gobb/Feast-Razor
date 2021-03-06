<?php
/**
 * The Razor template for Lilina
 *
 * A 3-column layout, designed to look like a desktop application
 * @author Ryan McCue <http://ryanmccue.info/>
 */
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php bloginfo( 'name' ) ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/style.css" />
<?php
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
	<div id="header">

		<h1 id="title"><a href="<?php echo site_url() ?>"><?php bloginfo( 'name' ) ?></a></h1>
		<p id="messagearea"></p>
		<ul id="menu">
			<li id="help"><a href="#help" title="Learn how to use me!">Help</a></li>
<?php
	if ( is_user_logged_in() ):
?>
			<li id="settings"><a href="<?php echo admin_url() ?>" title="Change your settings">Settings</a></li>
			<li id="logout"><a href="<?php echo wp_logout_url() ?>" title="Log out">Log out</a></li>
<?php
	else:
?>
			<li id="login"><a href="<?php echo wp_login_url() ?>">Log in</a></li>
<?php
	endif;
?>
		</ul>
	</div>

	<div id="sidebar">
		<div class="item-list">
			<h2>Library</h2>
			<ul id="library">
			</ul>
			<h2>Feeds</h2>
			<ul id="feeds-list">
			</ul>
		</div>
	</div>

	<div id="items-list-container">
		<ol id="items-list">
		</ol>
	</div>

	<div id="item-view">
		<div id="item">
			<div id="item-content">
				<p>Pick a feed from the left to get started!</p>
			</div>
		</div>
		<div class="footer">
			<ul>
			</ul>
		</div>
	</div>

	<script type="text/template" id="tmpl-sidebar-item">
		<a href="{{ data.url }}"
			<# if (data.genericon) {#>
				class="genericon-{{ data.genericon }}"
			<# } #>
			>
			<# if ( data.icon ) { #>
				<img src="{{ data.icon }}" />
			<# } #>
			<span>{{ data.title }}</span>
		</a>
	</script>

	<script type="text/template" id="tmpl-footer-item">
		<a>{{ data.name }}</a>
	</script>

	<script type="text/template" id="tmpl-list-item">
		<a href="{{ data.permalink }}">
			<span class="item-title">{{ data.title }}</span>
			<span class="sep">from</span>
			<span class="item-source">{{ data.feed.title }}</span>
			<span class="sep">at</span>
			<span class="item-date" title="{{ Razor.dateToHuman(data.timestamp) }}">{{ Razor.dateToRelative(data.timestamp) }}</span>
		</a>
	</script>

	<script type="text/template" id="tmpl-item-viewer">
		<div id="heading">
			<h2 class="item-title"><a href="{{ data.original }}">{{ data.title }}</a></h2>
			<p class="item-meta">
				<span class="item-source">From <a href="{{ Razor.Feeds.get(data.feed_id).get('uri') }}">{{ Razor.Feeds.get(data.feed_id).get('title') }}</a></span>.
				<span class="item-date">Posted <abbr title="{{ Razor.dateToHuman(data.timestamp) }}">{{ Razor.dateToRelative(data.timestamp) }}</abbr></span
				<# if (data.author.name) { #>
					> <span class="item-author">by <a href="{{ data.author.uri }}">{{ data.author.name }}</a></span
				<# } #>
			>.</p>
		</div>
		<div id="item-content">{{{ data.content }}}</div>
	</script>

	<?php wp_footer(); ?>

<?php
	$feeds = array_values(Feast_API::getFeeds());
	$items = array_values(Feast_API::getItems());
?>
	<script>
		Razor.baseURL = <?php echo json_encode(Feast_API::getURL()) ?>;
		Razor.scriptURL = <?php echo json_encode(get_stylesheet_directory_uri()) ?>;

		Razor.Feeds.reset(<?php echo json_encode($feeds) ?>);
		Razor.Items.reset(<?php echo json_encode($items) ?>);
	</script>
</body>
</html>