<?php

namespace Av\HTTP;

class Response
{
    /** @var integer */
    private $status_code;
    /** @var string */
    private $message;
    /** @var array */
    private $headers = [];

    /**
     * @param string $key
     * @param string $value
     * @return Response
     */
    public function setContentType(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param $status_code
     * @return $this
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
        return $this;
    }

    /**
     * @param string $content
     * @param $options
     */
    public function setContent(string $content, $options)
    {
        if (isset($options['headers'])) {
            $this->headers = $options['headers'];
        }
        $this->message = $content;
    }

    /**
     * @param array $content
     * @param $status_code
     * @return Response
     */
    public function setJsonContent(array $content, $status_code): self
    {
        $this->headers['Content-type'] = 'application/json';
        $this->status_code = $status_code;
        $this->message = \json_encode($content);
        return $this;
    }

    /**
     * @todo implement send
     */
    public function send(): void
    {

    }
}