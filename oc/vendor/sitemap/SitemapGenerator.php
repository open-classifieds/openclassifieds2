<?php
/**
 * @category   class
 * @package    SitemapGenerator
 * @author     Paweł Antczak <pawel@antczak.org>
 * @copyright  2009 Paweł Antczak
 * @license    http://www.gnu.org/licenses/gpl.html  GPL V 2.0
 * @version    1.2.0
 * @see        http://www.sitemaps.org/protocol.php
 * @see        http://en.wikipedia.org/wiki/Sitemaps
 * @see        http://en.wikipedia.org/wiki/Sitemap_index
 */
class SitemapGenerator {
    /**
     * Name of sitemap file
     * @var string
     * @access public
     */
    public $sitemapFileName = "sitemap.xml";
    /**
     * Name of sitemap index file
     * @var string
     * @access public
     */

    public $sitemapIndexFileName = "sitemap-index.xml";
    /**
     * Robots file name
     * @var string
     * @access public
     */
    public $robotsFileName = "robots.txt";
    /**
     * Quantity of URLs per single sitemap file.
     * According to specification max value is 50.000.
     * If Your links are very long, sitemap file can be bigger than 10MB,
     * in this case use smaller value.
     * @var int
     * @access public
     */
    public $maxURLsPerSitemap = 50000;
    /**
     * If true, two sitemap files (.xml and .xml.gz) will be created and added to robots.txt.
     * If true, .gz file will be submitted to search engines.
     * If quantity of URLs will be bigger than 50.000, option will be ignored,
     * all sitemap files except sitemap index will be compressed.
     * @var bool
     * @access public
     */
    public $createGZipFile = false;
    /**
     * URL to Your site.
     * Script will use it to send sitemaps to search engines.
     * @var string
     * @access private
     */
    private $baseURL;
    /**
     * Base path. Relative to script location.
     * Use this if Your sitemap and robots files should be stored in other
     * directory then script.
     * @var string
     * @access private
     */
    private $basePath;
    /**
     * Version of this class
     * @var string
     * @access private
     */
    private $classVersion = "1.2.0";
    /**
     * Search engines URLs
     * @var array of strings
     * @access private
     */
    private $searchEngines = array(
        array("http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=USERID&url=",
        "http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap="),
        "http://www.google.com/webmasters/tools/ping?sitemap=",
        "http://submissions.ask.com/ping?sitemap=",
        "http://www.bing.com/webmaster/ping.aspx?siteMap="
    );
    /**
     * Array with urls
     * @var array of strings
     * @access private
     */
    private $urls;
    /**
     * Array with sitemap
     * @var array of strings
     * @access private
     */

    private $sitemaps;
    /**
     * Array with sitemap index
     * @var array of strings
     * @access private
     */

    private $sitemapIndex;
     /**
     * Current sitemap full URL
     * @var string
     * @access private
     */
    private $sitemapFullURL;

