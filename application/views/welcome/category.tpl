{extends file='base-layout.tpl'}
{block name='main'}
	{jsx}
	{literal}
		<div className='header'>
			<widgets.Categorysearch />
			<widgets.Albumlist list={datastore.get('categories')}/>
		</div>
	{/literal}
	{/jsx}
{/block}
