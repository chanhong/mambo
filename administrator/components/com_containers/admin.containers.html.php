<?php

class containersAdminHTML extends basicAdminHTML {
	var $repository = '';
	var $clist = '';

	function containersAdminHTML (&$controller, $limit, $clist) {
	    basicAdminHTML::basicAdminHTML($controller, $limit);
		$this->repository =& $controller->repository;
		$this->clist = $clist;
	}

	function displayIcons ($object, $iconList) {
		if (is_object($object)) $icon = $object->icon;
		else $icon = '';
		?>
		<script type="text/javascript">
		function paste_strinL(strinL){
			var input=document.forms["adminForm"].elements["icon"];
			input.value='';
			input.value=strinL;
		}
		</script>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo T_('Icon'); ?></b>&nbsp;
			</td>
			<td valign="top">
				<input class="inputbox" type="text" name="icon" size="25" value="<?php echo $icon; ?>" />
				<table>
					<tr>
						<td>
							<?php echo $iconList; ?>
						</td>
					</tr>
				</table>
			</td>
  		</tr>
  		<?php
	}

	function listHeader ($descendants, $search) {
		?>
		<tr>
    		<td align="left"><?php echo T_('Display number').$this->pageNav->writeLimitBox(); ?>
			</td>
			<td align="left"><?php echo T_('Search:'); ?><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
    		</td>
			<td align="left"><?php echo T_('Show Descendants?'); ?><input type="checkbox" name="descendants" value="1" <?php if ($descendants) echo 'checked="checked"'; ?> onChange="document.adminForm.submit();" />
			</td>
		</tr>
		<tr>
		<?php
		if ($this->clist<>'') {
			echo '<td align="left" colspan=3>'.$this->clist.'</td>';
		}
		echo '</tr>';
	}

	function containerSelectBox () {
		?>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo T_('Parent container'); ?></b>&nbsp;
			</td>
			<td valign="top">
				<?php echo $this->clist; ?>
			</td>
		</tr>
		<?php
	}

	function startEditHeader ($title) {
		?>
		<form method="post" name="adminForm" action="index2.php">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
   		<tr>
			<td width="100%" colspan="4">
			<div class="title">
			<img src="<?php echo mamboCore::get('mosConfig_live_site').'/administrator/images/asterisk.png'; ?>" alt="<?php echo $title; ?>" />
			<span class="sectionname">&nbsp;<?php echo T_('Mambo '); echo $title; ?></span>
			</div>
			</td>
    	</tr>
		<?php
		$this->blankRow();
		$this->containerSelectBox();
	}

	function publishedBox (&$object) {
		?>
				<tr>
					<td width="30%" align="right">
				  	<b><?php echo T_('Published'); ?></b>&nbsp;
				  </td>
				  	<?php $this->tickBox($object, 'published'); ?>
				</tr>
		<?php
	}

	function editLink ($id, $containerid=0) {
		$url = "index2.php?option=com_containers&amp;act=$this->act&amp;task=edit&amp;cfid=$id";
		if ($containerid) $url .= "&amp;containerid=$containerid";
		return $url;
	}

	function legalTypeList ($current) {
		$alternatives = explode(',',_REMOS_LEGAL_TYPES);
		foreach ($alternatives as $one) {
			if ($one == $current) $mark = 'selected=\'selected\'';
			else $mark = '';
			echo "<option $mark value='$one'>$one</option>";
		}
	}

}

class listContainersHTML extends containersAdminHTML {

	function columnHeads ($containers, $descendants) {
		$this->listHeadingStart(count($containers));
		$this->headingItem('30%', T_('Title'));
		if ($this->clist) {
			$this->headingItem('5%', 'ID');
			if (!$descendants) {
				$this->headingItem('5%', T_('Reorder'), 2);
				$this->headingItem('2%', T_('Order'));
				?>
				<th width="1%">
				<a href="javascript: saveorder( <?php echo count( $containers )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo T_('Save Order'); ?>" /></a>
				</th>
				<?php
			}
			$this->headingItem('25%', T_('Top level container'));
			$this->headingItem('25%', T_('Immediate container'));
		}
		$this->headingItem('7%', T_('Published'));
		echo '</tr>';
	}

