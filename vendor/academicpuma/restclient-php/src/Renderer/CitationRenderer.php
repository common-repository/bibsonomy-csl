<?php


namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Exceptions\FileNotFoundException;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use Exception;
use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Exception\CiteProcException;
use Seboettg\CiteProc\StyleSheet;

class CitationRenderer
{

    /**
     * @var string
     */
    private $stylesheetName;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var
     */
    private $stylesheet;

    /**
     * @var CSLModelRenderer
     */
    private $cslModelRenderer;

    /**
     * @var CiteProc
     */
    private $citeProc;


    /**
     * Citation Renderer constructor.
     * @param string $lang
     * @param string $stylesheet
     * @param array $markupExtension
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function __construct(string $stylesheet, $lang = "en-US", $markupExtension = [])
    {
        $this->cslModelRenderer = new CSLModelRenderer();
        $this->lang = $lang;
        if (empty($this->stylesheet)) {
            $this->stylesheet = $this->loadStylesheet($stylesheet);
        }

        if ($this->stylesheet === false) {
            throw new FileNotFoundException("Could not find a style with the name' . $this->stylesheetName . '.");
        }
        $this->citeProc = new CiteProc($this->stylesheet, $this->lang, $markupExtension);
    }

    /**
     * @param $data
     * @param string $mode
     *
     * @return string
     *
     * @throws CiteProcException
     */
    public function render($data, $mode = 'bibliography')
    {
        if ($data instanceof Posts || $data instanceof Post) {
            $data = $this->cslModelRenderer->render($data);
        }

        if (is_array($data)) {
            return $this->citeProc->render($data, $mode);
        } else {
            return $this->citeProc->render(array($data), $mode);
        }
    }

    /**
     * @return string
     * @throws CiteProcException
     */
    public function renderCssStyles()
    {
        return $this->citeProc->renderCssStyles();
    }

    /**
     * @param $stylesheet
     * @return string
     * @throws CiteProcException
     */
    public function loadStylesheet($stylesheet)
    {
        // check, if provided stylesheet is a XML/CSL or just a possible stylesheet name
        $xmlTag = '<?xml';
        $isXml = (substr($stylesheet, 0, strlen($xmlTag)) === $xmlTag);
        if ($isXml) {
            return $stylesheet;
        } else {
            return StyleSheet::loadStyleSheet($stylesheet);
        }
    }

    /**
     * Reseting certain things for this citation renderer
     */
    public function reset()
    {
        $this->cslModelRenderer = new CSLModelRenderer();
    }

}