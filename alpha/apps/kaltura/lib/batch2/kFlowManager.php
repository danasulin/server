<?php

/**
 *
 * Manages the batch flow
 *
 * @package Core
 * @subpackage Batch
 *
 */
class kFlowManager implements kBatchJobStatusEventConsumer, kObjectAddedEventConsumer, kObjectChangedEventConsumer, kObjectDeletedEventConsumer, kObjectReadyForReplacmentEventConsumer,kObjectDataChangedEventConsumer
{
	public final function __construct()
	{
	}

	protected function updatedImport(BatchJob $dbBatchJob, kImportJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleImportFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_RETRY:
				return kFlowHelper::handleImportRetried($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleImportFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedConcat(BatchJob $dbBatchJob, kConcatJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleConcatFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleConcatFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedConvertLiveSegment(BatchJob $dbBatchJob, kConvertLiveSegmentJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleConvertLiveSegmentFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleConvertLiveSegmentFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedIndex(BatchJob $dbBatchJob, kIndexJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PENDING:
				return kFlowHelper::handleIndexPending($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleIndexFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleIndexFailed($dbBatchJob, $data);
				return $dbBatchJob;
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedCopy(BatchJob $dbBatchJob, kCopyJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
//				return kFlowHelper::handleCopyFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
//				return kFlowHelper::handleCopyFailed($dbBatchJob, $data);
				return $dbBatchJob;
			default:
				return $dbBatchJob;
		}
	}
	
	protected function updatedDelete(BatchJob $dbBatchJob, kDeleteJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				//				return kFlowHelper::handleDeleteFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				//				return kFlowHelper::handleDeleteFailed($dbBatchJob, $data);
				return $dbBatchJob;
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedExtractMedia(BatchJob $dbBatchJob, kExtractMediaJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleExtractMediaClosed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedMoveCategoryEntries(BatchJob $dbBatchJob, kMoveCategoryEntriesJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
//				return kFlowHelper::handleMoveCategoryEntriesFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
//				return kFlowHelper::handleMoveCategoryEntriesFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedStorageExport(BatchJob $dbBatchJob, kStorageExportJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleStorageExportFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleStorageExportFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedStorageDelete(BatchJob $dbBatchJob, kStorageDeleteJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleStorageDeleteFinished($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedCaptureThumb(BatchJob $dbBatchJob, kCaptureThumbJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleCaptureThumbFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleCaptureThumbFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}
	
	protected function updatedDeleteFile (BatchJob $dbBatchJob, kDeleteFileJobData $data)
	{
		switch ($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PROCESSING:
				kFlowHelper::handleDeleteFileProcessing($data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleDeleteFileFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
			default:
				return $dbBatchJob;
		}	
	}

	protected function updatedConvert(BatchJob $dbBatchJob, kConvertJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PENDING:
				return kFlowHelper::handleConvertPending($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_QUEUED:
				return kFlowHelper::handleConvertQueued($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleConvertFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleConvertFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedPostConvert(BatchJob $dbBatchJob, kPostConvertJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handlePostConvertFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handlePostConvertFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedBulkUpload(BatchJob $dbBatchJob, kBulkUploadJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FAILED: 
			case BatchJob::BATCHJOB_STATUS_FATAL: 
				return kFlowHelper::handleBulkUploadFailed($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED: 
				return kFlowHelper::handleBulkUploadFinished($dbBatchJob, $data);
			default: return $dbBatchJob;
		}
	}

	protected function updatedConvertCollection(BatchJob $dbBatchJob, kConvertCollectionJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PENDING:
				return kFlowHelper::handleConvertCollectionPending($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleConvertCollectionFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleConvertCollectionFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedConvertProfile(BatchJob $dbBatchJob, kConvertProfileJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PENDING:
				return kFlowHelper::handleConvertProfilePending($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleConvertProfileFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleConvertProfileFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedBulkDownload(BatchJob $dbBatchJob, kBulkDownloadJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_PENDING:
				return kFlowHelper::handleBulkDownloadPending($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleBulkDownloadFinished($dbBatchJob, $data);
			//Bulk download has now worker so there is no point to retry it.
			case BatchJob::BATCHJOB_STATUS_RETRY:
				return kFlowHelper::handleBulkDownloadRetried($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedProvisionDelete(BatchJob $dbBatchJob, kProvisionJobData $data)
	{
		return $dbBatchJob;
	}

	protected function updatedProvisionProvide(BatchJob $dbBatchJob, kProvisionJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleProvisionProvideFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleProvisionProvideFailed($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}
	
	protected function updatedLiveReportExport(BatchJob $dbBatchJob, kLiveReportExportJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleLiveReportExportFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleLiveReportExportFailed($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_ABORTED:
				return kFlowHelper::handleLiveReportExportAborted($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	protected function updatedReportExport(BatchJob $dbBatchJob, kReportExportJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleReportExportFinished($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_FAILED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
				return kFlowHelper::handleReportExportFailed($dbBatchJob, $data);
			case BatchJob::BATCHJOB_STATUS_ABORTED:
				return kFlowHelper::handleReportExportAborted($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::shouldConsumeJobStatusEvent()
	 */
	public function shouldConsumeJobStatusEvent(BatchJob $dbBatchJob)
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::updatedJob()
	 */
	public function updatedJob(BatchJob $dbBatchJob)
	{ 
		$dbBatchJobLock = $dbBatchJob->getBatchJobLock();
		
		try
		{
			if($dbBatchJob->getStatus() == BatchJob::BATCHJOB_STATUS_FAILED || $dbBatchJob->getStatus() == BatchJob::BATCHJOB_STATUS_FATAL)	{
				kJobsManager::abortChildJobs($dbBatchJob);
			}
			
			$jobType = $dbBatchJob->getJobType();
			switch($jobType)
			{
				case BatchJobType::IMPORT:
					$dbBatchJob = $this->updatedImport($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::EXTRACT_MEDIA:
					$dbBatchJob = $this->updatedExtractMedia($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::CONVERT:
					$dbBatchJob = $this->updatedConvert($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::POSTCONVERT:
					$dbBatchJob = $this->updatedPostConvert($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::BULKUPLOAD:
					$dbBatchJob = $this->updatedBulkUpload($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::CONVERT_PROFILE:
					$dbBatchJob = $this->updatedConvertProfile($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::BULKDOWNLOAD:
					$dbBatchJob = $this->updatedBulkDownload($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::PROVISION_PROVIDE:
					$dbBatchJob = $this->updatedProvisionProvide($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::PROVISION_DELETE:
					$dbBatchJob = $this->updatedProvisionDelete($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::CONVERT_COLLECTION:
					$dbBatchJob = $this->updatedConvertCollection($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::STORAGE_EXPORT:
					$dbBatchJob = $this->updatedStorageExport($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::MOVE_CATEGORY_ENTRIES:
					$dbBatchJob = $this->updatedMoveCategoryEntries($dbBatchJob, $dbBatchJob->getData());
					break;
							
				case BatchJobType::STORAGE_DELETE:
					$dbBatchJob = $this->updatedStorageDelete($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::CAPTURE_THUMB:
					$dbBatchJob = $this->updatedCaptureThumb($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::DELETE_FILE:
					$dbBatchJob=$this->updatedDeleteFile($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::INDEX:
					$dbBatchJob=$this->updatedIndex($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::COPY:
					$dbBatchJob=$this->updatedCopy($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::DELETE:
					$dbBatchJob=$this->updatedDelete($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::CONCAT:
					$dbBatchJob=$this->updatedConcat($dbBatchJob, $dbBatchJob->getData());
					break;
					
				case BatchJobType::CONVERT_LIVE_SEGMENT:
					$dbBatchJob=$this->updatedConvertLiveSegment($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::LIVE_REPORT_EXPORT:
					$dbBatchJob=$this->updatedLiveReportExport($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::EXPORT_CSV:
					$dbBatchJob = $this->updatedExportCsv($dbBatchJob, $dbBatchJob->getData());
					break;

				case BatchJobType::REPORT_EXPORT:
					$dbBatchJob = $this->updatedReportExport($dbBatchJob, $dbBatchJob->getData());
					break;

				default:
					break;
			}
			
			if($dbBatchJob->getStatus() == BatchJob::BATCHJOB_STATUS_RETRY) {
				
				if($dbBatchJobLock && $dbBatchJobLock->getExecutionAttempts() >= BatchJobLockPeer::getMaxExecutionAttempts($jobType))
					$dbBatchJob = kJobsManager::updateBatchJob($dbBatchJob, BatchJob::BATCHJOB_STATUS_FAILED);
			}
			
			if(in_array($dbBatchJob->getStatus(), BatchJobPeer::getClosedStatusList()))
			{
				$jobEntry = $dbBatchJob->getEntry();
				if($jobEntry && $jobEntry->getMarkedForDeletion())
					myEntryUtils::deleteEntry($jobEntry,null,true);
			}
		}
		catch ( Exception $ex )
		{
			self::alert($dbBatchJob, $ex);
			KalturaLog::err( "Error:" . $ex->getMessage() );
		}
			
		return true;
	}

	// creates a mail job with the exception data
	protected static function alert(BatchJob $dbBatchJob, Exception $exception)
	{
		$jobData = new kMailJobData();
		$jobData->setMailPriority( kMailJobData::MAIL_PRIORITY_HIGH);
		$jobData->setStatus(kMailJobData::MAIL_STATUS_PENDING);

		KalturaLog::alert("Error in job [{$dbBatchJob->getId()}]\n".$exception);

		$jobData->setMailType(90); // is the email template
		$jobData->setBodyParamsArray(array($dbBatchJob->getId(), $exception->getFile(), $exception->getLine(), $exception->getMessage(), $exception->getTraceAsString()));

		$jobData->setFromEmail(kConf::get("batch_alert_email"));
		$jobData->setFromName(kConf::get("batch_alert_name"));
		$jobData->setRecipientEmail(kConf::get("batch_alert_email"));
		$jobData->setSubjectParamsArray( array() );

		kJobsManager::addJob($dbBatchJob->createChild(BatchJobType::MAIL, $jobData->getMailType()), $jobData, BatchJobType::MAIL, $jobData->getMailType());
	}

	/* (non-PHPdoc)
	 * @see kObjectAddedEventConsumer::shouldConsumeAddedEvent()
	 */
	public function shouldConsumeAddedEvent(BaseObject $object)
	{
		if($object instanceof asset)
			return true;

		return false;
	}

	/* (non-PHPdoc)
	 * @see kObjectAddedEventConsumer::objectAdded()
	 */
	public function objectAdded(BaseObject $object, BatchJob $raisedJob = null)
	{
		/** @var entry $entry */
		$entry = $object->getentry();

		if ($object->getStatus() == asset::FLAVOR_ASSET_STATUS_QUEUED || $object->getStatus() == asset::FLAVOR_ASSET_STATUS_IMPORTING)
		{
			if (!($object instanceof flavorAsset))
			{
				$object->setStatus(asset::FLAVOR_ASSET_STATUS_READY);
				$object->save();
			} elseif ($object->getIsOriginal())
			{
				if ($entry->getType() == entryType::MEDIA_CLIP)
				{
					if ($entry->getFlowType() == EntryFlowType::IMPORT_FOR_CLIP_CONCAT)
					{
						$object->setStatus(asset::FLAVOR_ASSET_STATUS_READY);
						$object->save();
						return true;
					}
					$allowedFlows = array(EntryFlowType::CLIP_CONCAT, EntryFlowType::TRIM_CONCAT);
					if ($entry->getOperationAttributes() && $object->getIsOriginal() && !in_array($entry->getFlowType(), $allowedFlows))
						kBusinessPreConvertDL::convertSource($object, null, null, $raisedJob);
					else
					{
						$syncKey = $object->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);

						if (kFileSyncUtils::fileSync_exists($syncKey))
						{
							list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($syncKey, true, false);
							kJobsManager::addConvertProfileJob($raisedJob, $entry, $object->getId(), $fileSync);
						}
					}

				}
			} else
			{
				$object->setStatus(asset::FLAVOR_ASSET_STATUS_VALIDATING);
				$object->save();
			}
		}

		if ($object->getStatus() == asset::FLAVOR_ASSET_STATUS_READY && $object instanceof thumbAsset)
		{
			if ($object->getFlavorParamsId())
				kFlowHelper::generateThumbnailsFromFlavor($object->getEntryId(), $raisedJob, $object->getFlavorParamsId());
			else
				if ($object->hasTag(thumbParams::TAG_DEFAULT_THUMB))
					kBusinessConvertDL::setAsDefaultThumbAsset($object);
			return true;
		}


		if ($object->getIsOriginal() && $entry->getStatus() == entryStatus::NO_CONTENT)
		{
			$entry->setStatus(entryStatus::PENDING);
			$entry->save();
		}

		return true;
	}

	/* (non-PHPdoc)
	 * @see kObjectChangedEventConsumer::shouldConsumeChangedEvent()
	 */
	public function shouldConsumeChangedEvent(BaseObject $object, array $modifiedColumns)
	{
		if(
			$object instanceof entry
			&&	in_array(entryPeer::STATUS, $modifiedColumns)
			&&	($object->getStatus() == entryStatus::READY || $object->getStatus() == entryStatus::ERROR_CONVERTING)
			&&	$object->getReplacedEntryId()
		)
			return true;

		if(
			$object instanceof UploadToken
			&&	in_array(UploadTokenPeer::STATUS, $modifiedColumns)
			&&	$object->getStatus() == UploadToken::UPLOAD_TOKEN_FULL_UPLOAD
		)
			return true;


		if(
			$object instanceof ClippingTaskEntryServerNode
			&&	in_array(EntryServerNodePeer::STATUS, $modifiedColumns)
		)
			return true;


		if(
			$object instanceof flavorAsset
			&&	in_array(assetPeer::STATUS, $modifiedColumns)
		)
			return true;
			
		if(
			$object instanceof BatchJob
			&&	$object->getJobType() == BatchJobType::BULKUPLOAD
			&&	$object->getStatus() == BatchJob::BATCHJOB_STATUS_ABORTED
			&&	in_array(BatchJobPeer::STATUS, $modifiedColumns)
			&&	in_array($object->getColumnsOldValue(BatchJobPeer::STATUS), BatchJobPeer::getClosedStatusList())
		)
			return true;
			
			
		if ($object instanceof UserRole
			&& in_array(UserRolePeer::PERMISSION_NAMES, $modifiedColumns))
			{
				return true;
			}

		return false;
	}

	/* (non-PHPdoc)
	 * @see kObjectChangedEventConsumer::objectChanged()
	 */
	public function objectChanged(BaseObject $object, array $modifiedColumns)
	{
		if(
			$object instanceof entry
			&&	in_array(entryPeer::STATUS, $modifiedColumns)
			&&	($object->getStatus() == entryStatus::READY || $object->getStatus() == entryStatus::ERROR_CONVERTING)
			&&	$object->getReplacedEntryId()
		)
		{
			kFlowHelper::handleEntryReplacement($object);
			return true;
		}

		if(
			$object instanceof UploadToken
			&&	in_array(UploadTokenPeer::STATUS, $modifiedColumns)
			&&	$object->getStatus() == UploadToken::UPLOAD_TOKEN_FULL_UPLOAD
		)
		{
			kFlowHelper::handleUploadFinished($object);
			return true;
		}
		
		if(
			$object instanceof ClippingTaskEntryServerNode
			&&	in_array(EntryServerNodePeer::STATUS, $modifiedColumns)
		)
		{
			if ($object->getServerType() == EntryServerNodeType::LIVE_CLIPPING_TASK)
				kFlowHelper::handleClippingTaskStatusUpdate($object);
			return true;
		}

		if(
			$object instanceof BatchJob
			&&	$object->getJobType() == BatchJobType::BULKUPLOAD
			&&	$object->getStatus() == BatchJob::BATCHJOB_STATUS_ABORTED
			&&	in_array(BatchJobPeer::STATUS, $modifiedColumns)
			&&	in_array($object->getColumnsOldValue(BatchJobPeer::STATUS), BatchJobPeer::getClosedStatusList())
		)
		{
			$partner = $object->getPartner();
			if($partner->getEnableBulkUploadNotificationsEmails())
				kFlowHelper::sendBulkUploadNotificationEmail($object, MailType::MAIL_TYPE_BULKUPLOAD_ABORTED, array($partner->getAdminName(), $object->getId(), kFlowHelper::createBulkUploadLogUrl($object)));
				
			return true;
		}
			
		if ($object instanceof UserRole
			&& in_array(UserRolePeer::PERMISSION_NAMES, $modifiedColumns))
		{
			$filter = new kuserFilter();
			$filter->set('_eq_role_ids', $object->getId());
			kJobsManager::addIndexJob($object->getPartnerId(), IndexObjectType::USER, $filter, false);
			return true;
		}

		if(
			!($object instanceof flavorAsset)
			||	!in_array(assetPeer::STATUS, $modifiedColumns)
		)
			return true;

			
		$entry = entryPeer::retrieveByPKNoFilter($object->getEntryId());

		KalturaLog::info("Asset id [" . $object->getId() . "] isOriginal [" . $object->getIsOriginal() . "] status [" . $object->getStatus() . "]");
		if($object->getIsOriginal())
			return true;
		
		if($object->getStatus() == flavorAsset::FLAVOR_ASSET_STATUS_VALIDATING)
		{
			$postConvertAssetType = BatchJob::POSTCONVERT_ASSET_TYPE_FLAVOR;
			$offset = $entry->getThumbOffset(); // entry getThumbOffset now takes the partner DefThumbOffset into consideration
			$syncKey = $object->getSyncKey(asset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);

			$fileSync = kFileSyncUtils::getLocalFileSyncForKey($syncKey, false);
			if(!$fileSync)
				return true;


			if(kFileSyncUtils::getLocalFilePathForKey($syncKey))
				kJobsManager::addPostConvertJob(null, $postConvertAssetType, $syncKey, $object->getId(), null, $entry->getCreateThumb(), $offset);

			$conversionProfile = $entry->getconversionProfile2();
			if($conversionProfile && !flavorParamsConversionProfilePeer::retrieveByConversionProfile( $entry->getConversionProfileId()) )
			{
				$conversionProfileTags = explode(',', $conversionProfile->getTags());
				if (in_array(conversionProfile2::SKIP_VALIDATION, $conversionProfileTags))
				{
					$object->setStatus(flavorAsset::ASSET_STATUS_READY);
					$object->save();
					$entry->setStatus(entryStatus::READY);
					$entry->save();
				}
			}
		}
		elseif ($object->getStatus() == flavorAsset::FLAVOR_ASSET_STATUS_READY)
		{
			// If we get a ready flavor and the entry is in no content
			if($entry->getStatus() == entryStatus::NO_CONTENT)
			{
				$entry->setStatus(entryStatus::PENDING); // we change the entry to pending
				$entry->save();
			}
		}

		return true;
	}

	/* (non-PHPdoc)
	 * @see kObjectDeletedEventConsumer::shouldConsumeDeletedEvent()
	 */
	public function shouldConsumeDeletedEvent(BaseObject $object)
	{
		if($object instanceof UploadToken)
			return true;
			
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see kObjectAddedEventConsumer::shouldConsumeReadyForReplacmentEvent()
	 */
	public function shouldConsumeReadyForReplacmentEvent(BaseObject $object)
	{
		if($object instanceof entry)
			return true;
			
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see kObjectAddedEventConsumer::objectReadyForReplacment()
	 */
	public function objectReadyForReplacment(BaseObject $object, BatchJob $raisedJob = null)
	{
		
		$entry = entryPeer::retrieveByPK($object->getReplacedEntryId());
		if(!$entry)
		{
			KalturaLog::err("Real entry id [" . $object->getReplacedEntryId() . "] not found");
			return true;
		}
		
		kBusinessConvertDL::replaceEntry($entry, $object);
		return true;
	}
	

	/* (non-PHPdoc)
	 * @see kObjectDeletedEventConsumer::objectDeleted()
	 */
	public function objectDeleted(BaseObject $object, BatchJob $raisedJob = null)
	{
		kFlowHelper::handleUploadCanceled($object);
		return true;
	}

	/**
	 * @param BaseObject $object
	 * @param string $previousVersion
	 * @return bool true if the consumer should handle the event
	 */
	public function shouldConsumeDataChangedEvent(BaseObject $object, $previousVersion = null)
	{
		if($object instanceof asset)
			return true;

		return false;		
	}

	/**
	 * @param BaseObject $object
	 * @param string $previousVersion
	 * @param BatchJob $raisedJob
	 * @return bool true if should continue to the next consumer
	 */
	public function objectDataChanged(BaseObject $object, $previousVersion = null, BatchJob $raisedJob = null)
	{
		if ($object instanceof flavorAsset)
		{
			if ($object->getStatus() == asset::FLAVOR_ASSET_STATUS_QUEUED)
			{
				if (!$object->getIsOriginal())
				{
					$object->setStatus(asset::FLAVOR_ASSET_STATUS_VALIDATING);
					$object->save();
				}
			}
		}
		return true;
	}
	
	protected function updatedExportCsv (BatchJob $dbBatchJob, kExportCsvJobData $data)
	{
		switch($dbBatchJob->getStatus())
		{
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return kFlowHelper::handleExportCsvFinished($dbBatchJob, $data);
			default:
				return $dbBatchJob;
		}
	}

}