	function filecount ($container) {
		if ($container->filecount) {
			$link = "<a href='index2.php?option=com_containers&amp;act=files&amp;task=list&amp;containerid=$container->id'>";
			$link .= $container->filecount;
			$link .= '</a>';
			return $link;
		}
		else return '0';
	}

	function listLine ($container, $descendants, $i, $k, $n) {
		global $mosConfig_live_site;
		?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="5">
						<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $container->id; ?>" onclick="isChecked(this.checked);" />
					</td>
					<td width="30%" align="left">
							<a href="<?php echo $this->editLink($container->id); ?>">
							<?php echo $container->name; ?>
						</a>
					</td>
					<?php if ($this->clist) { ?>
					<td width="5%" align="left"><?php echo $container->id; ?></td>
					<?php if (!$descendants) { echo '<td>'.$this->pageNav->orderUpIcon( $i ); ?>
					</td>
					<td>
					<?php echo $this->pageNav->orderDownIcon( $i, $n ); ?>
					</td>
					<td align="center" colspan="2">
					<input type="text" name="order[]" size="5" value="<?php echo $container->ordering; ?>" class="text_area" style="text-align: center" />
					<?php echo '</td>'; } ?>
					<td width="25%" align="left"><?php echo $container->getCategoryName();?></td>
					<td width="25%" align="left"><?php echo $container->getFamilyNames();?></td>
					<?php }
					if ($container->published==1) { ?>
					<td width="7%" align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/publish_g.png" border="0" alt="Published" /></td>
					<?php } else { ?>
					<td width="7%" align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/publish_x.png" border="0" alt="Published" /></td>
					<?php } ?>
					</td>
				</tr>
		<?php
	}

	// was showContainersHTML
	function view (&$containers, $descendants, $search='')  {
		$this->formStart(T_('Containers'), mamboCore::get('mosConfig_live_site').'/administrator/images/asterisk.png');
		$this->blankRow();
		$this->listHeader($descendants, $search);
		echo '</table>';
		$this->columnHeads($containers, $descendants);
		$n = count($containers);
		$k = 0;
		foreach ($containers as $i=>$container) {
			$this->listLine($container, $descendants, $i, $k, $n);
			$k = 1 - $k;
		}
		$this->listFormEnd();
	}
}

class editContainersHTML extends containersAdminHTML {

	function selectList ($title, $selector, $redstar) {
		$this->inputTop ($title, $redstar);
		?>
			<td valign="top">
				<?php echo $selector; ?>
			</td>
		</tr>
		<?php
	}

	function permission ($title,$container,$updown,$name) {
		$this->inputTop($title, true);
		?>
					<td valign="top">
					<?php
					for ($i=0; $i<4; $i++) {
						echo '<input type="radio" name="'.$name.'" value="'.$i;
						if ($container->$name == $i) echo '" checked="checked" />';
						else echo '" />';
						echo $updown[$i];
					}
					?>
				    </td>
			</tr>
		<?php
	}

	function groupOptions ($object, $property) {
		?>
		<td valign="top">
			<select NAME="<?php echo $property; ?>" class="inputbox">
				<option value="0"><?php echo _GLOBAL; ?></option>
				<option value="1" <?php if ($object->$property) echo 'selected="selected"'; echo '>'._YES; ?></option>
			</select>
		</td>
		<?php
	}

	function view (&$container)
	{
		$iconList = mosContainer::getIcons ();
		$this->commonScripts('description');
		echo '<br/>';
		$this->startEditHeader(T_('Edit Container details'));
		$this->publishedBox($container);
		$this->fileInputBox(T_('Container name'), 'name', $container->name, 50);
		$this->fileInputArea(T_('Description'), T_('Up to 500 characters'), 'description', $container->description, 50, 100, true);
		$this->fileInputBox(T_('Keywords'),'keywords',$container->keywords,50);
		$this->fileInputBox(T_('Window title'),'windowtitle',$container->windowtitle,50);
		$this->displayIcons($container, $iconList);
		$this->editFormEnd ($container->id);
	}
}

?>
