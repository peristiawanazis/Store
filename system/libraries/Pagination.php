<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class CI_Pagination {

	var $base_url			= ''; // The page we are linking to
	var $total_rows  		= 0; // Total number of items (database results)
	var $per_page	 		= 10; // Max number of items you want shown per page
	var $num_links			=  2; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page	 		=  0; // The current page being viewed
	var $uri_segment		= 3;
	
	var $full_tag_open		= "<div class=\"pagination\"><ul>";
	var $full_tag_close		= '</ul></div>';
	var $first_tag_open		= "<li class=\"page\">";
	var $first_link   		= "&lsaquo;&lsaquo;";
	var $first_tag_close	= "</li>";
	var $last_tag_open		= "<li class=\"page\">";
	var $last_link			= "&rsaquo;&rsaquo;";
	var $last_tag_close		= "</li>";
	var $prev_tag_open		= "<li class=\"page\">";
	var $prev_link			= "&lsaquo;";
	var $prev_tag_close		= "</li>";
	var $next_tag_open		= "<li class=\"page\">";
	var $next_link			= "&rsaquo;";
	var $next_tag_close		= "</li>";	
	var $cur_tag_open		=  "<li class=\"current\">";
	var $cur_tag_close		= "</li>";	
	var $num_tag_open		=  "<li class=\"page\">";
	var $num_tag_close		= "</li>";	
	var $ajax= true; //oastera
	var $mobile= false; //oastera
	var $container= ''; //oastera

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	function CI_Pagination($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}
		log_message('debug', "Pagination Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		//print_r($params);
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
					//echo $key." ".$this->$key."<br />";
				}
			}
			$this->ajax= true;	
		}
		if ($this->mobile) {
			$this->full_tag_open= "<div style=\"color:#999;padding:0px;font-size:x-small;\">";
			$this->full_tag_close= '</div>';
			$this->first_tag_open= " ";
			$this->first_link= "first";
			$this->first_tag_close= " | ";
			$this->last_tag_open= " | ";
			$this->last_link= "last";
			$this->last_tag_close= " ";
			$this->prev_tag_open= " ";
			$this->prev_link= "prev";
			$this->prev_tag_close= " ";
			$this->next_tag_open= " | ";
			$this->next_link= "next";
			$this->next_tag_close= " ";
			$this->cur_tag_open= " | ";
			$this->cur_tag_close= " ";
			$this->num_tag_open= " | ";
			$this->num_tag_close= " ";	
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */	
	function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		//echo $this->per_page."<br />";
		//echo $this->total_rows;
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
		   return '';
		}
		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{	
			return '';
		}
		// Determine the current page number.		
		$CI =& get_instance();
		if ($CI->uri->segment($this->uri_segment) != 0)
		{
			if ($this->cur_page==0)
				$this->cur_page= $CI->uri->segment($this->uri_segment);
			
			// Prep the current page - no funny business!
			$this->cur_page = (int) $this->cur_page;
		}

		$this->num_links = (int)$this->num_links;		
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
				
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 0;
		}
		
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}
		
		$uri_page_number = $this->cur_page;
		$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Add a trailing slash to the base URL if needed
		$this->base_url = rtrim($this->base_url, '/') .'/';

  		// And here we go...
		$output = '';
		// Render the "First" link
		if  ($this->cur_page > $this->num_links)
		{
			if ($this->ajax)
				$output .= $this->first_tag_open.'<a href="javascript:etalase.loadpage(\''.$this->container.'\' ,  \''.$this->base_url.'page/0/ajax/true/\')">'.$this->first_link.'</a>'.$this->first_tag_close;
			else
				$output .= $this->first_tag_open.'<a href="'.$this->base_url.'page/0">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->cur_page != 1)
		{
			$i = $uri_page_number - $this->per_page;
			if ($i == 0) $i = 0;
			if ($this->ajax)
				$output .= $this->prev_tag_open.'<a href="javascript:etalase.loadpage(\''.$this->container.'\' , \''.$this->base_url.'page/'.$i.'/ajax/true/\')">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			else
				$output .= $this->prev_tag_open.'<a href="'.$this->base_url.'page/'.$i.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
				//echo "\n".$this->prev_link."<br />".$output."\n" ;
		}
		
		// Write the digit links
		for ($loop = $start -1; $loop <= $end; $loop++)
		{
			$i = ($loop * $this->per_page) - $this->per_page;
					
			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
				}
				else
				{
					$n = ($i == 0) ? $i: $i;
					if ($this->ajax)		
						$output .= $this->num_tag_open.'<a href="javascript:etalase.loadpage(\''.$this->container.'\' , \''.$this->base_url.'page/'.$n.'/ajax/true/\')">'.$loop.'</a>'.$this->num_tag_close;
					else
						$output .= $this->num_tag_open.'<a href="'.$this->base_url.'page/'.$n.'">'.$loop.'</a>'.$this->num_tag_close;
				}
			}
		}
		
		// Render the "next" link
		if ($this->cur_page < $num_pages)
		{
			if ($this->ajax)
				$output .= $this->next_tag_open.'<a href="javascript:etalase.loadpage(\''.$this->container.'\' , \''.$this->base_url.'page/'.($this->cur_page * $this->per_page).'/ajax/true/\')">'.$this->next_link.'</a>'.$this->next_tag_close;
			else
				$output .= $this->next_tag_open.'<a href="'.$this->base_url.'page/'.($this->cur_page * $this->per_page).'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if (($this->cur_page + $this->num_links) < $num_pages)
		{
			$i = (($num_pages * $this->per_page) - $this->per_page);
			if ($this->ajax)
				$output .= $this->last_tag_open.'<a href="javascript:etalase.loadpage(\''.$this->container.'\' , \''.$this->base_url.'page/'.$i.'/ajax/true/\')">'.$this->last_link.'</a>'.$this->last_tag_close;
			else
				$output .= $this->last_tag_open.'<a href="'.$this->base_url.'page/'.$i.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}
		
		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;		
		return $output;		
	}
}
// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */