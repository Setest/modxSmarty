<?php

function smarty_modifier_spell($num, $one, $two, $many) {
    if ($num%10==1 && $num%100!=11){
        echo $one;
    }
    elseif($num%10>=2 && $num%10<=4 && ($num%100<10 || $num%100>=20)){
        echo $two;
    }
    else{
        echo $many;
    }
}
?>
