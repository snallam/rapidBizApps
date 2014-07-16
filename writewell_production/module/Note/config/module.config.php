<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Note\Controller\Note' => 'Note\Controller\NoteController',
				),
		),
		// The following Note is new and should be added to your file
		'router' => array(
				'routes' => array(
						'Note' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/note[/][:action][/:id][/:status]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[a-zA-Z][a-zA-Z0-9]*',
												'status' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Note\Controller\Note',
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