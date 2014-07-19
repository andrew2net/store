<?php
class JSTree extends CInputWidget{

	/**
	 * Plugins to load for the tree
	 * @var array
	 */
	public $plugins = array("themes", "html_data", "sort", "ui");

	public $bind = array();

	/**
	 * Form the array that will be fed directly into the JQuery plugin
	 *
	 * @return The array that contains the configuration of the widget
	 */
	public function makeOptions(){

		$plugins_array = array(); // We need to split out the listed plugins from their config
		$config_array = array();

		foreach($this->plugins as $plugin=>$config){ // Scroll through the array given to us by the user

			$plugins_array[] = is_numeric($plugin) ? $config : $plugin; // If the array key is numeric then the user has put no config to the plugin

			if(!is_numeric($plugin)){ // Then add this plugin to the config list
				$config_array[$plugin] = $config;
			}
		}

		return array_merge(
			$config_array, array("plugins"=>$plugins_array) // Mege the two so we have loaded plugins with their config
		);
	}

	/**
	 * @see framework/CWidget::run()
	 * @return $html The HTML of the tree object
	 */
	public function run(){

		list($name, $id) = $this->resolveNameID(); // Lets get the model attribute so we can make the form up

		// Lets publish the assets and get the ClientScript object so we add js etc
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
		$assets = Yii::app()->getAssetManager()->publish($dir);
		$cs = Yii::app()->getClientScript();

		$js_binds = '';
		foreach($this->bind as $event => $function){
			$js_binds .= CJavaScript::encode("js:$('.js_tree_".$this->attribute." div').bind('".$event."', $function);");
		}

		$cs->registerScriptFile($assets.'/jquery.jstree.js');
		$cs->registerScript('Yii.'.get_class($this).'#'.$id, '
			$(function(){
				$(".js_tree_'.$this->attribute.' div").bind("loaded.jstree", function (event, data) {
					$c_selected = [];
					data.inst.get_checked().each(function(i, node){
						$c_selected[$c_selected.length] = $(node).find(":checkbox").val();
					});
					$(this).parent().children("input").val(JSON.stringify($c_selected));
				}).jstree(
					'.CJavaScript::encode($this->makeOptions()).'
				);

				//Array Remove - By John Resig (MIT Licensed)
				Array.prototype.remove = function(from, to) {
				  var rest = this.slice((to || from) + 1 || this.length);
				  this.length = from < 0 ? this.length + from : from;
				  return this.push.apply(this, rest);
				};

				$(".js_tree_'.$this->attribute.' div").bind("check_node.jstree", function(e, data){
					var obj = data.rslt.obj.children(":checkbox");

					if($(this).parent().children("input").val() == "" || $(this).parent().children("input").val() == null){
						$c_selected = []; // if empty make new object
					}else{
						$c_selected = JSON.parse($(this).parent().children("input").val()); // Get all currently selected in this list
					}

					// Search array for the value if it does not exist add it
					var found = false;
					for(var i=0; i<$c_selected.length; i++){
						if($c_selected[i] == obj.val())
							found = true;
					}

					if(!found){
						$c_selected[$c_selected.length] = obj.val();
					}
					$(this).parent().children("input").val(JSON.stringify($c_selected));
				});

				$(".js_tree_'.$this->attribute.' div").bind("uncheck_node.jstree", function(e, data){
					var obj = data.rslt.obj.children(":checkbox");

					if($(this).parent().children("input").val() == "" || $(this).parent().children("input").val() == null){
						$c_selected = []; // if empty make new object
					}else{
						$c_selected = JSON.parse($(this).parent().children("input").val()); // Get all currently selected in this list
					}

					// Search array for the value if it does not exist add it
					for(var i=0; i<$c_selected.length; i++){
						if($c_selected[i] == obj.val())
							$c_selected.remove(i);
					}
					$(this).parent().children("input").val(JSON.stringify($c_selected));
				});

				'.$js_binds.'
			});
		', CClientScript::POS_READY); // Add the initial load of the JS widget to the page

		//$cs->registerScript('Yii.'.get_class($this).'#'.$id.'.binds', $js_binds);

		$html = CHTML::openTag("div", array("class"=>"js_tree_".$this->attribute)); // Start building the html
			$html .= CHTML::openTag("div");
			$html .= CHTML::closeTag("div");
			$html .= CHTML::activeTextField($this->model, $this->attribute, array("style"=>"display:none;"));
		$html .= CHTML::closeTag("div");

		echo $html; // Return the full tree and all its components
	}
}