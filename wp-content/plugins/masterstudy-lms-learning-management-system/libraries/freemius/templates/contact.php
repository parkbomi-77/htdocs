<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	$links = [
		'documentation_url' => 'https://docs.stylemixthemes.com/masterstudy-lms/',
		'video_url' => '',
		'support_url' => 'https://support.stylemixthemes.com/auth/login'
	];
?>
<style>
	.welcome-panel .welcome-panel-column:first-child {
		width: 32%;
	}
	.welcome-panel .welcome-panel-column {
		width: 34%;
	}
</style>

<div class="wrap">
	<h1 class="wp-heading-inline">Contact Us</h1>

	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			<h2>Welcome to Support page!</h2>
			<p class="about-description">We’ve assembled some links to get you started:</p>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h3>Get Started</h3>
					<a class="button button-primary button-hero" href="<?php echo esc_url( $links['documentation_url'] ); ?>" target="_blank">Documentation</a>
				</div>
				<?php if ( ! empty( $links['video_url'] ) ) : ?>
				<div class="welcome-panel-column">
					<h3>Video Tutorials</h3>
					<a class="button button-primary button-hero" href="<?php echo esc_url( $links['video_url'] ); ?>" target="_blank">Go to Tutorials</a>
					<p>Video Tutorials of basic knowledge, features and options.</p>
				</div>
				<?php endif; ?>
				<div class="welcome-panel-column">
					<h3>Support</h3>
					<a class="button button-primary button-hero" href="<?php echo esc_url( $links['support_url'] ); ?>" target="_blank">Create a Ticket</a>
					<p>We're experiencing a much larger number of tickets than usual.<br> So the waiting time is longer than expected.</p>
				</div>
			</div>
		</div>
	</div>
</div>
