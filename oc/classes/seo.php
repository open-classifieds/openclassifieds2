<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class to improve the seo of the site 
 *
 * @package    OC
 * @category   SEO
 * @author     Chema <chema@open-classifieds.com>
 * @version    1.0   
 * @date       21-02-2014  
 * @url        http://garridodiaz.com/phpseo/
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 * 
 * @notes 
 * length of title and description from:
 * http://moz.com/learn/seo/meta-description 
 * http://moz.com/learn/seo/title-tag
 * remember keywords are useless!! http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html
 *
 * @usage 
 * seo::$charset = Kohana::$charset;
 * $this->template->title = seo::text($this->template->title, 70);
 * $this->template->meta_keywords = seo::keywords($this->template->meta_description);
 * $this->template->meta_description = seo::text($this->template->meta_description);
 */

class seo{ 
    
    /**
     * default charset to use
     * @var string
     */
    public static $charset = 'UTF-8';
    
    /**
     * banned words in english feel free to change them
     * @var array
     */
    public static  $banned_words = array();
    //public static  $banned_words = array('able', 'about', 'above', 'act', 'add', 'afraid', 'after', 'again', 'against', 'age', 'ago', 'agree', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'amount', 'an', 'and', 'anger', 'angry', 'animal', 'another', 'answer', 'any', 'appear', 'apple', 'are', 'arrive', 'arm', 'arms', 'around', 'arrive', 'as', 'ask', 'at', 'attempt', 'aunt', 'away', 'back', 'bad', 'bag', 'bay', 'be', 'became', 'because', 'become', 'been', 'before', 'began', 'begin', 'behind', 'being', 'bell', 'belong', 'below', 'beside', 'best', 'better', 'between', 'beyond', 'big', 'body', 'bone', 'born', 'borrow', 'both', 'bottom', 'box', 'boy', 'break', 'bring', 'brought', 'bug', 'built', 'busy', 'but', 'buy', 'by', 'call', 'came', 'can', 'cause', 'choose', 'close', 'close', 'consider', 'come', 'consider', 'considerable', 'contain', 'continue', 'could', 'cry', 'cut', 'dare', 'dark', 'deal', 'dear', 'decide', 'deep', 'did', 'die', 'do', 'does', 'dog', 'done', 'doubt', 'down', 'during', 'each', 'ear', 'early', 'eat', 'effort', 'either', 'else', 'end', 'enjoy', 'enough', 'enter', 'even', 'ever', 'every', 'except', 'expect', 'explain', 'fail', 'fall', 'far', 'fat', 'favor', 'fear', 'feel', 'feet', 'fell', 'felt', 'few', 'fill', 'find', 'fit', 'fly', 'follow', 'for', 'forever', 'forget', 'from', 'front', 'gave', 'get', 'gives', 'goes', 'gone', 'good', 'got', 'gray', 'great', 'green', 'grew', 'grow', 'guess', 'had', 'half', 'hang', 'happen', 'has', 'hat', 'have', 'he', 'hear', 'heard', 'held', 'hello', 'help', 'her', 'here', 'hers', 'high', 'hill', 'him', 'his', 'hit', 'hold', 'hot', 'how', 'however', 'I', 'if', 'ill', 'in', 'indeed', 'instead', 'into', 'iron', 'is', 'it', 'its', 'just', 'keep', 'kept', 'knew', 'know', 'known', 'late', 'least', 'led', 'left', 'lend', 'less', 'let', 'like', 'likely', 'likr', 'lone', 'long', 'look', 'lot', 'make', 'many', 'may', 'me', 'mean', 'met', 'might', 'mile', 'mine', 'moon', 'more', 'most', 'move', 'much', 'must', 'my', 'near', 'nearly', 'necessary', 'neither', 'never', 'next', 'no', 'none', 'nor', 'not', 'note', 'nothing', 'now', 'number', 'of', 'off', 'often', 'oh', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'out', 'please', 'prepare', 'probable', 'pull', 'pure', 'push', 'put', 'raise', 'ran', 'rather', 'reach', 'realize', 'reply', 'require', 'rest', 'run', 'said', 'same', 'sat', 'saw', 'say', 'see', 'seem', 'seen', 'self', 'sell', 'sent', 'separate', 'set', 'shall', 'she', 'should', 'side', 'sign', 'since', 'so', 'sold', 'some', 'soon', 'sorry', 'stay', 'step', 'stick', 'still', 'stood', 'such', 'sudden', 'suppose', 'take', 'taken', 'talk', 'tall', 'tell', 'ten', 'than', 'thank', 'that', 'the', 'their', 'them', 'then', 'there', 'therefore', 'these', 'they', 'this', 'those', 'though', 'through', 'till', 'to', 'today', 'told', 'tomorrow', 'too', 'took', 'tore', 'tought', 'toward', 'tried', 'tries', 'trust', 'try', 'turn', 'two', 'under', 'until', 'up', 'upon', 'us', 'use', 'usual', 'various', 'verb', 'very', 'visit', 'want', 'was', 'we', 'well', 'went', 'were', 'what', 'when', 'where', 'whether', 'which', 'while', 'white', 'who', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yes', 'yet', 'you', 'young', 'your', 'br', 'img', 'p','lt', 'gt', 'quot', 'copy');

