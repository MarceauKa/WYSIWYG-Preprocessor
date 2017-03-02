<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class StripTags
 *
 * @package Akibatech\Wysiwyg\Modifier
 *
 * This class will strip out any tags that are not specified in the options['tags'] array
 * Within each specified tag, a user can specify which attributes they want to allow with the 'allow_attr' array
 * Within each attribute, a user can specify allowed beginnings to its value (This was to address this issue of
 * javascript injection (e.g. href="javascript:stealTheirStuff()"))
 * Also within each attribute, a user can insert attributes - e.g. target="_new" on anchor tags
 */
class TreatTags extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $tags = $this->options['tags'];
        $tags['html'] = [];
        $tags['body'] = [];
        $tags['p'] = [];
        $dom = new \DOMDocument();
        $dom->loadHTML($input);
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//*');
        foreach ($nodes as $node) {
            $tagName = $node->tagName;
            $nodeName = $node->nodeName;
            $nodeValue = $node->nodeValue;
            if (isset($tags[$tagName])){
                foreach ($node->attributes as $attr){
                    if (isset($tags[$tagName]['allow_attr'][$attr->name])){
                        $protocol_is_ok = false;
                        if (isset($tags[$tagName]['allow_attr'][$attr->name]['allow_to_begin_with'])){
                            foreach($tags[$tagName]['allow_attr'][$attr->name]['allow_to_begin_with'] as $proto){
                                if ($proto == substr($attr->value, 0, strlen($proto))){
                                    $protocol_is_ok = true;
                                }
                            }
                            if (!$protocol_is_ok){
                                $node->removeAttribute($attr->name);
                            }
                        }
                        foreach($tags[$tagName]['insert_attr'] as $attr=>$val){
                            $node->setAttribute($attr, $val);
                        }
                    }else{
                        $node->removeAttribute($attr->name);
                    }
                }
            }else{
                $node->parentNode->removeChild($node);
            }
        }
        $output = $dom->saveHTML($dom->getElementsByTagName('html')->item(0)->getElementsByTagName('body')->item(0)->getElementsByTagName('p')->item(0));
        
        $output = substr($output, 3, count($output) - 5);

        return $output;
    }
    
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            
        ];
    }

    //-------------------------------------------------------------------------
    
}