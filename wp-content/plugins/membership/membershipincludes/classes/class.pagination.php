<?php

class M_Pagination {

    var $total_pages = -1; //items
    var $limit = 10;
    var $items_title = 'items';
    var $target = "";
    var $page = 1;
    var $adjacents = 2;
    var $showCounter = false;
    var $className = "pagination-links";
    var $parameterName = "page";
    var $urlF = false; //urlFriendly

    /* Buttons next and previous */
    var $nextT = "Next";
    var $nextI = "&#187;"; //&#9658;
    var $prevT = "Previous";
    var $prevI = "&#171;"; //&#9668;
    var $calculate = false;

    #Total items

    function items($value) {
        $this->total_pages = (int) $value;
    }

    #how many items to show per page

    function limit($value) {
        $this->limit = (int) $value;
    }

    #Page to sent the page value

    function target($value) {
        $this->target = $value;
    }

    #Current page

    function currentPage($value) {
        $this->page = (int) $value;
    }

    #How many adjacent pages should be shown on each side of the current page?

    function adjacents($value) {
        $this->adjacents = (int) $value;
    }

    #show counter?

    function showCounter($value = "") {
        $this->showCounter = ($value === true) ? true : false;
    }

    #to change the class name of the pagination div

    function changeClass($value = "") {
        $this->className = $value;
    }

    function nextLabel($value) {
        $this->nextT = $value;
    }

    function nextIcon($value) {
        $this->nextI = $value;
    }

    function prevLabel($value) {
        $this->prevT = $value;
    }

    function prevIcon($value) {
        $this->prevI = $value;
    }

    #to change the class name of the pagination div

    function parameterName($value = "") {
        $this->parameterName = $value;
    }

    #to change urlFriendly

    function urlFriendly($value = "%") {
        if (eregi('^ *$', $value)) {
            $this->urlF = false;
            return false;
        }
        $this->urlF = $value;
    }

    var $pagination;

    function pagination() {

    }

    function show() {
        if ( !$this->calculate ) {
			if ( $this->calculate() ) {
				echo '<span class="displaying-num">', $this->total_pages, " ", $this->items_title, "</span><div class=\"tablenav-pages\"><span class=\"$this->className\">$this->pagination</span></div>\n";
			}
		}
    }

    function getOutput() {
        if (!$this->calculate)
            if ($this->calculate())
                return "<span class=\"$this->className\">$this->pagination</span>\n";
    }

	function calculate() {
        $this->pagination = "";
		$this->calculate = true;
		$error = false;
		if ( $this->urlF and $this->urlF != '%' and strpos( $this->target, $this->urlF ) === false ) {
			//_e("You have specified one wildcard to replace, but does it does not exist in the target", 'cp');
			$error = true;
		} elseif ( $this->urlF and $this->urlF == '%' and strpos( $this->target, $this->urlF ) === false ) {
			//_e("You must specify the wildcard% target to replace the page number", 'cp');
			$error = true;
		}

		if ( $this->total_pages < 0 ) {
			//echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
			$error = true;
		}
		if ( $this->limit == null ) {
			//echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
			$error = true;
		}
		if ( $error ) {
			return false;
		}

		/* Setup page vars for display. */
        $prev = $this->page - 1;                            //previous page is page - 1
        $next = $this->page + 1;                            //next page is page + 1
        $lastpage = ceil($this->total_pages / $this->limit);        //lastpage is = total pages / items per page, rounded up.

        if ($lastpage > 1) {
            if ($this->page) {
                //anterior button
				$this->pagination .= $this->page > 1
					? "<a href=\"" . add_query_arg( 'page_num', 1 ) . "\" class=\"first-page\">&laquo;</a>&nbsp;<a href=\"" . add_query_arg( 'page_num', $prev ) . "\" class=\"prev-page\">&lsaquo;</a>&nbsp;"
					: "<a class=\"first-page disabled\">&laquo;</a>&nbsp;<a class=\"prev-page disabled\">&lsaquo;</a>&nbsp;";
            }

            $this->pagination .= '&nbsp;<span class="paging-input">' . $this->page . ' of <span class="total-pages">' . $lastpage . '</span></span>&nbsp;';

            if ($this->page) {
                if ($this->page < $lastpage) {
                    $this->pagination .= "&nbsp;<a href=\"" . add_query_arg( 'page_num', $next ) . "\" class=\"next-page\">&rsaquo;</a>&nbsp;<a href=\"" . add_query_arg( 'page_num', $lastpage ) . "\" class=\"last-page\">&raquo;</a>";
				} else {
                    $this->pagination .= "&nbsp;<a class=\"next-page disabled\">&rsaquo;</a>&nbsp;<a class=\"last-page disabled\">&raquo;</a>";
				}

                if ($this->showCounter) {
                    $this->pagination .= "<div class=\"pagination_data\">($this->total_pages Pages)</div>";
				}
            }
        }

        return true;
    }

}