    /**
     * min len for a word in the keywords
     * @var integer
     */
    public static $min_word_length = 4;
     
    /**
     * SEO for text length
     * returns a text with text
     * @param  integer $length of the description
     * @return string      
     */
    public static function text($text, $length = 160)
    {
        return self::limit_chars(self::clean($text), $length,'',TRUE);
    } 

    /**
     * gets the keyword from the text in the construct
     * 
     * @param  integer $max_keys number of keywords
     * @return string      
     */
    public static function keywords($text, $max_keys = 15)
    {
        $text = self::clean(mb_strtolower($text, Kohana::$charset));
        $text = str_replace (array('–','(',')','+',':','.','?','!','_','*','-','"'), '', $text);//replace not valid character 
        $text = str_replace (array(' ','.',';'), ',', $text);//replace for comas 

        $wordcount = array_count_values(explode(',',$text)); 

        //array to keep word->number of repetitions 
        //$wordcount = array_count_values(str_word_count(self::clean($text),1));

        //remove small words
        foreach ($wordcount as $key => $value) 
        {
            if ( (mb_strlen($key, Kohana::$charset)<= self::$min_word_length) OR in_array($key, self::$banned_words))
                unset($wordcount[$key]);
        }
        
        //sort keywords from most repetitions to less 
        uasort($wordcount,array('self','cmp'));

        //keep only X keywords
        $wordcount = array_slice($wordcount,0, $max_keys);

        //return keywords on a string
        return implode(', ', array_keys($wordcount));
    } 

    /**
     * cleans an string from HTML spaces etc...
     * @param  string $text 
     * @return string       
     */
    private static function clean($text)
    { 
        $text = html_entity_decode($text,ENT_QUOTES,self::$charset);
        $text = strip_tags($text);//erases any html markup
        $text = preg_replace('/\s\s+/', ' ', $text);//erase possible duplicated white spaces
        $text = str_replace (array('\r\n', '\n', '+'), ',', $text);//replace possible returns 
        return trim($text); 
    } 
    
    /**
     * sort for uasort descendant numbers , compares values
     * @param  integer $a 
     * @param  integer $b 
     * @return integer    
     */
    private static function cmp($a, $b) 
    {
        if ($a == $b) return 0; 

        return ($a < $b) ? 1 : -1; 
    } 

   /**
     * Limits a phrase to a given number of characters.
     * ported from kohana text class, so this class can remain as independent as possible
     *     $text = Text::limit_chars($text);
     *
     * @param   string  $str            phrase to limit characters of
     * @param   integer $limit          number of characters to limit to
     * @param   string  $end_char       end character or entity
     * @param   boolean $preserve_words enable or disable the preservation of words while limiting
     * @return  string
     */
    private static function limit_chars($str, $limit = 100, $end_char = NULL, $preserve_words = FALSE)
    {
        $end_char = ($end_char === NULL) ? '…' : $end_char;

        $limit = (int) $limit;

        if (trim($str) === '' OR mb_strlen($str, Kohana::$charset) <= $limit)
            return $str;

        if ($limit <= 0)
            return $end_char;

        if ($preserve_words === FALSE)
            return rtrim(mb_substr($str, 0, $limit, Kohana::$charset)).$end_char;

        // Don't preserve words. The limit is considered the top limit.
        // No strings with a length longer than $limit should be returned.
        if ( ! preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches))
            return $end_char;

        return rtrim($matches[0]).((mb_strlen($matches[0], Kohana::$charset) === mb_strlen($str, Kohana::$charset)) ? '' : $end_char);
    }
} 
//end seo class 
