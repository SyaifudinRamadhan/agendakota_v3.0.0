<?php
include_once( KEYSTROKE_ADDONS_DIR . '/include/custom-post-type-base.php');
/**
 * Project
 */
$project_args = array(
    'menu_icon' => 'dashicons-portfolio'
);
$project = new Axil_Register_Custom_Post_Type( 'Project', 'Projects', $project_args);
$project->add_taxonomy( 'Category', 'Categories', 'project-cat' );

/**
 * Case Studies
 */
$case_studies_args = array(
    'menu_icon' => 'dashicons-book-alt'
);
$case_studies = new Axil_Register_Custom_Post_Type( 'Case Study', 'Case Studies', $case_studies_args);
$case_studies->add_taxonomy( 'Category', 'Categories', 'case-studies-cat' );

/**
 * Teams
 */
$team_args = array(
    'menu_icon' => 'dashicons-groups'
);
$teams = new Axil_Register_Custom_Post_Type( 'Team', 'Teams', $team_args);
$teams->add_taxonomy( 'Category', 'Categories', 'team-cat' );





