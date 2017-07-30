<?php
namespace hbynlsl\View;

/**
 * View with a template
 * 
 * {test}   => will parse $this->test
 * {test()} => will parse $this->test();
 * {test('asfd','zxv')} => will parse $this->test('asfd','zxv');
 *
 * @author Elger van Boxtel
 */
class TemplateView extends SimpleTemplateView
{

    /**
     * (non-PHPdoc)
     *
     * @see \view\IView::render()
     */
    protected function getTemplate()
    {
        $contents = parent::getTemplate();
        $contents = $this->checkExpressions($contents);
        $contents = $this->checkBlocks($contents);
        $contents = $this->replaceVars($contents);
        
        return $contents;
    }

	/** 
	 * 
	 */
    private function checkExpressions($aContent)
    {
        $viewmodel = $this;
        
        // if blocks
        $content = preg_replace_callback("/{#(.*)([=<>\!]{2})(.*)}(.*){\/(.*)}/Usi", function ($match) use ($viewmodel)
        {
            $left = preg_match("/[\'\"]/", $match[1]) ? substr($match[1], 1, $match[1] - 1) : $viewmodel->{$match[1]};
            $operator = $match[2];
            $right = preg_match("/[\'\"]/", $match[3]) ? substr($match[3], 1, $match[3] - 1) : $viewmodel->{$match[3]};
            
            switch (trim($operator)) {
                case '==':
                    if (trim($left) == trim($right)) {
                        return trim($match[4]);
                    }
                    break;
                default:
                    throw new \Exception("Operator " . $operator . " in expression not supported");
            }
            return "";
        }, $aContent);
        
        return $content;
    }

	/**
	 *
	 */
    private function checkBlocks($aContent)
    {
        $viewmodel = $this;
        
        // list block, starts with ^
        $content = preg_replace_callback("/{\^(.*)}(.*){\/(.*)}/Usi", function ($match) use ($viewmodel)
        {
            $iter = $viewmodel->{$match[1]};
            if (is_array($iter) || $iter instanceof \Traversable) {
                $listTpl = $match[2];
                $result = '';
                foreach ($iter as $key => $value) {
                    $result .= str_replace('{.}', $value, $listTpl);
                }
                return $result;
            }
            
            return "";
        }, $aContent);
        
        // if blocks
        $content = preg_replace_callback("/{#(.*)}(.*){\/(.*)}/Usi", function ($match) use ($viewmodel)
        {
            if ($viewmodel->{$match[1]} != "") {
                return trim($match[2]);
            }
            return "";
        }, $content);
        
        // NOT blocks
        $content = preg_replace_callback("/{!(.*)}(.*){\/(.*)}/Usi", function ($match) use ($viewmodel)
        {
            if ($viewmodel->{$match[1]} == "") {
                return trim($match[2]);
            }
            return "";
        }, $content);
        
        return $content;
    }

    /**
     * Replace all variables
     *
     * @param string $aContent            
     *
     * @return string the new content
     */
    private function replaceVars($aContent)
    {
        $viewmodel = $this;
        return preg_replace_callback("/{(.*)}/Ui", function ($match) use ($viewmodel)
        {
            $var = $match[1];
            if (strstr($var, ".")) { // Object?
                $parts = explode(".", $var);
                $obj = $viewmodel->{$parts[0]};
                if (! is_object($obj)) {
                    return "";
                } else 
                    if (preg_match("/.*\(\)/i", $var)) { // method call
                        $method = preg_replace("/\(\)/", "", $parts[1]);
                        $result = $obj->{$method}();
                    } else {
                        $result = $viewmodel->{$parts[count($parts) - 1]};
                    }
                
                return $result;
            } else if(strstr($var, '(') && strstr($var, ')')) { // Closure
                if (preg_match("/(.*)\((.*)\)/i", $var, $matches)) { 
                    $func = $matches[1];
                    if ($matches[2]) { // with arguments
                        $args = explode(',', $matches[2]);
                        for ($i =0; $i < count($args); $i++) {
                            $args[$i] = trim($args[$i]); // strip whitespace
                            $args[$i] = trim($args[$i], "'\""); // strip quotes
                        }
                        return call_user_func_array($viewmodel->{$func}, $args);
                    }
                    return  $viewmodel->$func();
                }
            } else {
                // just a normal variable
                return $viewmodel->{$var};
            }
            
            return ''; // nothing to do
        }, $aContent);
    }
}
