<?php 
 
    $list = new Telenok\Core\Module\Users\ProfileEdit\Controller();
    
    $data = $list->edit(452);
    
    echo $data['tabContent'];