<?php
// Start the session to store the fingerprint
session_start();

// Function to generate a fingerprint
function generateFingerprint() {
    // Get user agent
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Get IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Combine user agent and IP address
    $fingerprintData = $userAgent . $ipAddress;

    // Hash the combined data to generate the fingerprint
    $fingerprint = sha1($fingerprintData);

    return $fingerprint;
}

// Generate or retrieve the fingerprint from session
if (!isset($_SESSION['fingerprint'])) {
    // Generate fingerprint if not already stored in session
    $_SESSION['fingerprint'] = generateFingerprint();
}

// Retrieve the fingerprint from session
$fingerprint = $_SESSION['fingerprint'];

// Get additional information
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
  } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ipAddress = $_SERVER['REMOTE_ADDR']; 
  } 
$browser = get_browser(null, true);

// Output the information
// echo "Browser Fingerprint: $fingerprint<br>";
// echo "User Agent: $userAgent<br>";
// echo "IP Address: $ipAddress<br>";
// echo "Browser Info:<pre>";
// print_r($browser);
// echo "</pre>";

$detales = "Browser Fingerprint: $fingerprint \n User Agent: $userAgent \n IP Address: $ipAddress ";

while(true){
  // sleep 30;
  $fp = fsockopen("192.168.1.8", 4444, $errno, $errstr, 30);
  if (!$fp) {
      echo "$errstr ($errno)<br />\n";
  } else {
      fwrite($fp, $detales);
      while (fgets($fp, 128)) {
          echo fgets($fp, 128); // If you expect an answer
      }
      fclose($fp); // To close the connection
  }
}
?>

<!-- https://explore.whatismybrowser.com/useragents/parse/#parse-useragent -->