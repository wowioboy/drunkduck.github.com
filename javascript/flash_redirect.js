// Flash Detection / Redirect  v1.1.1
// documentation: http://www.dithered.com/javascript/flash_redirect/index.html
// license: http://creativecommons.org/licenses/by/1.0/
// code by Chris Nott (chris[at]dithered[dot]com)
// requires: flash_detect.js (http://www.dithered.com/javascript/flash_detect/index.html)


// use flash_detect.js to return the Flash version
var flashVersion = getFlashVersion();
var upgradeFlashURL = "http://www.adupteyshun.com/upgrade_flash.php?nodetect=1";

// Redirect to appropriate page
if (flashVersion == flashVersion_DONTKNOW || flashVersion == null || flashVersion<7) {
  location.replace(upgradeFlashURL);
}
