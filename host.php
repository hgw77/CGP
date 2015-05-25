<?php

require_once 'conf/common.inc.php';
require_once 'inc/html.inc.php';
require_once 'inc/collectd.inc.php';

$host = validate_get(GET('h'), 'h');
$plugin = validate_get(GET('p'), 'p');

$selected_plugins = !$plugin ? $CONFIG['overview'] : array($plugin);

html_start();

printf("<fieldset id=\"%s\">", htmlentities($host));
printf("<legend>%s</legend>", htmlentities($host));


if (!strlen($host) || !$plugins = collectd_plugins($host)) {
	echo "Unknown host\n";
	return false;
}

plugins_list($host, $selected_plugins);

echo '<div class="graphs">';
foreach ($selected_plugins as $selected_plugin) {
	if (in_array($selected_plugin, $plugins)) {
		plugin_header($host, $selected_plugin);
		graphs_from_plugin($host, $selected_plugin, empty($plugin));
	}
}
echo '</div>';
printf("</fieldset>");

html_end();
