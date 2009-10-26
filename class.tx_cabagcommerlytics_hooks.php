<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2009 Dimitri König <dk@cabag.ch>
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

require_once(PATH_t3lib.'class.t3lib_div.php');

class tx_cabagcommerlytics_hooks {

	function postFinish($basket, $reference) {
		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_commerlytics']);
		$trackingCode = $ext_conf['gaCode'];
		if(empty($trackingCode)) {
			return;
		}
		$orderID = $GLOBALS['TSFE']->fe_user->getKey('ses', 'orderID');
		if(empty($orderID)) {
			$orderID = $reference->orderId;
		}
		if(empty($orderID)) {
			return;
		}
		$shopName = $ext_conf['shopName'];
		$total_sum_gross = $this->getFormattedNumber($basket->basket_sum_gross);
		$total_sum_tax = $this->getFormattedNumber($basket->basket_sum_gross - $basket->basket_sum_net);
		$shippingCost = 0;
		$city = $reference->MYSESSION['billing']['city'] ? $reference->MYSESSION['billing']['city'] : '';
		$state = $reference->MYSESSION['billing']['region'] ? $reference->MYSESSION['billing']['region'] : '';
		$country = $reference->MYSESSION['billing']['country'] ? $reference->MYSESSION['billing']['country'] : '';
		$articles = array();

		foreach($basket->basket_items as $item) {
			switch($item->article->article_type_uid) {
				case 3:
					$shippingCost = $this->getFormattedNumber($item->priceGross);
					break;
				case 1:
					$newarticle = array();
					$newarticle['sku'] = $item->article->uid;
					$newarticle['producttitle'] = $item->product->title;
					$newarticle['articletitle'] = $item->article->title;
					$newarticle['unitprice'] = $this->getFormattedNumber($item->priceGross);
					$newarticle['quantity'] = $item->quantity;
					$articles[] = $newarticle;
					break;
			}
		}		

		$articlecode = '';
		foreach($articles as $newart) {
			$articlecode .= '
				pageTracker._addItem(
					"' . $orderID . '", // order ID - necessary to associate item with transaction
					"' . $newart['sku'] . '", // SKU/code - required
					"' . $newart['producttitle'] . '", // product name
					"' . $newart['articletitle'] . '", // category or variation
					"' . $newart['unitprice'] . '", // unit price - required
					"' . $newart['quantity'] . '" // quantity - required
				);
			';
		}
		
		$ga_jscode = '
			<script type="text/javascript">
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
				try {
					var pageTracker = _gat._getTracker("' . $trackingCode . '");
					pageTracker._setDomainName("none");
					pageTracker._setAllowHash(false);
					pageTracker._setAllowLinker(true);
					pageTracker._trackPageview();

					pageTracker._addTrans(
						"' . $orderID . '", // order ID - required
						"' . $shopName . '", // affiliation or store name
						"' . $total_sum_gross . '", // total - required
						"' . $total_sum_tax . '", // tax
						"' . $shippingCost . '", // shipping
						"' . $city . '", // city
						"' . $state . '", // state or province
						"' . $country . '" // country
					);
					' . $articlecode . '
					pageTracker._trackTrans();
				} catch(err) {}</script>
		';

		if(intval($ext_conf['redirectSite']) != 0) {
			$redirect_url = 'http://'.$_SERVER['SERVER_NAME'].'/'.$GLOBALS['TSFE']->cObj->getTypoLink_URL($ext_conf['redirectSite']);
			$redirect_jscode = '
				<META HTTP-EQUIV="Refresh" CONTENT="0; URL='.$redirect_url.'">
				<script language="javascript" type="text/javascript">
				<!-- //
					window.location.href = "'.$redirect_url.'";
				// -->
				</script>
			';

			$GLOBALS['TSFE']->additionalHeaderData['cabag_commerlatics'] = $redirect_jscode;
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'ga_ecommerce', $ga_jscode);
		} else {
			$GLOBALS['TSFE']->additionalHeaderData['cabag_commerlatics'] = $ga_jscode;
		}
	}

	function getFormattedNumber($number) {
		return number_format(intval($number) / 100, 2, '.', '');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']["ext/cabag_commerlytics/class.tx_cabagcommerlytics_hooks.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']["ext/cabag_commerlytics/class.tx_cabagcommerlytics_hooks.php"]);
}

?>
