<?=$before_widget?>
<?=$before_title . $title . $after_title?>
<div class="minepress" data-minepress-host="exscope.net" data-minepress-url="<?=Minepress::url()?>">
	<div>
		<strong>host:</strong>
		<span class="minepress-host"></span>
		(<span class="minepress-ping"></span>)
	</div>
	<div>
		<strong>players:</strong>
		<span class="minepress-players"></span>
		(<span class="minepress-max-players"></span> max)
	</div>
	<div>
		<strong>message:</strong>
		<span class="minepress-motd"></span>
	</div>
	<div>
		<strong>load:</strong>
		<span class="minepress-load"></span>
	</div>
	<div>
		<strong>ram:</strong>
		<span class="minepress-ram"></span>
	</div>
	<div>
		<strong>uptime:</strong>
		<span class="minepress-uptime"></span>
	</div>
</div>
<?=$after_widget?>
