{extends 'app.tpl'}
{block 'content'}
	<div class="container">
		<div class="container">
			<h1>{$post.title}</h1>                
			<p>Posted on {$post.date}</p>    
			<div>{$content}</div>
		</div>
	</div>	
{/block}
