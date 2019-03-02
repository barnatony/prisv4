<?php
class pagination {
	public static function makePagination($n,$total_rows,$base_url,$paramStr='',&$config=null) {
		//defaults
		$c['per_page']         = 4;
		$c['num_links']        = 2;
		$c['first_link']       = '<<';
		$c['next_link']        = '&gt;';
		$c['prev_link']        = '&lt;';
		$c['last_link']        = '>>';
		$c['full_tag_open']    = '<nav style="text-align: right;"><ul class="pagination pagination-lg">';
		$c['full_tag_close']   = '</ul></nav><br/><br />';
		$c['first_tag_open']   = '<li>';
		$c['first_tag_close']  = '</li>&nbsp;';
		$c['last_tag_open']    = '&nbsp;<li>';
		$c['last_tag_close']   = '</li>';
		$c['cur_tag_open']     = '<li class="active"><a href="javascript:void(0);">&nbsp';
		$c['cur_tag_close']    = '</a></li>';
		$c['next_tag_open']    = '&nbsp;<li>';
		$c['next_tag_close']   = '&nbsp;</li>';
		$c['prev_tag_open']    = '&nbsp;<li>';
		$c['prev_tag_close']   = '</li>';
		$c['num_tag_open']     = '&nbsp;<li>';
		$c['num_tag_close']     = '</li>';
		
		

		//initialize
		if (is_array($config))
			foreach ($config as $k => $v)
				if (isset($c[$k]))
					$c[$k] = $v;
					$per_page=$c['per_page'];
					$num_links=$c['num_links'];

					if (!$total_rows or !$per_page or $n > $total_rows)
						return '';

						$num_pages = ceil($total_rows / $per_page);
						
						if ($num_pages == 1)
							return '';

							$num_links = max(1,(int)$num_links);
							$n = max(0,(int)$n);

							// Is the page number beyond the result range?
							// If so we show the last page
							if ($n > $total_rows)
								$n = ($num_pages - 1) * $per_page;

								$uri_page_number = $n;
								$cur_page = floor(($n / $per_page) + 1);

								// Calculate the start and end numbers. These determine
								// which number to start and end the digit links with
								$start = (($cur_page - $num_links) > 0) ? $cur_page - ($num_links - 1) : 1;
								$end   = (($cur_page + $num_links) < $num_pages) ? $cur_page + $num_links : $num_pages;

								// Sanitize the trailing slash
								$base_url = rtrim($base_url, ' /') .'/';
								if($paramStr!='')
									$paramStr = '/'.$paramStr;
								$output = '';

								// Render the "First" link
								if  ($cur_page > $num_links)
									$output .= $c['first_tag_open'].'<a href="'.$base_url.'0"'.$paramStr.'>'.$c['first_link'].'</a>'.$c['first_tag_close'];
									
									// Render the "previous" link
									if  ($cur_page != 1) {
										$i = $uri_page_number - $per_page;
										$output .= $c['prev_tag_open'].'<a href="'.$base_url.$i.$paramStr.'">'.$c['prev_link'].'</a>'.$c['prev_tag_close'];
										
									}

									// Write the digit links
									for ($loop = $start -1; $loop <= $end; $loop++) {
										$i = ($loop * $per_page) - $per_page;
										if ($i >= 0) {
											if ($cur_page == $loop)
												$output .= $c['cur_tag_open'].$loop.$c['cur_tag_close']; // Current page
												else
													$output .= $c['num_tag_open'].'<a href="'.$base_url.$i.$paramStr.'">'.$loop.'</a>'.$c['num_tag_close'];
										}
									}

									// Render the "next" link
									if ($cur_page < $num_pages)
										$output .= $c['next_tag_open'].'<a href="'.$base_url.($cur_page * $per_page).$paramStr.'">'.$c['next_link'].'</a>'.$c['next_tag_close'];

										// Render the "Last" link
										if (($cur_page + $num_links) < $num_pages) {
											$i = (($num_pages * $per_page) - $per_page);
											$output .= $c['last_tag_open'].'<a href="'.$base_url.$paramStr.$i.'">'.$c['last_link'].'</a>'.$c['last_tag_close'];
										}

										$output = $c['full_tag_open'].$output.$c['full_tag_close'];
										return $output;
	}
}
