<?php

/**
 * CollabPress Template Tags
 *
 * @package CollabPress
 * @subpackage TemplateTags
 */

function cp_has_projects( $args = array() ) {
	global $cp;

	$defaults = array(
		'post_type' => 'cp-projects',
		'posts_per_page' => -1,
		'project_id' => NULL,
		'task_list_id' => NULL,
		'status' => 'any',
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args );

	$cp->projects = new WP_Query( $args );

	return $cp->projects->have_posts();
}

function cp_projects() {
	global $cp;
	// Put into variable to check against next
	$have_posts = $cp->projects->have_posts();

	// Reset the post data when finished
	if ( empty( $have_posts ) )
		wp_reset_postdata();
	
	return $have_posts;
}

function cp_the_project() {
	global $cp;

	return $cp->projects->the_post();
}

function cp_project_title() {
	global $cp;
	echo '<h2>' . $cp->project->post_title . '</h2>';
}

/**
 * Return current project ID in $cp global
 */
function cp_get_project_id() {
	global $cp;
	if( ! empty( $cp->project->ID ) )
		return $cp->project->ID;
	else
		return false;
}

function cp_get_project_permalink( $project_id = 0 ) {
	if ( ! $project_id ) {
		global $cp;
		if ( ! empty( $cp->project->ID ) )
			$project_id = $cp->project->ID;
		else
			$project_id = get_the_ID();
	}
	return add_query_arg( array( 'project' => $project_id ), CP_DASHBOARD );
}

	function cp_project_permalink( $project_id = 0 ) {
		echo cp_get_project_permalink( $project_id );
	}

function cp_get_project_tasks_permalink( $project_id = 0 ) {
	if ( ! $project_id ) {
		global $cp;
		$project_id = $cp->project->ID;
	}
	return add_query_arg( array( 'project' => $project_id, 'view' => 'tasks' ), CP_DASHBOARD );
}

	function cp_project_tasks_permalink( $project_id = 0 ) {
		echo cp_get_project_tasks_permalink( $project_id );
	}

function cp_get_project_calendar_permalink( $project_id = 0 ) {
	if ( ! $project_id ) {
		global $cp;
		$project_id = $cp->project->ID;
	}
	return add_query_arg( array( 'project' => $project_id, 'view' => 'calendar' ), CP_DASHBOARD );
}

	function cp_project_calendar_permalink( $project_id = 0 ) {
		echo cp_get_project_calendar_permalink( $project_id );
	}

function cp_get_project_files_permalink( $project_id = 0 ) {
	if ( ! $project_id ) {
		global $cp;
		$project_id = $cp->project->ID;
	}
	return add_query_arg( array( 'project' => $project_id, 'view' => 'files' ), CP_DASHBOARD );
}

	function cp_project_files_permalink( $project_id = 0 ) {
		echo cp_get_project_files_permalink( $project_id );
	}

function cp_get_project_users_permalink( $project_id = 0 ) {
	if ( ! $project_id ) {
		global $cp;
		$project_id = $cp->project->ID;
	}
	return add_query_arg( array( 'project' => $project_id, 'view' => 'users' ), CP_DASHBOARD );
}

	function cp_project_users_permalink( $project_id = 0 ) {
		echo cp_get_project_users_permalink( $project_id );
	}

// Retrieve all tasks in a task list with a specific status
// todo: maybe remove in the future
function cp_get_tasks( $args = array() ) {
	$defaults = array(
		'post_type' => 'cp-tasks',
		'posts_per_page' => -1,
		'project_id' => NULL,
		'task_list_id' => NULL,
		'status' => 'any',
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	if ( $task_list_id ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-task-list-id',
			'value' => $task_list_id,
		);
	}

	if ( $project_id ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-project-id',
			'value' => $project_id,
		);
	}

	if ( $status != 'any' ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-task-status',
			'value' => $status,
		);
	}

	return get_posts( $args );
}

function cp_task_title() {
	global $cp;
	echo '<h2>' . $cp->task->post_title . '</h2>';
}

function cp_task_content() {
	global $cp;
	echo $cp->task->post_content;
}

/**
 * The main tasks loop.
 *
 * @since 1.2
 *
 * @param mixed $args All the arguments supported by {@link WP_Query},
 * 	as well as a few custom arguments specific to CollabPress:
 *		'task_list_id' - a task list
 * 		'project_id' - a project
 * 		'status' - a task status
 * @uses WP_Query To make query and get the tasks
 * @return object Multidimensional array of forum information
 */
function cp_has_tasks( $args = array() ) {
	global $cp;

	$defaults = array(
		'post_type' => 'cp-tasks',
		'posts_per_page' => -1,
		'project_id' => NULL,
		'task_list_id' => NULL,
		'status' => 'any',
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args );

	if ( $task_list_id ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-task-list-id',
			'value' => $task_list_id,
		);
	}

	if ( $project_id ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-project-id',
			'value' => $project_id,
		);
	} else if ( ! empty( $cp->project ) ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-project-id',
			'value' => $cp->project->ID,
		);
	}

	if ( $status != 'any' ) {
		$args['meta_query'][] = array( 
			'key' => '_cp-task-status',
			'value' => $status,
		);
	}

	$cp->tasks = new WP_Query( $args );

	return $cp->tasks->have_posts();
}

function cp_tasks() {
	global $cp;
	// Put into variable to check against next
	$have_posts = $cp->tasks->have_posts();

	// Reset the post data when finished
	if ( empty( $have_posts ) )
		wp_reset_postdata();
	
	return $have_posts;
}

function cp_the_task() {
	global $cp;
	return $cp->tasks->the_post();
}

function cp_task_permalink() {
	global $cp, $post;
	$permalink = add_query_arg( 
		array( 
			'project' => $cp->project->ID,
			'task' => $post->ID,
			), 
		CP_DASHBOARD
	);
	echo $permalink;
}

function cp_project_links() {
	global $cp;
	?>
	<a href="<?php cp_permalink(); ?>">CP Dashboard</a>
	<a href="<?php cp_project_permalink(); ?>">Project Overview</a>
	<a href="<?php cp_project_calendar_permalink(); ?>">Calendar</a>
	<a href="<?php cp_project_tasks_permalink(); ?>">Tasks</a>
	<a href="<?php cp_project_files_permalink(); ?>">Files</a>
	<a href="<?php cp_project_users_permalink(); ?>">Users</a><?php
} 

function cp_overall_links() { ?>
	<a href="<?php cp_permalink(); ?>">Dashboard</a>
	<a href="<?php cp_calendar_permalink(); ?>">Calendar</a><?php
}

function cp_permalink() {
	echo CP_DASHBOARD;
}
function cp_get_sidebar() {
	?>
	<div class="collabpress-sidebar" style="border: dashed 1px black; width: 20%; max-width: 200px; min-height: 400px; padding: 5px; float: left">
		<div style="border: dashed 1px black; height: 200px; padding: 5px">calendar</div>
		<div style="border: dashed 1px black; height: 200px; padding: 5px">recent activity</div>
	</div>
	<?php
}

/**
 * The main files loop.
 *
 * @since 1.2
 *
 * @param mixed $args All the arguments supported by {@link WP_Query},
 * 	as well as a few custom arguments specific to CollabPress:
 *		'task_list_id' - a task list
 * 		'project_id' - a project
 * 		'status' - a task status
 * @uses WP_Query To make query and get the tasks
 * @return object Multidimensional array of forum information
 */
function cp_has_files( $args = array() ) {
	global $cp;

	$defaults = array(
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'project_id' => NULL,
		'post_status' => 'inherit'
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args );

	if ( $project_id ) {
		$args['post_parent'] = $project_id;
	} else if ( ! empty( $cp->project ) ) {
		$args['post_parent'] = $cp->project->ID;
	}

	$cp->files = new WP_Query( $args );
	return $cp->files->have_posts();
}

function cp_files() {
	global $cp;
	// Put into variable to check against next
	$have_posts = $cp->files->have_posts();

	// Reset the post data when finished
	if ( empty( $have_posts ) )
		wp_reset_postdata();
	
	return $have_posts;
}

function cp_the_file() {
	global $cp;
	return $cp->files->the_post();
}