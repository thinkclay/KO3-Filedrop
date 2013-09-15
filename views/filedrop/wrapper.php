<h2>Filedrop</h2>
<div id="module-filedrop" class="clearfix clear">
	<header></header>
	<div id="folder-list">
		<h3>Folders:</h3>
		<ul>
			<li class="parent">
				<strong>My Documents</strong>
				<ul>
					<?php if (count($data) < 1) : ?>
					<li><em>You have no folders yet</em></li>
					<?php else : ?>
					<?php foreach ($data as $k => $v) : ?>
					<li 
						<?php if (isset($data[$k]['directory'])) echo 'data-directory="'.$data[$k]['directory'].'"'; ?>
						<?php if (isset($data[$k]['hash'])) echo 'data-hash="'.$data[$k]['hash'].'"'; ?>
						<?php if (isset($data[$k]['time'])) echo 'data-time="'.$data[$k]['time'].'"'; ?>
					>
						<em><?php echo (isset($data[$k]['name'])) ? $data[$k]['name'] : $data[$k]['hash']; ?></em>
						<span class="rename hidden">rename</span>
						<span class="delete hidden">delete</span>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
					<li id="folder-new"><em>new folder</em></li>
				</ul>
			</li>
		</ul>
	</div>
	<div id="file-upload">
		<p id="file-status">File API &amp; FileReader API not supported</p>
  		<p>
  			Drag an image from your desktop on to the drop zone above to see the browser read the contents 
  			of the file and use it as the background - without uploading the file to any servers.
  		</p>
  		<input type="file" id="file-upload-field" />
	</div>
	<div id="file-list">
		
	</div>
	<footer></footer>
</div>


<script src="/resources/scripts/jquery/filedrop.js"></script>