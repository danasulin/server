<?php 
class Form_DropFolderConfigure extends Infra_Form
{
	protected $newPartnerId;
	protected $dropFolderType;
	
	const EXTENSION_SUBFORM_NAME = 'extensionSubForm';
	
	public function __construct($partnerId, $type)
	{
		$this->newPartnerId = $partnerId;
		$this->dropFolderType = $type;
		
		parent::__construct();
	}
	
	
	public function init()
	{
		$this->setAttrib('id', 'frmDropFolderConfigure');
		$this->setMethod('post');			
		
		$titleElement = new Zend_Form_Element_Hidden('generalTitle');
		$titleElement->setLabel('General');
		$titleElement->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElement($titleElement);
		
		$this->addElement('text', 'id', array(
			'label'			=> 'ID:',
			'filters'		=> array('StringTrim'),
			'readonly'		=> true,
			'disabled'		=> 'disabled',
		));
		
		$this->addElement('text', 'partnerId', array(
			'label' 		=> 'Related Publisher ID:',
			'required'		=> true,
			'filters' 		=> array('StringTrim'),
			'placement' => 'prepend',
			'readonly'		=> true,
		));
		
		$this->addElement('text', 'name', array(
			'label' 		=> 'Drop Folder Name:',
			'required'		=> true,
			'filters'		=> array('StringTrim'),
			'placement' => 'prepend',
		));
		
		$this->addElement('text', 'description', array(
			'label' 		=> 'Description:',
			'required'		=> false,
			'filters'		=> array('StringTrim'),
		));
		
		$typeForView = new Kaltura_Form_Element_EnumSelect('typeForView', array('enum' => 'Kaltura_Client_DropFolder_Enum_DropFolderType'));
		$typeForView->setLabel('Type:');
		$typeForView->setAttrib('readonly', true);
		$typeForView->setAttrib('disabled', 'disabled');
		$typeForView->setValue($this->dropFolderType);
		$this->addElement($typeForView);
		
		$this->addElement('hidden', 'type', array(
			'filters' 		=> array('StringTrim'),
			'decorators'    => array('ViewHelper'),
		    'value'			=> $this->dropFolderType,
		));
		
		$this->addElement('text', 'tags', array(
			'label' 		=> 'Tags: (used by batch workers)',
			'required'		=> false,
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('hidden', 'crossLine1', array(
			'decorators' => array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'hr', 'class' => 'crossLine')))
		));
				
		// --------------------------------
		
		$titleElement = new Zend_Form_Element_Hidden('ingestionSettingsTitle');
		$titleElement->setLabel('Ingestion Settings');
		$titleElement->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElement($titleElement);
		
		$this->addConversionProfiles();
		
