<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Dimitri König <dk@cabag.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin '' for the 'cabag_commerlytics' extension.
 *
 * @author    Dimitri König <dk@cabag.ch>
 * @package    TYPO3
 * @subpackage    tx_cabagcommerlytics
 */
class tx_cabagcommerlytics_pi1 extends tslib_pibase {
    var $prefixId      = 'tx_cabagcommerlytics_pi1';        // Same as class name
    var $scriptRelPath = 'pi1/class.tx_cabagcommerlytics_pi1.php';    // Path to this script relative to the extension dir.
    var $extKey        = 'cabag_commerlytics';    // The extension key.
    var $pi_checkCHash = true;
    
    /**
     * The main method of the PlugIn
     *
     * @param    string        $content: The PlugIn content
     * @param    array        $conf: The PlugIn configuration
     * @return    The content that is displayed on the website
     */
    function main($content, $conf)    {
        $jscode = $GLOBALS['TSFE']->fe_user->getKey('ses', 'ga_ecommerce');
        if(!empty($jscode)) {
        	$GLOBALS['TSFE']->fe_user->setKey('ses', 'ga_ecommerce', null);
        	return $jscode;
        }
    }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_commerlytics/pi1/class.tx_cabagcommerlytics_pi1.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_commerlytics/pi1/class.tx_cabagcommerlytics_pi1.php']);
}

?>
