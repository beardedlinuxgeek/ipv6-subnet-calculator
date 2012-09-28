<?php
Class Network {
	
	/**
	 * The IP of the network. Array of 8 groups, each consisting of 4 hex digits
	 */
	var $ip;
	/**
	 * The prefix of the network (/49 to /63)
	 */
	var $prefix;
	/**
	 * A string denoting the position of the subnet in the hierarchy
	 */
	var $position;
	/**
	 * Array of child networks to this network
	 */
	var $subnets = array();
	
	
	/**
	 * Create a Network object with multiple levels of child networks
	 * 
	 * @param	array	IPv6 address in hex (8 groups of 4 hex digits)
	 * @param	int  	Subnet prefix of the network
	 * @param	array	Number of networks per level
	 * @param	string	Position of the network
	 * @return	void
	 */
	function __construct( $ip, $prefix, $levels, $position = '')
	{
		$this->ip       = $ip;
		$this->prefix   = $prefix;
		$this->position = $position;
		
		// Set the number of child networks and remove it from the level list
		$child_network_count = array_shift($levels);
		
		// If there are child networks, create them
		if ( $child_network_count > 0 )
			$this->add_subnets($child_network_count, $levels);
	}
	
	
	/**
	 * Add child networks to this network
	 * 
	 * @param	int  	Number of direct child networks
	 * @param	array	Networks per level for the remaining levels
	 * @return	void
	 */
	function add_subnets( $child_network_count, $levels )
	{
		// number of bits needed to make $child_network_count subnets
		$bits = ceil( log($child_network_count, 2) );
		
		// number bits already in use
		$bits_in_use = $this->prefix - 48;
		
		// The prefix of the new subnets
		$subnet_prefix = $this->prefix + $bits;
		
		// We can't allocate anything smaller than a /64
		if ( $subnet_prefix > 64 )
			die('Not enough bits');
		
		// The amount to increase each subnet
		$increment = pow( 2, 16 - ( $bits_in_use + $bits ) );
		
		
		for ( $i = 0; $i < $child_network_count; $i++ )
		{
			// Increment the 4th block
			$subnet_ip = $this->ip;
			$subnet_ip[3] = dechex( hexdec($subnet_ip[3]) + ($increment*$i) );
			
			// Add this subnet position onto the position string 
			$subnet_position = $this->position . ($i+1) . '.';
			
			// Create a new network and save it
			$this->subnets[] = new Network( $subnet_ip, $subnet_prefix, $levels, $subnet_position );
		}
	}
	
}