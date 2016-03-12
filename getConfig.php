<?php

$rpid = (isset($_GET['rpid'])) ? $_GET['rpid'] : '';

$config = array (
  '00000000e3c069d3' => array(
    'name' => 'fredrik',
    'icals' => array(
      'url1',
      'url2'
    ),
    'apps' => array(
      'vasttrafik' => 1
    )
  ),
  'xxx' => array(
    'name' => 'per',
    'icals' => array(
      'urlx',
      'urly'
    ),
    'apps' => array(
      'vasttrafik' => 1
    )
  )
);

if (isset($config[$rpid])) {
  echo json_encode($config[$rpid]);
}
