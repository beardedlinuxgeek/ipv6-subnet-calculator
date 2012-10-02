ipv6-subnet-calculator
======================

Quick demo:

````php
include 'calculator_tools.php';
include 'Network.php';

$human_ip = '2001:9fe:a:80::';
$prefix = 60;
$levels = array(3, 4);

$ip = human_to_hex( $human_ip );
$top_level = new Network($ip, $prefix, $levels);

header('Content-Type: text/plain');
print_network($top_level);
````
