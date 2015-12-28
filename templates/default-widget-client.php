<?php
echo $widget['before_widget'];

if($widget['title']) {
	echo $widget['before_title'] . $widget['title'] . $widget['after_title'];
}

echo $widget['markup'];

echo $widget['after_widget'];