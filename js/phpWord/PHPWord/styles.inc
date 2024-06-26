<?php

// Example styles
// This is an example of how a "style sheet" should be structured
// to asign PHPWord styles to HTML elements, classes, and inline
// styles.
function htmltodocx_styles_example()
{
    // Set of default styles -
    // to set initially whatever the element is:
    // NB - any defaults not set here will be provided by PHPWord.
    $styles['default'] =
        array(
            'size' => 11,
        );
    // Element styles:
    // The keys of the elements array are valid HTML tags;
    // The arrays associated with each of these tags is a set
    // of PHPWord style definitions.
    $styles['elements'] =
        array(
            'h1'     => array(
                'bold' => TRUE,
                'size' => 400,
            ),
            'h2'     => array(
                'bold'       => TRUE,
                'size'       => 28,
                'spaceAfter' => 400,
            ),
            'h3'     => array(
                'size'       => 16,
                'bold'       => TRUE,
                'spaceAfter' => 400,
            ),
            'li'     => array(),
            'ol'     => array(
                'spaceBefore' => 200,
            ),
            'ul'     => array(
                'spaceAfter' => 150,
            ),
            'b'      => array(
                'bold' => TRUE,
            ),
            'em'     => array(
                'italic' => TRUE,
            ),
            'i'      => array(
                'italic' => TRUE,
            ),
            'strong' => array(
                'bold' => TRUE,
            ),
            'b'      => array(
                'bold' => TRUE,
            ),
        	
            'sup'    => array(
                'superScript' => TRUE,
                'size'        => 6,
            ), // Superscript not working in PHPWord
            'u'      => array(
                'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
            ),
            'a'      => array(
                'color'     => '0000FF',
                'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
            ),
            'table'  => array(
                // Note that applying a table style in PHPWord applies the relevant style to
                // ALL the cells in the table. So, for example, the borderSize applied here
                // applies to all the cells, and not just to the outer edges of the table:
                'borderColor' => '000000',
                'borderSize'  => 10,
                'width' => 100 * 100,
            	
            	
            ),
            'th'     => array(
            	//'borderColor' => '000000',
               // 'borderSize'  => 10,
            	'width' => 100 * 100,
            		
            	
            ),
            'td'     => array(
            	// 'borderColor' => '000000',
                //'borderSize'  => 10,
            	'width' => 100 * 100,
            	
            ),
        	
        	
        );
        
    // Classes:
    // The keys of the classes array are valid CSS classes;
    // The array associated with each of these classes is a set
    // of PHPWord style definitions.
    // Classes will be applied in the order that they appear here if
    // more than one class appears on an element.
    $styles['classes'] =
        array(
            'underline' => array(
                'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
            ),
            'purple'    => array(
                'color' => '901391',
            ),
            'green'     => array(
                'color' => '00A500',
            ),		
        );
    // Inline style definitions, of the form:
    // array(css attribute-value - separated by a colon and a single space => array of
    // PHPWord attribute value pairs.
    $styles['inline'] =
        array(
            'text-decoration: underline' => array(
                'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
            ),
            'text-decoration: line-through' => array(
                'strikethrough' => true,
            ),
            'vertical-align: left'       => array(
                'align' => 'left',
            ),
            'vertical-align: middle'     => array(
                'align' => 'center',
            ),
            'vertical-align: right'      => array(
                'align' => 'right',
            ),
            'text-align: center'      => array(
                'align' => 'center',
            ),
            'text-align: left'      => array(
                'align' => 'left',
            ),
            'text-align: right'      => array(
                'align' => 'right',
            ),
        	'text-align: justify'      => array(
                'align'=>'both',
                'spaceAfter'=>100
            ),
        	        	
        );
    return $styles;
}