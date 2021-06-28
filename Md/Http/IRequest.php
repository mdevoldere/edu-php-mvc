<?php 

namespace Md\Http; 

/**
 * Http/Request represents the current HTTP Query
 * 
 * @method string getMethod()
 * @method string getRoute()
 * @method array getData()
 * @method string getController()
 * @method string getAction()
 * @method null|string getId()
 * @method string getView()
 * @method string getLocalPath()
 */
interface IRequest 
{
    /**
     * Get current Request method
     * @return string the current request method
     */
    public function getMethod(): string;

    /**
     * Get current Request HTTP route ({Controller}/{Action}/{?Id})
     * @return string the current request route
     */
    public function getRoute(): string;

    /**
     * Get current Request HTTP data (get params, post form etc...) 
     * @return array the current request data
     */
    public function getData(): array;

    /**
     * Get current Request param (1st part) identified as Controller
     * @return string the Controller to execute
     */
    public function getController(): string;

    /**
     * Get current Request param (2nd part) identified as Action to execute inside Controller
     * @return string the method to invoke inside Controller
     */
    public function getAction(): string;

    /**
     * Get current Request param (3rd part) identified as Id provided to Action
     * @return string the value provided to the method invoked in Controller
     */
    public function getId(): ?string;

    /**
     * Get View relative path
     * ex: 
     * - request is /users/profile/23
     * - view = users/profile
     * @return string the view relative path
     */
    public function getView(): string;

    /**
     * Get App absolute path
     * @param null|string $_subpath
     * @return string the app absolute path
     */
    public function getLocalPath(?string $_subpath = null): string;
}