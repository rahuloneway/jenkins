<?php

if (count($template) <= 0) {
    echo '<tr>';
    echo '<td colspan="11">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('TEMPLATE_RECORD_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {

    foreach ($template as $key => $value) {
        echo '<tr>';
        echo '<td>'.$value->Template_Name.'</td>';
        echo '<td>'.substr($value->Template_Text,0,200).'</td>';
        $id = $this->encrypt->encode($value->Id);
        echo '<td><a  id="'.$id.'" title="Click to edit template" data-placement="right" data-toggle="tooltip" class="btn btn-primary btn-xs color config-edit-email-template" href="#"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        echo '</tr>';
    }
}
?>