		$this->addElement('text', 'fileNamePatterns', array(
			'label' 		=> 'Source File Name Patterns (to handle):',
			'required'		=> true,
		    'value'			=> '*.*',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'ignoreFileNamePatterns', array(
			'label' 		=> 'Ignore file name patterns (don\'t even list them) :',
			'filters'		=> array('StringTrim'),
		));
		
		$fileHandlerTypes = new Kaltura_Form_Element_EnumSelect('fileHandlerType', array('enum' => 'Kaltura_Client_DropFolder_Enum_DropFolderFileHandlerType'));
		$fileHandlerTypes->setLabel('Ingestion Source:');
		$fileHandlerTypes->setRequired(true);
		$fileHandlerTypes->setAttrib('onchange', 'handlerTypeChanged()');
		$this->addElement($fileHandlerTypes);
		
		$handlerConfigForm = new Form_ContentFileHandlerConfig();
		$this->addSubForm($handlerConfigForm, 'contentHandlerConfig'); 

		$this->addElement('hidden', 'crossLine2', array(
			'decorators' => array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'hr', 'class' => 'crossLine')))
		));		
		
		// --------------------------------
		
		$titleElement = new Zend_Form_Element_Hidden('locationTitle');
		$titleElement->setLabel('Local Storage Folder Location');
		$titleElement->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElement($titleElement);
		
		$this->addElement('text', 'dc', array(
			'label' 		=> 'Data Center:',
			'required'		=> true,
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'path', array(
			'label' 		=> 'Folder Path:',
			'required'		=> true,
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('hidden', 'crossLine3', array(
			'decorators' => array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'hr', 'class' => 'crossLine')))
		));
		
		// --------------------------------
		
		$titleElement = new Zend_Form_Element_Hidden('policiesTitle');
		$titleElement->setLabel('Folder Policies');
		$titleElement->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElement($titleElement);
		
		$this->addElement('text', 'fileSizeCheckInterval', array(
			'label' 		=> 'Check file size every (seconds):',
			'required'		=> true,
			'filters'		=> array('StringTrim'),
		));
		
		$fileDeletePolicies = new Kaltura_Form_Element_EnumSelect('fileDeletePolicy', array('enum' => 'Kaltura_Client_DropFolder_Enum_DropFolderFileDeletePolicy'));
		$fileDeletePolicies->setLabel('File Deletion Policy:');
		$fileDeletePolicies->setRequired(true);
		$this->addElement($fileDeletePolicies);
		
		$this->addElement('text', 'autoFileDeleteDays', array(
			'label' 		=> 'Auto delete files after (days):',
			'required'		=> true,
			'filters'		=> array('StringTrim'),
		));
		
		// --------------------------------
		
		$extendTypeSubForm = KalturaPluginManager::loadObject('Form_DropFolderConfigureExtend_SubForm', $this->dropFolderType);
		if ($extendTypeSubForm) {
    		$this->addElement('hidden', 'crossLine4', array(
				'decorators' => array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'hr', 'class' => 'crossLine')))
		    ));
		    $extendTypeSubFormTitle = new Zend_Form_Element_Hidden(self::EXTENSION_SUBFORM_NAME.'_title');
    		$extendTypeSubFormTitle->setLabel($extendTypeSubForm->getTitle());
    		$extendTypeSubFormTitle->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
    		$this->addElement($extendTypeSubFormTitle);
    		$extendTypeSubForm->setDecorators(array(
    	        'FormElements',
            ));
		    $this->addSubForm($extendTypeSubForm, self::EXTENSION_SUBFORM_NAME);
		}
	}
	
	
	
	public function populateFromObject($object, $add_underscore = true)
	{
		parent::populateFromObject($object, $add_underscore);
		
		if ($object->fileHandlerType === Kaltura_Client_DropFolder_Enum_DropFolderFileHandlerType::CONTENT) {
			$this->getSubForm('contentHandlerConfig')->populateFromObject($object->fileHandlerConfig, false);
		}
				
		$props = $object;
		if(is_object($object))
			$props = get_object_vars($object);
		
		$allElements = $this->getElements();
		foreach ($allElements as $element)
		{
			if ($element instanceof Kaltura_Form_Element_EnumSelect)
			{
				$elementName = $element->getName();
				$element->setValue(array($props[$elementName]));
			}
		}
		
		$this->setDefault('typeForView', $object->type);
		
		$extendTypeSubForm = $this->getSubForm(self::EXTENSION_SUBFORM_NAME);
		if ($extendTypeSubForm) {
		    $extendTypeSubForm::populateFromObject($object, $add_underscore);
		}
	}
	
	public function getObject($objectType, array $properties, $add_underscore = true, $include_empty_fields = false)
	{
		if (isset($properties[self::EXTENSION_SUBFORM_NAME])) {
		    $properties = array_merge($properties[self::EXTENSION_SUBFORM_NAME], $properties);
		}
	    $objectType = KalturaPluginManager::getObjectClass($objectType, $properties['type']);
	    $object = parent::getObject($objectType, $properties, $add_underscore, $include_empty_fields);
		if ($object->fileHandlerType === Kaltura_Client_DropFolder_Enum_DropFolderFileHandlerType::CONTENT) {
			$object->fileHandlerConfig = $this->getSubForm('contentHandlerConfig')->getObject('Kaltura_Client_DropFolder_Type_DropFolderContentFileHandlerConfig', $properties, $add_underscore, $include_empty_fields);
		}
		else if ($object->fileHandlerType === Kaltura_Client_DropFolder_Enum_DropFolderFileHandlerType::XML){
			$object->fileHandlerConfig = new Kaltura_Client_DropFolderXmlBulkUpload_Type_DropFolderXmlBulkUploadFileHandlerConfig();
		}
		
		$extendTypeSubForm = $this->getSubForm(self::EXTENSION_SUBFORM_NAME);
		if ($extendTypeSubForm) {
		    $object =  $extendTypeSubForm::getObject($object, $objectType, $properties, $add_underscore, $include_empty_fields);
		}
		
		return $object;
	}
	
	
	protected function addConversionProfiles()
	{
		$conversionProfiles = null;
		if (!is_null($this->newPartnerId))
		{
			try 
			{
				$conversionProfileFilter = new Kaltura_Client_Type_ConversionProfileFilter();

				$client = Infra_ClientHelper::getClient();
				Infra_ClientHelper::impersonate($this->newPartnerId);
				$conversionProfileList = $client->conversionProfile->listAction($conversionProfileFilter);
				Infra_ClientHelper::unimpersonate();
				
				$conversionProfiles = $conversionProfileList->objects;
			}
			catch (Kaltura_Client_Exception $e)
			{
				$conversionProfiles = null;
			}
		}
		
		if(!is_null($conversionProfiles) && count($conversionProfiles))
		{
			$this->addElement('select', 'conversionProfileId', array(
				'label' 		=> 'Conversion Profile ID:',
				'required'		=> false,
				'filters'		=> array('StringTrim'),
			));
				
			$element = $this->getElement('conversionProfileId');
			
			foreach($conversionProfiles as $conversionProfile) {
				$element->addMultiOption($conversionProfile->id, $conversionProfile->id.' - '.$conversionProfile->name);
			}
		}
		else 
		{
			$this->addElement('text', 'conversionProfileId', array(
				'label' 		=> 'Conversion Profile ID:',
				'required'		=> false,
				'filters'		=> array('StringTrim'),
			));
		}
	}
	
	    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
    	$fileHandlerType = $data['fileHandlerType'];
    	if ($fileHandlerType != Kaltura_Client_DropFolder_Enum_DropFolderFileHandlerType::CONTENT) {
    		$this->removeSubForm('contentHandlerConfig');
    	}
    	return parent::isValid($data);
    }
 
			
}