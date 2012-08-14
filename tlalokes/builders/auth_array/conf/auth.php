<?php
$auth['users']['admin']['active'] = false;
$auth['users']['admin']['password'] = '21232f297a57a5a743894a0e4a801fc3';
$auth['users']['admin']['role'] = 'Administrator';

$auth['roles']['Administrator'] = true;

$auth['profiles']['prof1'] = array( 'Administrator' );

$auth['permissions']['prof1']['TestCtl'] = array( 'method_one', 'method_two' );