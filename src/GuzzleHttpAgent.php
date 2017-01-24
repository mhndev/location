<?php
namespace mhndev\location;

use GuzzleHttp\Client;
use mhndev\location\Interfaces\iHttpAgent;
use Psr\Http\Message\RequestInterface;

class GuzzleHttpAgent implements iHttpAgent
{

    /**
     * @var Client
     */
    private $bridge;


    /**
     * GuzzleHttpAgent constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->bridge = new Client($options);
    }


    /**
     * @param $options
     * @param $headers
     * @return array|null
     */
    protected function setHeaders(&$options, $headers)
    {
        if($headers){
            $options['headers'] = $headers;
        }

    }

    /**
     * Send Http Request Message
     *
     * @param RequestInterface $request
     * @param null $options
     *
     * @return mixed
     */
    function request(RequestInterface $request, $options = null)
    {
        return $this->bridge->send($request, $options);
    }

    /**
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function GET($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);
        if(is_null($options)){
            $options= [];
        }

        return $this->bridge->request('GET', $uri, $options);
    }

    /**
     * note: For retrieving meta-information written in response headers,
     *       without having to transport the entire content(body).
     *
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function HEAD($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('HEAD', $uri, $options);

    }

    /**
     * ! post request should always has Content-Length Header if has body
     *   with value equals to body size
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.4
     *
     * @param $uri
     * @param null $options
     * @param null $body
     * @param null $headers
     * @return mixed
     */
    function POST($uri, $options = null, $body = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('POST', $uri, $options);
    }

    /**
     * note: PUT puts a file or resource at a specific URI, and exactly at that URI.
     *       If there's already a file or resource at that URI, PUT replaces that
     *       file or resource. If there is no file or resource there, PUT creates one.
     *
     * @param $uri
     * @param null $options
     * @param null $body
     * @param null $headers
     * @return mixed
     */
    function PUT($uri, $options = null, $body = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('PUT', $uri, $options);
    }

    /**
     * note: Used to update partial resources.
     *       For instance, when you only need to update one field of the resource,
     *       PUTting a complete resource representation might be cumbersome and
     *       utilizes more bandwidth.
     *
     * @param $uri
     * @param null $options
     * @param null $body
     * @param null $headers
     * @return mixed
     */
    function PATCH($uri, $options = null, $body = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('PATCH', $uri, $options);
    }

    /**
     * note: Removes all current representations of the target resource given by a URI
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function DELETE($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('DELETE', $uri, $options);
    }

    /**
     * note: Allows the client to determine the options and/or requirements
     *       associated with a resource, or the capabilities of a server, without
     *       implying a resource action or initiating a resource retrieval.
     *
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function OPTIONS($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('OPTIONS', $uri, $options);
    }

    /**
     * note: used by the client to establish a network connection to
     *       a web server over HTTP
     *
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function CONNECT($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('CONNECT', $uri, $options);
    }

    /**
     *
     * note: used to echo the contents of an HTTP Request back to the requester
     *       which can be used for debugging purpose at the time of development
     *
     * @param $uri
     * @param null $options
     * @param null $headers
     * @return mixed
     */
    function TRACE($uri, $options = null, $headers = null)
    {
        $this->setHeaders($options, $headers);

        return $this->bridge->request('TRACE', $uri, $options);
    }

    /**
     * exp.
     *
     *   http://site-name.com/
     *   http://site-name.com/basepath/
     *
     * @param string $baseUrl
     * @return $this
     */
    function setBaseUrl($baseUrl)
    {

    }

    /**
     * @return string|null
     */
    function getBaseUrl()
    {
        // TODO: Implement getBaseUrl() method.
    }
}
