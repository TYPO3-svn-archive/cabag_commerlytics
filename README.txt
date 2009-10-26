This extension provides ecommerce tracking with google analytics.
If any other extension puts google analytics code in the page it has to be removed, for example like this:

[globalVar = GP:tx_commerce_pi3|yellowpay = success][globalVar = GP:tx_commerce_pi3|terms = termschecked]
	page.30 >
[global]

[globalVar = TSFE:id = 208]
	page.30 < plugin.tx_cabagcommerlytics_pi1
[global]

Page ID 208 is the redirect page if one is needed.


the general google analytics code must include the following code before any ecommerce code:
					pageTracker._setDomainName("none");
					pageTracker._setAllowHash(false);
					pageTracker._setAllowLinker(true);
					pageTracker._trackPageview();
