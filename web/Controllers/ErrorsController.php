<?php
class ErrorsController extends Controller
{
    function Error404() : void
    {   
        http_response_code(404);
        $this->View("404");
    }
}
?>