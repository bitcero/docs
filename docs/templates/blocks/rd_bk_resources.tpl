<{if $block.resources}>
<ul class="rd_block_list">
<{foreach item=res from=$block.resources}>
    <li>
        <h3 style="margin: 0;"><a href="<{$res.link}>"><{$res.title}></a></h3>
        <span class="description"><{$res.desc}></span>
        <span class="info"><{$res.reads}> <br><{$res.author}></span>
    </li>
<{/foreach}>
</ul>
<{/if}>