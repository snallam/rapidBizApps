<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Project\Controller\Project' => 'Project\Controller\ProjectController',
				),
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'Project' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/project[/][:action][/:id][/:status]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[a-zA-Z][a-zA-Z0-9]*',
												'status' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Project\Controller\Project',
												'action'     => 'index',
										),
								),
						),
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'view' => __DIR__ . '/../view',
				),
		),
);