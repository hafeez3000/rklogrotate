<?php

class Redkiwi_Rklogrotate_Model_Cron extends Mage_Core_Model_Abstract
{

    /**
     * Clean logs
     *
     * @return Mage_Log_Model_Cron
     */
    public function logClean()
    {
        $keep_seconds 	= (int)Mage::getStoreConfig('system/log/logrotate_keep') * 24 * 3600;
		$dump_stamp		= time() - $keep_seconds;
		
		$logs = $this->_rglob(Mage::getBaseDir('var') . DIRECTORY_SEPARATOR . '*.log');
		foreach ($logs as $log)
		{
			if (filemtime($log) < $dump_stamp) unlink($log);
		}
		
        return $this;
    }
	
	
	// source: http://thephpeffect.com/recursive-glob-vs-recursive-directory-iterator/
	protected function _rglob($pattern, $flags = 0) {
		$files = glob($pattern, $flags); 
		foreach (glob(dirname($pattern) . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
			$files = array_merge($files, $this->_rglob($dir . DIRECTORY_SEPARATOR . basename($pattern), $flags));
		
		return $files;
	}
	 
}
