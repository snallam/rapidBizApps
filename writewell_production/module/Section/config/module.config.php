<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Section\Controller\Section' => 'Section\Controller\SectionController',
				),
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'Section' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/section[/][:action][/:id][/:status]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[a-zA-Z][a-zA-Z0-9]*',
												'status' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Section\Controller\Section',
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