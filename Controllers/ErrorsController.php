<?php
class ErrorsController extends Controller
{
    public function Error404()
    {   
        http_response_code(404);
        $this->View("404");
    }
}
?>