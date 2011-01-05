<?php 
class Form_MsnProfileConfiguration extends Form_ProviderProfileConfiguration
{
	public function getObject($objectType, array $properties, $add_underscore = true, $include_empty_fields = false)
	{
		$object = parent::getObject($objectType, $properties, $add_underscore, $include_empty_fields);
		
		if($object instanceof KalturaMsnDistributionProfile)
		{
			$requiredFlavorParamsIds = explode(',', $object->requiredFlavorParamsIds);
			$optionalFlavorParamsIds = explode(',', $object->optionalFlavorParamsIds);
			
			if($object->movFlavorParamsId)
			{
				if(!in_array($object->movFlavorParamsId, $requiredFlavorParamsIds))
					$requiredFlavorParamsIds[] = $object->movFlavorParamsId;
					
				$flavorKey = array_search($object->movFlavorParamsId, $optionalFlavorParamsIds);
				if($flavorKey !== false)
					unset($optionalFlavorParamsIds[$flavorKey]);
			}
			
			if($object->flvFlavorParamsId)
			{
				if(!in_array($object->flvFlavorParamsId, $requiredFlavorParamsIds))
					$requiredFlavorParamsIds[] = $object->flvFlavorParamsId;
					
				$flavorKey = array_search($object->flvFlavorParamsId, $optionalFlavorParamsIds);
				if($flavorKey !== false)
					unset($optionalFlavorParamsIds[$flavorKey]);
			}
			
			if($object->wmvFlavorParamsId)
			{
				if(!in_array($object->wmvFlavorParamsId, $requiredFlavorParamsIds))
					$requiredFlavorParamsIds[] = $object->wmvFlavorParamsId;
					
				$flavorKey = array_search($object->wmvFlavorParamsId, $optionalFlavorParamsIds);
				if($flavorKey !== false)
					unset($optionalFlavorParamsIds[$flavorKey]);
			}
			
			$object->requiredFlavorParamsIds = implode(',', $requiredFlavorParamsIds);
			$object->optionalFlavorParamsIds = implode(',', $optionalFlavorParamsIds);
		}
		return $object;
	}
	
	protected function addProviderElements()
	{
		$element = new Zend_Form_Element_Hidden('providerElements');
		$element->setLabel('MSN Specific Configuration');
		$element->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElements(array($element));
		
		$metadataProfiles = null;
		try
		{
			$metadataProfileFilter = new KalturaMetadataProfileFilter();
//			$metadataProfileFilter->partnerIdEqual = $this->partnerId;
			$metadataProfileFilter->metadataObjectTypeEqual = KalturaMetadataObjectType::ENTRY;
			
			$client = Kaltura_ClientHelper::getClient();
			Kaltura_ClientHelper::impersonate($this->partnerId);
			$metadataProfileList = $client->metadataProfile->listAction($metadataProfileFilter);
			Kaltura_ClientHelper::unimpersonate();
			
			$metadataProfiles = $metadataProfileList->objects;
		}
		catch (KalturaClientException $e)
		{
			$metadataProfiles = null;
		}
		
		if(count($metadataProfiles))
		{
			$this->addElement('select', 'metadata_profile_id', array(
				'label'			=> 'Metadata Profile ID:',
				'filters'		=> array('StringTrim'),
			));
			
			$element = $this->getElement('metadata_profile_id');
			foreach($metadataProfiles as $metadataProfile)
				$element->addMultiOption($metadataProfile->id, $metadataProfile->name);
		}
		else 
		{
			$this->addElement('hidden', 'metadata_profile_id', array(
				'value'			=> 0,
			));
		}
		
		$this->addElement('text', 'username', array(
			'label'			=> 'Username:',
			'filters'		=> array('StringTrim'),
		));
	
		$this->addElement('text', 'password', array(
			'label'			=> 'Password:',
			'filters'		=> array('StringTrim'),
		));
	
		$this->addElement('text', 'domain', array(
			'label'			=> 'Domain:',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'cs_id', array(
			'label'			=> 'CS ID:',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'source', array(
			'label'			=> 'Source:',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'mov_flavor_params_id', array(
			'label'			=> 'MOV Flavor Params ID:',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'flv_flavor_params_id', array(
			'label'			=> 'FLV Flavor Params ID:',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'wmv_flavor_params_id', array(
			'label'			=> 'WMV Flavor Params ID:',
			'filters'		=> array('StringTrim'),
		));
	}
}