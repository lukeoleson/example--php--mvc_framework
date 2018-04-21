<?php

/*
    App core class
    Creates URL and loads core controller
    URL FORMAT - /controller/method/params

    // NOTE: I think that I need to check to make sure that there actually is a parameter passed to the url here, i.e. .../about/33 not just .../about, or else I think I will have either index out of bounds or just to few arguments
*/

class Core {
    // The currentController and currentMethod will change
    // as the URL changes
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    // Constructor
    public function __construct(){
        // print_r($this->getUrl());
        $url = $this->getUrl();

        // look in controllers for the first value in the array, which should be our controller
        if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // if this controller exists, set it as the current controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 index
            unset($url[0]);
        }

        // now require the controller ...
        require_once '../app/controllers/' . $this->currentController . '.php';
        // .. and instantiate the controller class
        $this->currentController = new $this->currentController;

        // check for second part of url
        if(isset($url[1])){
            // check to see if method exists in controller
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }

        // get params
        $this->params = $url ? array_values($url) : [];

        // NOTE: I think that I need to check to make sure that there actually is a parameter passed to the url here, i.e. .../about/33 not just .../about, or else I think I will have either index out of bounds or just to few arguments
        // call a callback with an array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    public function getUrl(){
        if(isset($_GET['url'])){
            // strip the trailing /
            $url = rtrim($_GET['url'], '/');
            // sanitize as a url (it now should not have any chars that a URL should not have)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // breaks the string up into an array
            $url = explode('/', $url);
            return $url;
        }
    }
}
