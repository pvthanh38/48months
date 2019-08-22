<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$eat_variables['templates'] = array(
									array(
									'group_name' => 'Templates',
									'group_data' => array(
															array(
																'name' =>  __( 'Template 1', 'everest-admin-theme-lite' ),
																'value' => 'temp-1',
																'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/templates/template1.jpg'
																),
															array(
																'name' =>  __( 'Template 2', 'everest-admin-theme-lite' ),
																'value' => 'temp-2',
																'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/templates/template2.jpg'
																),
															array(
																'name' =>  __( 'Template 3', 'everest-admin-theme-lite' ),
																'value' => 'temp-3',
																'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/templates/template3.jpg'
																),
															array(
																'name' =>  __( 'Template 4', 'everest-admin-theme-lite' ),
																'value' => 'temp-4',
																'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/templates/template4.jpg'
																)
														)
									)
								);

$eat_variables['login_form_templates'] = array(
												array(
													'name' =>  __( 'Template 1', 'everest-admin-theme-lite' ),
													'value' => 'template-1',
													'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/login-form/template1.jpg'
													),
												array(
													'name' =>  __( 'Template 2', 'everest-admin-theme-lite' ),
													'value' => 'template-2',
													'img' => EAT_ADMIN_THEME_IMAGE_DIR . '/login-form/template2.jpg'
													)
											);

$eat_variables['default_settings'] = $default_settings = Array
								(
								    'general-settings' => Array
								        (
								            'template' =>'',
								            'background' => Array
								                (
								                    'type' => '',
								                    'background-color' => Array
								                        (
								                            'color' =>''
								                        )
								                ),

								            'favicon' => Array
								                (
								                    'url' =>'',
								                ),
								        ),

								    'admin_bar' => Array
								        (
								            'layout' => 'fixed',
								            'outer_background_settings' => array(
															            'menu' => Array
															                (
															                    'background_selection' => Array
															                        (
															                            'type' => 'default',
															                            'background-color' => Array
															                                (
															                                    'color' =>''
															                                ),
															                        ),
															                ),

															            'sub_menu' => Array
															                (
															                    'background_selection' => Array
															                        (
															                            'type' => 'default',
															                            'background-color' => Array
															                                (
															                                    'color' =>''
															                                )
															                        )
															                )
								            )

								        ),

								    'admin_menu' => Array
								        (
								            'outer_background_settings' => array(
								            	'menu' => Array
										                        (
										                            'type' => '',
										                            'background-color' => Array
										                                (
										                                    'color' =>''
										                                )

										                        ),

							                    'sub_menu' => Array
											                        (
											                            'type' => '',
											                            'background-color' => Array
											                                (
											                                    'color' =>''
											                                )

											                        )

										    )
								        ),

								    'footer_info' => Array
								        (
								            'left' => Array
								                (
								                    'custom_texts' => Array
								                        (
								                            'content' => ''
								                        )

								                ),
								            'right' => Array
								                (
								                    'custom_texts' => Array
								                        (
								                            'content' => ''
								                        )

								                )

								        ),

								    'custom_login' => Array
								        (
								            'background' => Array
								                (
								                    'type' => '',
								                    'background-color' => Array
								                        (
								                            'color' => ''
								                        )
								                ),

								            'login_form' => Array
								                (
								                    'template' => '',
								                )
								        )
								);
