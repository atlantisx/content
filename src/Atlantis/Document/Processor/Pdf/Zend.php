<?php namespace Atlantis\Document\Processor\Pdf;

use ZendPdf\PdfParser;
use ZendPdf\PdfDocument;
use ZendPdf\Exception;
use ZendPdf\InternalType;
use ZendPdf\Trailer;


class Zend extends PdfDocument{

    /**
     * List of form fields
     *
     * @var array - Associative array, key: name of form field, value: Zend_Pdf_Element
     */
    protected $_formFields = array();


    /**
     * Load form fields
     * Populates the _formFields array, for later lookup of fields by name
     *
     * @param Zend_Pdf_Element_Reference $root Document catalog entry
     */
    protected function _loadFormFields(Zend_Pdf_Element_Reference $root)
    {
        if ($root->AcroForm === null || $root->AcroForm->Fields === null) {
            return;
        }

        foreach ($root->AcroForm->Fields->items as $field) {
            if ($field->FT->value == 'Tx' && $field->T !== null) /* We only support fields that are textfields and have a name */ {
                $this->_formFields[$field->T->value] = $field;
            }
        }

        if (!$root->AcroForm->NeedAppearances || !$root->AcroForm->NeedAppearances->value) {
            /* Ask the .pdf viewer to generate its own appearance data, so we do not have to */
            $root->AcroForm->add(new Zend_Pdf_Element_Name('NeedAppearances'), new Zend_Pdf_Element_Boolean(true));
            $root->AcroForm->touch();
        }
    }


    public function __construct($source = null, $revision = null, $load = false)
    {
        parent::__construct();

        if ($source !== null) {
            $this->_loadFormfields($this->_trailer->Root);
        }

        /*$this->_objFactory = ObjectFactory::createFactory(1);

        if ($source !== null) {
            $this->_parser           = new PdfParser\StructureParser($source, $this->_objFactory, $load);
            $this->_pdfHeaderVersion = $this->_parser->getPDFVersion();
            $this->_trailer          = $this->_parser->getTrailer();
            if ($this->_trailer->Encrypt !== null) {
                throw new Exception\NotImplementedException('Encrypted document modification is not supported');
            }
            if ($revision !== null) {
                $this->rollback($revision);
            } else {
                $this->_loadPages($this->_trailer->Root->Pages);
            }

            $this->_loadNamedDestinations($this->_trailer->Root, $this->_parser->getPDFVersion());
            $this->_loadOutlines($this->_trailer->Root);
            $this->_loadFormfields($this->_trailer->Root);

            if ($this->_trailer->Info !== null) {
                $this->properties = $this->_trailer->Info->toPhp();

                if (isset($this->properties['Trapped'])) {
                    switch ($this->properties['Trapped']) {
                        case 'True':
                            $this->properties['Trapped'] = true;
                            break;

                        case 'False':
                            $this->properties['Trapped'] = false;
                            break;

                        case 'Unknown':
                            $this->properties['Trapped'] = null;
                            break;

                        default:
                            // Wrong property value
                            // Do nothing
                            break;
                    }
                }

                $this->_originalProperties = $this->properties;
            }
        } else {
            $this->_pdfHeaderVersion = self::PDF_VERSION;

            $trailerDictionary = new InternalType\DictionaryObject();

            $docId = md5(uniqid(rand(), true));   // 32 byte (128 bit) identifier
            $docIdLow  = substr($docId,  0, 16);  // first 16 bytes
            $docIdHigh = substr($docId, 16, 16);  // second 16 bytes

            $trailerDictionary->ID = new InternalType\ArrayObject();
            $trailerDictionary->ID->items[] = new InternalType\BinaryStringObject($docIdLow);
            $trailerDictionary->ID->items[] = new InternalType\BinaryStringObject($docIdHigh);

            $trailerDictionary->Size = new InternalType\NumericObject(0);

            $this->_trailer = new Trailer\Generated($trailerDictionary);

            $docCatalog = $this->_objFactory->newObject(new InternalType\DictionaryObject());
            $docCatalog->Type     = new InternalType\NameObject('Catalog');
            $docCatalog->Version  = new InternalType\NameObject(self::PDF_VERSION);
            $this->_trailer->Root = $docCatalog;

            $docPages = $this->_objFactory->newObject(new InternalType\DictionaryObject());
            $docPages->Type  = new InternalType\NameObject('Pages');
            $docPages->Kids  = new InternalType\ArrayObject();
            $docPages->Count = new InternalType\NumericObject(0);
            $docCatalog->Pages = $docPages;
        }*/
    }


    /**
     * Retrieves a list with the names of the AcroForm textfields in the PDF
     *
     * @return array of strings
     */
    public function getTextFieldNames()
    {
        return array_keys($this->_formFields);
    }


    /**
     * Sets the value of an AcroForm text field
     *
     * @param string $name Name of textfield
     * @param string $value Value
     * @throws Zend_Pdf_Exception if the textfield does not exist in the pdf
     */
    public function setTextField($name, $value)
    {
        if (!isset($this->_formFields[$name]))
            throw new Zend_Pdf_Exception("Field '$name' does not exist or is not a textfield");

        $field = $this->_formFields[$name];
        $field->add(new Zend_Pdf_Element_Name('V'), new Zend_Pdf_Element_String($value));
        $field->touch();
    }
}