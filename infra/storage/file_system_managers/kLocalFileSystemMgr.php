<?php
/**
 * Created by IntelliJ IDEA.
 * User: yossi.papiashvili
 * Date: 5/26/19
 * Time: 4:19 PM
 */

require_once(dirname(__FILE__) . '/kFileSystemMgr.php');

class kLocalFileSystemMgr extends kFileSystemMgr
{
	// instances of this class should be created usign the 'getInstance' of the 'kFileTransferMgr' class
	protected function __construct(array $options = null)
	{
		parent::__construct($options);
	}
	
	protected function doCreateDirForPath($filePath)
	{
		$dirname = dirname($filePath);
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0777, true);
		}
	}
	
	protected function doCheckFileExists($filePath)
	{
		return file_exists($filePath);
	}
	
	protected function doGetFile($filePath)
	{
		return @file_get_contents($filePath);
	}
	
	protected function doUnlink($filePath)
	{
		return @unlink($filePath);
	}
	
	protected function doPutFileAtomic($filePath, $fileContent)
	{
		// write to a temp file and then rename, so that the write will be atomic
		$tempFilePath = tempnam(dirname($filePath), basename($filePath));
		
		if(!$this->doPutFile($tempFilePath, $fileContent))
			return false;
		
		if(!$this->doRename($tempFilePath, $filePath))
		{
			$this->doUnlink($tempFilePath);
			return false;
		}
		
		return true;
	}
	
	protected function doPutFile($filePath, $fileContent)
	{
		return file_put_contents($filePath, $fileContent);
	}
	
	protected function doRename($filePath, $newFilePath)
	{
		return rename($filePath, $newFilePath);
	}
	
	protected function doCopy($fromFilePath, $toFilePath)
	{
		return copy($fromFilePath, $toFilePath);
	}
	
	protected function doGetFileFromRemoteUrl($url, $destFilePath = null)
	{
		$curlWrapper = new KCurlWrapper();
		$res = $curlWrapper->exec($url, $destFilePath, null, $allowInternalUrl);
		
		$httpCode = $curlWrapper->getHttpCode();
		if (KCurlHeaderResponse::isError($httpCode))
		{
			KalturaLog::info("curl request [$url] return with http-code of [$httpCode]");
			if ($destFilePath && file_exists($destFilePath))
				unlink($destFilePath);
			$res = false;
		}
		
		$curlWrapper->close();
		return $res;
	}
}