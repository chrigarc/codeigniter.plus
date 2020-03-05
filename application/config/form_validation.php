<?php

$config = array(
	'user.store' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'required'
		),
		array(
			'field' => 'password_confirmed',
			'label' => 'Password Confirmation',
			'rules' => 'required'
		),
		array(
			'field' => 'name',
			'label' => 'Nombre',
			'rules' => 'required'
		),
		array(
			'field' => 'last_name',
			'label' => 'Apellido',
			'rules' => 'required'
		),
	),
	'user.update' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required'
		),
		array(
			'field' => 'name',
			'label' => 'Nombre',
			'rules' => 'required'
		),
		array(
			'field' => 'last_name',
			'label' => 'Apellido',
			'rules' => 'required'
		),
	),
	'auth.login' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'required'
		),
	),
);
