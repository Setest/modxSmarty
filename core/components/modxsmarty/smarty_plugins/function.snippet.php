<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */


function smarty_function_snippet($params, & $smarty)
{

    if(!isset($params['name']) OR !$name = $params['name']){return;}
    if(!empty($params['assign'])){
        $assign = (string)$params['assign'];
    }

    $modx = & $smarty->modx;
    $modx->getParser();
    $scriptProperties = array();

    if(isset($params['params'])){
        $scriptProperties = $params['params'];
        // Check if String
        if(is_string($scriptProperties)){
            $scriptProperties = $modx->parser->parseProperties($scriptProperties);
        }
    }

    // для того чтобы получить вывод сниппета через return
    $direct = (isset($params['direct']) && ($params['direct']||$scriptProperties['direct']) )? true :false;

    if (!$direct){
        $output = $modx->runSnippet($name, $scriptProperties);
    }else{
        if($s = $modx->getObject('modSnippet', array(
            'name' => $name,
        ))){
            $s->loadScript();   // если запустить его ранее через runSnippet, то валит ошибку мол двойное объявление функции
            $f = $s->getScriptName();   // имя функции из кеша в данном случае elements_modsnippet_11
            $output  = $f($scriptProperties);
        }
    }

    if(!$direct && isset($params['parse']) && $params['parse'] == 'true'){
        $maxIterations= intval($modx->getOption('parser_max_iterations', $params, 10));
        $modx->parser->processElementTags('', $output, true, false, '[[', ']]', array(), $maxIterations);
        $modx->parser->processElementTags('', $output, true, true, '[[', ']]', array(), $maxIterations);
    }

    return !empty($assign) ? $smarty->assign($assign, $output) : $output;
}

?>