    /**
     * Constructor.
     * @param string $baseURL You site URL, with / at the end.
     * @param string|null $basePath Relative path where sitemap and robots should be stored.
     */
    public function __construct($baseURL, $basePath = "") {
        $this->baseURL = $baseURL;
        $this->basePath = $basePath;
    }
    /**
     * Use this to add many URL at one time.
     * Each inside array can have 1 to 4 fields.
     * @param array of arrays of strings $urlsArray
     */
    public function addUrls($urlsArray) {
        if (!is_array($urlsArray))
            throw new InvalidArgumentException("Array as argument should be given.");
        foreach ($urlsArray as $url) {
            $this->addUrl(isset ($url[0]) ? $url[0] : null,
                isset ($url[1]) ? $url[1] : null,
                isset ($url[2]) ? $url[2] : null,
                isset ($url[3]) ? $url[3] : null);
        }
    }
    /**
     * Use this to add single URL to sitemap.
     * @param string $url URL
     * @param string $lastModified When it was modified, use ISO 8601
     * @param string $changeFrequency How often search engines should revisit this URL
     * @param string $priority Priority of URL on You site
     * @see http://en.wikipedia.org/wiki/ISO_8601
     * @see http://php.net/manual/en/function.date.php
     */
    public function addUrl($url, $lastModified = null, $changeFrequency = null, $priority = null) {
        if ($url == null)
            throw new InvalidArgumentException("URL is mandatory. At least one argument should be given.");
        $urlLenght = extension_loaded('mbstring') ? mb_strlen($url, Kohana::$charset) : strlen($url);
        if ($urlLenght > 2048)
            throw new InvalidArgumentException("URL lenght can't be bigger than 2048 characters.
                                                Note, that precise url length check is guaranteed only using mb_string extension.
                                                Make sure Your server allow to use mbstring extension.");
        $tmp = array();
        $tmp['loc'] = $url;
        if (isset($lastModified)) $tmp['lastmod'] = $lastModified;
        if (isset($changeFrequency)) $tmp['changefreq'] = $changeFrequency;
        if (isset($priority)) $tmp['priority'] = $priority;
        $this->urls[] = $tmp;
    }
    /**
     * Create sitemap in memory.
     */
    public function createSitemap() {
        if (!isset($this->urls))
            throw new BadMethodCallException("To create sitemap, call addUrl or addUrls function first.");
        if ($this->maxURLsPerSitemap > 50000)
            throw new InvalidArgumentException("More than 50,000 URLs per single sitemap is not allowed.");

        $generatorInfo = '<!-- generator="SimpleSitemapGenerator/'.$this->classVersion.'" -->
                          <!-- sitemap-generator-url="http://www.antczak.org"
                          sitemap-generator-version="'.$this->classVersion.'" -->
                          <!-- generated-on="'.date('c').'" -->';
        $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?>'.$generatorInfo.'
                            <urlset
                                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                                http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
                                xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                         </urlset>';
        $sitemapIndexHeader = '<?xml version="1.0" encoding="UTF-8"?>'.$generatorInfo.'
                                <sitemapindex
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                                    http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
                                    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                              </sitemapindex>';
        foreach(array_chunk($this->urls,$this->maxURLsPerSitemap) as $sitemap) {
            $xml = new SimpleXMLElement($sitemapHeader);
            foreach($sitemap as $url) {
                $row = $xml->addChild('url');
                $row->addChild('loc',htmlspecialchars($url['loc'],ENT_QUOTES,'UTF-8'));
                if (isset($url['lastmod'])) $row->addChild('lastmod', $url['lastmod']);
                if (isset($url['changefreq'])) $row->addChild('changefreq',$url['changefreq']);
                if (isset($url['priority'])) $row->addChild('priority',$url['priority']);
            }
            if (strlen($xml->asXML()) > 10485760)
                throw new LengthException("Sitemap size is more than 10MB (10,485,760),
                    please decrease maxURLsPerSitemap variable.");
            $this->sitemaps[] = $xml->asXML();

        }
        if (sizeof($this->sitemaps) > 1000)
            throw new LengthException("Sitemap index can contains 1000 single sitemaps.
                Perhaps You trying to submit too many URLs.");
        if (sizeof($this->sitemaps) > 1) {
            for($i=0; $i<sizeof($this->sitemaps); $i++) {
                $this->sitemaps[$i] = array(
                    str_replace(".xml", ($i+1).".xml.gz", $this->sitemapFileName),
                    $this->sitemaps[$i]
                );
            }
            $xml = new SimpleXMLElement($sitemapIndexHeader);
            foreach($this->sitemaps as $sitemap) {
                $row = $xml->addChild('sitemap');
                $row->addChild('loc',$this->baseURL.htmlentities($sitemap[0]));
                $row->addChild('lastmod', date('c'));
            }
            $this->sitemapFullURL = $this->baseURL.$this->sitemapIndexFileName;
            $this->sitemapIndex = array(
                $this->sitemapIndexFileName,
                $xml->asXML());
        }
        else {
            if ($this->createGZipFile)
                $this->sitemapFullURL = $this->baseURL.$this->sitemapFileName.".gz";
            else
                $this->sitemapFullURL = $this->baseURL.$this->sitemapFileName;
            $this->sitemaps[0] = array(
                $this->sitemapFileName,
                $this->sitemaps[0]);
        }
    }
    /**
     * Returns created sitemaps as array of strings.
     * Use it You want to work with sitemap without saving it as files.
     * @return array of strings
     * @access public
     */
    public function toArray() {
        if (isset($this->sitemapIndex))
            return array_merge(array($this->sitemapIndex),$this->sitemaps);
        else
            return $this->sitemaps;
    }
    /**
     * Will write sitemaps as files.
     * @access public
     */
    public function writeSitemap() {
        if (!isset($this->sitemaps))
            throw new BadMethodCallException("To write sitemap, call createSitemap function first.");
        if (isset($this->sitemapIndex)) {
            $this->_writeFile($this->sitemapIndex[1], $this->basePath, $this->sitemapIndex[0]);
            foreach($this->sitemaps as $sitemap) {
                $this->_writeGZipFile($sitemap[1], $this->basePath, $sitemap[0]);
            }
        }
        else {
            $this->_writeFile($this->sitemaps[0][1], $this->basePath, $this->sitemaps[0][0]);
            if ($this->createGZipFile)
                $this->_writeGZipFile($this->sitemaps[0][1], $this->basePath, $this->sitemaps[0][0].".gz");
        }
    }
    /**
     * If robots.txt file exist, will update information about newly created sitemaps.
     * If there is no robots.txt will, create one and put into it information about sitemaps.
     * @access public
     */
    public function updateRobots() {
        if (!isset($this->sitemaps))
            throw new BadMethodCallException("To update robots.txt, call createSitemap function first.");
        $sampleRobotsFile = "User-agent: *\nAllow: /";
        if (file_exists($this->basePath.$this->robotsFileName)) {
            $robotsFile = explode("\n", file_get_contents($this->basePath.$this->robotsFileName));
            $robotsFileContent = "";
            foreach($robotsFile as $key=>$value) {
                if(substr($value, 0, 8) == 'Sitemap:') unset($robotsFile[$key]);
                else $robotsFileContent .= $value."\n";
            }
            $robotsFileContent .= "Sitemap: $this->sitemapFullURL";
            if ($this->createGZipFile && !isset($this->sitemapIndex))
                $robotsFileContent .= "\nSitemap: ".$this->sitemapFullURL.".gz";
            file_put_contents($this->basePath.$this->robotsFileName,$robotsFileContent);
        }
        else {
            $sampleRobotsFile = $sampleRobotsFile."\n\nSitemap: ".$this->sitemapFullURL;
            if ($this->createGZipFile && !isset($this->sitemapIndex))
                $sampleRobotsFile .= "\nSitemap: ".$this->sitemapFullURL.".gz";
            file_put_contents($this->basePath.$this->robotsFileName, $sampleRobotsFile);
        }
    }
    /**
     * Will inform search engines about newly created sitemaps.
     * Google, Ask, Bing and Yahoo will be noticed.
     * If You don't pass yahooAppId, Yahoo still will be informed,
     * but this method can be used once per day. If You will do this often,
     * message that limit was exceeded  will be returned from Yahoo.
     * @param string $yahooAppId Your site Yahoo appid.
     * @return array of messages and http codes from each search engine
     * @access public
     */
    public function submitSitemap($yahooAppId = null) {
        if (!isset($this->sitemaps))
            throw new BadMethodCallException("To submit sitemap, call createSitemap function first.");
        if (!extension_loaded('curl'))
            throw new BadMethodCallException("cURL library is needed to do submission.");
        $searchEngines = $this->searchEngines;
        $searchEngines[0] = isset($yahooAppId) ? str_replace("USERID", $yahooAppId, $searchEngines[0][0]) : $searchEngines[0][1];
        $result = array();
        for($i=0;$i<sizeof($searchEngines);$i++) {
            $submitSite = curl_init($searchEngines[$i].htmlspecialchars($this->sitemapFullURL,ENT_QUOTES,'UTF-8'));
            curl_setopt($submitSite, CURLOPT_RETURNTRANSFER, true);
            $responseContent = curl_exec($submitSite);
            $response = curl_getinfo($submitSite);
            $submitSiteShort = array_reverse(explode(".",parse_url($searchEngines[$i], PHP_URL_HOST)));
            $result[] = array("site"=>$submitSiteShort[1].".".$submitSiteShort[0],
                "fullsite"=>$searchEngines[$i].htmlspecialchars($this->sitemapFullURL, ENT_QUOTES,'UTF-8'),
                "http_code"=>$response['http_code'],
                "message"=>str_replace("\n", " ", strip_tags($responseContent)));
        }
        return $result;
    }
    /**
     * Save file.
     * @param string $content
     * @param string $filePath
     * @param string $fileName
     * @return bool
     * @access private
     */
    private function _writeFile($content, $filePath, $fileName) {
        $file = fopen($filePath.$fileName, 'w');
        fwrite($file, $content);
        return fclose($file);
    }
    /**
     * Save GZipped file.
     * @param string $content
     * @param string $filePath
     * @param string $fileName
     * @return bool
     * @access private
     */
    private function _writeGZipFile($content, $filePath, $fileName) {
        $file = gzopen($filePath.$fileName, 'w');
        gzwrite($file, $content);
        return gzclose($file);
    }
}
?>
