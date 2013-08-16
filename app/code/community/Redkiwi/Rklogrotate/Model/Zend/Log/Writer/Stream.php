<?php

class Redkiwi_Rklogrotate_Model_Zend_Log_Writer_Stream extends Zend_Log_Writer_Stream
{

    /**
     * Class Constructor
     *
     * @param  streamOrUrl     Stream or URL to open as a stream
     * @param  mode            Mode, only applicable if a URL is given
     */
    public function __construct($streamOrUrl, $mode = NULL)
    {
        // Setting the default
        if ($mode === NULL) {
            $mode = 'a';
        }

        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                #require_once 'Zend/Log/Exception.php';
                throw new Zend_Log_Exception('Resource is not a stream');
            }

            if ($mode != 'a') {
                #require_once 'Zend/Log/Exception.php';
                throw new Zend_Log_Exception('Mode cannot be changed on existing streams');
            }

            $this->_stream = $streamOrUrl;
        } else {
            if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
                $streamOrUrl = $streamOrUrl['stream'];
            }
			
			$pathinfo 		= pathinfo($streamOrUrl);
			$dateformat 	= Mage::getStoreConfig('system/log/logrotate_format');
			$streamOrUrl 	= $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] . '_' . date($dateformat) . '.' . $pathinfo['extension'];
			
			if (! $this->_stream = @fopen($streamOrUrl, $mode, false)) {
                #require_once 'Zend/Log/Exception.php';
                $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
                throw new Zend_Log_Exception($msg);
            }
        }

        $this->_formatter = new Zend_Log_Formatter_Simple();
    }
}
