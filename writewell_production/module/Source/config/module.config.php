<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Source\Controller\Source' => 'Source\Controller\SourceController',
				),
		),
		// The following Source is new and should be added to your file
		'router' => array(
				'routes' => array(
						'Source' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/source[/][:action][/:id][/:status]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[a-zA-Z][a-zA-Z0-9]*',
												'status' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Source\Controller\Source',
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