<div class="collabpress">

	<div class="project-wide-nav">

		<?php cp_project_wide_nav(); ?>

	</div>
	<div class="clear"></div>
	<div class="calendar">
		<h3><?php _e( 'Calendar', 'collabpress' ); ?></h3>
		<?php
		$month = ! empty( $_REQUEST['month'] ) ? $_REQUEST['month'] : NULL;
		$year = ! empty($_REQUEST['year'] ) ? $_REQUEST['year'] : NULL;
		cp_draw_calendar(
			array(
				'month' => $month,
				'year' => $year,
			)
		); ?>
	</div>
</div>