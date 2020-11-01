<?php
function cmp( $a, $b ) { 
    if(  $a->vigencia ==  $b->vigencia ){ return 0 ; } 
    return ($a->vigencia < $b->vigencia) ? -1 : 1;
  } 
?>