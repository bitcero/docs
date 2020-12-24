<ul class="list-unstyled">
<{foreach item=section from=$block.sections}>
<li style="padding-left: <{$section.jump*10}>px;">
	<{if $section.jump==0 && $section.nameid!=$block.section}>
		<{assign var='switch' value=1}>
	<{elseif $section.jump==0 && $section.nameid==$block.section}>
		<{assign var='switch' value=0}>
	<{/if}>
	<{if $section.nameid==''}>
		<{$section.number}>. <{$section.title}>
	<{else}>
		<a href="<{$section.link}>"><{$section.number}>. <{$section.title}></a>
	<{/if}>
</li>
<{/foreach}>
</ul>
