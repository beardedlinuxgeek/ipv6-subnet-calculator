<?php
require_once 'Network.php'; // Include the Network class


/**
 * Convert an IPv6 from human readable format to a hexadecimal array
 * 
 * @param	string	human readable IPv6 address
 * @return	array	IPv6 address in hex (8 groups of 4 hex digits)
 */
function human_to_hex( $human_ip )
{
	// Convert the human readable form to a string of 16 8-bit ASCII characters
	$addr = inet_pton($human_ip);
	
	// Convert from ascii to decimal to hexadecimal and concatenate
	$hex_string = '';
	foreach( str_split($addr) as $char)
	{
		$hex_string .= str_pad( dechex(ord($char)), 2, '0', STR_PAD_LEFT );
	}
	
	// Split the string into an array of 8 groups of 16-bit values
	// with each group represented as 4 hexadecimal digits
	return str_split( $hex_string, 4 );
}


/**
 * Convert an IPv6 hex array to human readable format
 * 
 * @param	array	IPv6 address in hex (8 groups of 4 hex digits)
 * @return	string	human readable IPv6 address
 */
function hex_to_human( $hex_ip )
{
	// Convert array to string and make sure each unit has 4 digits
	$hex_string = '';
	foreach( $hex_ip as $unit )
	{
		$hex_string .= str_pad( $unit, 4, '0', STR_PAD_LEFT );
	}
	
	// Convert to array of 16 8-bit groups, currently represented as 2 hex digits
	$hex_array = str_split($hex_string, 2);
	
	// Convert to a string of 16 8-bit ASCII characters
	$addr = '';
	foreach( $hex_array as $char )
	{
		$addr .= chr( hexdec($char) );
	}
	
	// Convert to human readable format
	return inet_ntop( $addr );
}


/**
 * Print the network in tree form
 * 
 * @param	Network	The network to print
 * @return	void
 */
function print_network( $network )
{
	echo $network->position;
	
	$indent = substr_count($network->position, '.');
	for( $i=0; $i<$indent; $i++ )
		echo ' ';
	
	echo hex_to_human($network->ip) . '/' . $network->prefix . "\n";
	
	foreach( $network->subnets as $subnet )
		print_network($subnet);
}

// Quick demo, update later
$human_ip = '2001:9fe:a:80::';
$prefix = 60;
$levels = array(3, 4);

$ip = human_to_hex( $human_ip );
$top_level = new Network($ip, $prefix, $levels, '');

header('Content-Type: text/plain');
print_network($top_level);
