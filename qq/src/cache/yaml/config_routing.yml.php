<?php
return array (
  'homepage' => 
  array (
    'url' => '/',
    'param' => 
    array (
      'controller' => 'default',
      'action' => 'default',
    ),
  ),
  'default_index' => 
  array (
    'url' => '/:controller',
    'param' => 
    array (
      'action' => 'default',
    ),
  ),
  'components' => 
  array (
    'url' => '/:component/:controller/:action',
    'requirements' => 
    array (
      'component' => 'vote|lottery|fileupload|videoupload',
      'controller' => 'vote|lottery|fileupload|videoupload',
    ),
  ),
  'default' => 
  array (
    'url' => '/:controller/:action/*',
  ),
);