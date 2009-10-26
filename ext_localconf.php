<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/pi3/class.tx_commerce_pi3.php']['finishIt'][] = 'EXT:cabag_commerlytics/class.tx_cabagcommerlytics_hooks.php:tx_cabagcommerlytics_hooks';

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_cabagcommerlytics_pi1.php', '_pi1', '', 0);

?>