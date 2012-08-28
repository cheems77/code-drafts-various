<?php

/**
 * Controller class as part of MVC framework
 *
 * Used process and generate views for an error 404 page
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
**/
Class error404Controller Extends baseController {

public function index() 
{
        $this->registry->template->page_title = 'File Not Found';
        $this->registry->template->show('error404');
}


}
?>
